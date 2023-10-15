<?php

namespace Domain\Order\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\OrderPaginatedResourceCollection;
use App\Http\Resources\OrderResource;
use App\Repositories\Order\OrderRepositoryInterface;
use Domain\Order\Exceptions\OrderValidationException;
use Domain\Order\Http\Requests\OrderRequest;
use Domain\Order\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends ApiController
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderService $service,
    ) {
    }

    public function index(AuthRequest $request): JsonResponse
    {
        $user = $request->user('api');

        $page = $request->integer('page', 1);

        $orders = $this->orderRepository->index($user, $page);

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'orders' => new OrderPaginatedResourceCollection($orders),
        ]);
    }

    public function create(OrderRequest $request): JsonResponse
    {
        $user = $request->user('api');

        try {
            $order = $this->service->placeOrder($user, $request->toData());
        } catch (OrderValidationException $e) {
            throw $e->toValidationException();
        }

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'order' => new OrderResource($order),
        ]);
    }
}
