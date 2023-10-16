<?php

namespace Domain\Currency\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Models\CurrencyPair;
use Domain\Currency\Services\PairService;
use Illuminate\Http\JsonResponse;

class PairController extends ApiController
{
    public function __construct(
        private readonly PairService $service,
    ) {
    }

    public function price(CurrencyPair $pair): JsonResponse
    {
        $price = $this->service->getPairPrice($pair);

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'price' => $price,
        ]);
    }
}
