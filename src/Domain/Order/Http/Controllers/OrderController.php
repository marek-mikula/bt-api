<?php

namespace Domain\Order\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\OrderResource;
use Domain\Order\Exceptions\OrderValidationException;
use Domain\Order\Http\Requests\OrderRequest;
use Domain\Order\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends ApiController
{
    public function __construct(
        private readonly OrderService $service,
    ) {
    }

    public function buy(OrderRequest $request): JsonResponse
    {
        $user = $request->user('api');

        try {
            $order = $this->service->buy($user, $request->toData());
        } catch (OrderValidationException $e) {
            $e->toValidationException();
        }

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'order' => new OrderResource($order),
        ]);
    }

    public function sell(OrderRequest $request): JsonResponse
    {
        $user = $request->user('api');

        try {
            $order = $this->service->sell($user, $request->toData());
        } catch (OrderValidationException $e) {
            $e->toValidationException();
        }

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'order' => new OrderResource($order),
        ]);
    }
}
