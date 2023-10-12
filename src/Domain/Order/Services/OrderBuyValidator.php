<?php

namespace Domain\Order\Services;

use Apis\Binance\Data\OrderData;
use Apis\Binance\Http\BinanceApi;
use Apis\Coinmarketcap\Http\CoinmarketcapApi;
use App\Models\Asset;
use App\Models\CurrencyPair;
use App\Models\Limits;
use App\Models\User;
use App\Repositories\Limits\LimitsRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use Domain\Currency\Enums\MarketCapCategoryEnum;
use Domain\Order\Enums\OrderErrorEnum;
use Domain\Order\Exceptions\OrderValidationException;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class OrderBuyValidator
{
    public function __construct(
        private readonly LimitsRepositoryInterface $limitsRepository,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly CoinmarketcapApi $coinmarketcapApi,
        private readonly BinanceApi $binanceApi,
    ) {
    }

    /**
     * @throws OrderValidationException
     */
    public function validate(User $user, CurrencyPair $pair, OrderData $order): void
    {
        $pair->loadMissing([
            'baseCurrency',
            'quoteCurrency',
        ]);

        // fetch current price from binance API

        $pairPrice = $this->binanceApi->marketData->symbolPrice($pair->symbol)
            ->json('price');

        $pairPrice = floatval($pairPrice);

        // calculate notional value

        $notionalValue = $pairPrice * $order->quantity;

        // fetch user current limits

        $limits = $this->limitsRepository->findOrCreate($user);

        // validate min quantity
        if (! empty($pair->min_quantity) && $pair->min_quantity > $order->quantity) {
            throw new OrderValidationException(OrderErrorEnum::MIN_QUANTITY_EXCEEDED, [
                'min' => $pair->min_quantity,
            ]);
        }

        // validate max quantity
        if (! empty($pair->max_quantity) && $pair->max_quantity < $order->quantity) {
            throw new OrderValidationException(OrderErrorEnum::MAX_QUANTITY_EXCEEDED, [
                'max' => $pair->max_quantity,
            ]);
        }

        // validate step size
        $this->validateStepSize($pair, $order);

        // validate min notional value
        if (! empty($pair->min_notional) && $pair->min_notional > $notionalValue) {
            throw new OrderValidationException(OrderErrorEnum::MIN_NOTIONAL_EXCEEDED, [
                'min' => $pair->min_notional,
            ]);
        }

        // validate max notional value
        if (! empty($pair->max_notional) && $pair->max_notional < $notionalValue) {
            throw new OrderValidationException(OrderErrorEnum::MAX_NOTIONAL_EXCEEDED, [
                'max' => $pair->max_notional,
            ]);
        }

        // validate available funds (count in waiting orders)
        $this->validateFunds($user, $pair, $notionalValue);

        // validate number of trades (daily, weekly, monthly)
        $this->validateNumberOfTrades($user, $limits);

        // validate number of cryptocurrencies
        $this->validateNumberOfAssets($user, $limits, $pair);

        // validate market cap limits
        $this->validateMarketCap($user, $limits, $pair, $order, $notionalValue);
    }

    /**
     * @throws OrderValidationException
     */
    private function validateNumberOfTrades(User $user, Limits $limits): void
    {
        // limits are turned off
        if (! $limits->trade_enabled) {
            return;
        }

        // check number of daily trades
        if (! empty($limits->trade_daily) && (($val = $this->orderRepository->getDailyOrderCount($user)) + 1) > $limits->trade_daily) {
            throw new OrderValidationException(OrderErrorEnum::DAILY_TRADES_EXCEEDED, [
                'max' => $limits->trade_daily,
            ]);
        }

        // check number of weekly trades
        if (! empty($limits->trade_weekly) && (($val = $this->orderRepository->getWeeklyOrderCount($user)) + 1) > $limits->trade_weekly) {
            throw new OrderValidationException(OrderErrorEnum::WEEKLY_TRADES_EXCEEDED, [
                'max' => $limits->trade_weekly,
            ]);
        }

        // check number of monthly trades
        if (! empty($limits->trade_monthly) && (($val = $this->orderRepository->getMonthlyOrderCount($user)) + 1) > $limits->trade_monthly) {
            throw new OrderValidationException(OrderErrorEnum::MONTHLY_TRADES_EXCEEDED, [
                'max' => $limits->trade_monthly,
            ]);
        }
    }

    /**
     * @throws OrderValidationException
     */
    private function validateNumberOfAssets(User $user, Limits $limits, CurrencyPair $pair): void
    {
        // limits are turned off
        if (! $limits->cryptocurrency_enabled) {
            return;
        }

        // if user has already the asset
        // he is trying to buy, we don't
        // have to check the limits,
        // because no new asset will be added
        // to his wallet

        $userHasAsset = $user
            ->assets()
            ->where('currency_id', '=', $pair->base_currency_id)
            ->exists();

        if ($userHasAsset) {
            return;
        }

        $numberOfAssets = $user->assets()->count();

        if (! empty($limits->cryptocurrency_max) && ($numberOfAssets + 1) > $limits->cryptocurrency_max) {
            throw new OrderValidationException(OrderErrorEnum::MAX_ASSETS_EXCEEDED, [
                'max' => $limits->cryptocurrency_max,
            ]);
        }
    }

    /**
     * @throws OrderValidationException
     */
    private function validateFunds(User $user, CurrencyPair $pair, float $notionalValue): void
    {
        /** @var Asset|null $asset */
        $asset = $user
            ->assets()
            ->where('currency_id', '=', $pair->quote_currency_id)
            ->first();

        // user does not own the quote asset

        if (! $asset) {
            throw new OrderValidationException(OrderErrorEnum::NO_FUNDS, [
                'funds' => 0,
            ]);
        }

        $funds = $this->orderRepository->sumWaitingOrderQuotes($user, $pair) + $asset->balance;

        if ($funds < $notionalValue) {
            throw new OrderValidationException(OrderErrorEnum::NO_FUNDS, [
                'funds' => round($funds, 2),
            ]);
        }
    }

    /**
     * @throws OrderValidationException
     */
    public function validateStepSize(CurrencyPair $pair, OrderData $order): void
    {
        // modulo between two tiny
        // float numbers is kinda tricky,
        // though we have to use something
        // else than fmod

        if (empty($pair->step_size)) {
            return;
        }

        $quantityAsString = sprintf("%.{$pair->base_currency_precision}f", $order->quantity);
        $stepSiteAsString = sprintf("%.{$pair->base_currency_precision}f", $pair->step_size);

        // validate step size
        if (bcmod($quantityAsString, $stepSiteAsString) !== '0') {
            throw new OrderValidationException(OrderErrorEnum::STEP_SIZE_INVALID, [
                'step' => $pair->step_size,
            ]);
        }
    }

    /**
     * @throws OrderValidationException
     */
    public function validateMarketCap(User $user, Limits $limits, CurrencyPair $pair, OrderData $order, float $notionalValue): void
    {
        if (! $limits->market_cap_enabled) {
            return;
        }

        $percentages = [
            MarketCapCategoryEnum::MICRO->value => 0.0,
            MarketCapCategoryEnum::SMALL->value => 0.0,
            MarketCapCategoryEnum::MID->value => 0.0,
            MarketCapCategoryEnum::LARGE->value => 0.0,
            MarketCapCategoryEnum::MEGA->value => 0.0,
        ];

        $orderCategories = [];

        // load base and quote currency of the order
        //
        // we are buying the base currency for quote
        // currency -> that means we have to check
        // both of these market cap categories
        // because both categories will be changing

        $baseCurrency = $pair->loadMissing('baseCurrency')->baseCurrency;
        $quoteCurrency = $pair->loadMissing('quoteCurrency')->quoteCurrency;

        // retrieve all users assets,
        // scope them only for cryptocurrencies

        $assets = $user
            ->assets()
            ->with('currency')
            ->whereHas('currency', static function (Builder $query): void {
                $query->where('is_fiat', '=', 0);
            })
            ->get();

        // pluck Coinmarketcap IDs
        // and add base currency and
        // quote currency CMC ID

        $ids = $assets
            ->pluck('currency.cmc_id')
            ->push($baseCurrency->cmc_id)
            ->push($quoteCurrency->cmc_id)
            ->unique();

        // retrieve quotes for given
        // currencies

        $quotes = $this->coinmarketcapApi->quotes($ids->unique()->toArray())
            ->collect('data');

        // add base currency value to
        // the percentages array

        /** @var array|null $quote */
        $quote = $quotes->get($baseCurrency->cmc_id);

        if (! $quote) {
            throw new Exception("Missing quotes for base currency {$baseCurrency->symbol}.");
        }

        $currency = (string) collect($quote['quote'])->keys()->first();

        $enum = MarketCapCategoryEnum::createFromValue($quote['quote'][$currency]['market_cap']);

        $orderCategories[] = $enum->value;

        $percentages[$enum->value] += ($order->quantity * $quote['quote'][$currency]['price']);

        // add quote currency value to
        // the percentages array

        /** @var array|null $quote */
        $quote = $quotes->get($quoteCurrency->cmc_id);

        if (! $quote) {
            throw new Exception("Missing quotes for quote currency {$quoteCurrency->symbol}.");
        }

        $currency = (string) collect($quote['quote'])->keys()->first();

        $enum = MarketCapCategoryEnum::createFromValue($quote['quote'][$currency]['market_cap']);

        $orderCategories[] = $enum->value;

        $percentages[$enum->value] -= ($notionalValue * $quote['quote'][$currency]['price']);

        // count the percentages for existing
        // users assets

        /** @var Asset $asset */
        foreach ($assets as $asset) {
            $currency = $asset->currency;

            // check if is fiat just to be sure

            if ($currency->is_fiat) {
                continue;
            }

            /** @var array|null $quote */
            $quote = $quotes->get($currency->cmc_id);

            if (! $quote) {
                throw new Exception("Missing quotes for currency {$currency->symbol}.");
            }

            $quoteCurrency = (string) collect($quote['quote'])->keys()->first();

            $enum = MarketCapCategoryEnum::createFromValue($quote['quote'][$quoteCurrency]['market_cap']);

            $percentages[$enum->value] += ($asset->balance * $quote['quote'][$quoteCurrency]['price']);
        }

        // sum total balance in $

        $totalBalance = collect($percentages)->sum();

        // now check all percentages if they are
        // in the correct percentage span

        foreach ($percentages as $category => $value) {
            // ignore other categories except the ones
            // connected to current order

            if (! in_array($category, $orderCategories)) {
                continue;
            }

            // do not check the limit if it
            // is disabled

            if (! $limits->{"market_cap_{$category}_enabled"}) {
                continue;
            }

            // calculate the numbers the final value
            // should be between

            $between = [
                ((int) $limits->{"market_cap_{$category}"}) - $limits->market_cap_margin,
                ((int) $limits->{"market_cap_{$category}"}) + $limits->market_cap_margin,
            ];

            // calculate the percentage

            $percentage = ($value * 100) / $totalBalance;

            if ($percentage < $between[0] || $percentage > $between[1]) {
                throw new OrderValidationException(OrderErrorEnum::MARKET_CAP_EXCEEDED, [
                    'category' => MarketCapCategoryEnum::from($category)->getTranslatedValue(),
                    'from' => $between[0],
                    'to' => $between[1],
                    'value' => round($percentage, 2),
                ]);
            }
        }
    }
}
