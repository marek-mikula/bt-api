<?php

namespace Domain\Cryptocurrency\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\DataPaginatedResourceCollection;
use App\Http\Resources\DataResource;
use App\Models\Currency;
use Domain\Cryptocurrency\Services\CryptocurrencyService;
use Illuminate\Http\JsonResponse;

class CryptocurrencyController extends ApiController
{
    public function __construct(
        private readonly CryptocurrencyService $service,
    ) {
    }

    public function index(AuthRequest $request): JsonResponse
    {
        $page = $request->integer('page', 1);

        $data = $this->service->getDataForIndex(page: $page);

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'cryptocurrencies' => new DataPaginatedResourceCollection($data),
        ]);
    }

    public function show(AuthRequest $request, Currency $cryptocurrency): JsonResponse
    {
        $user = $request->user('api');

        $data = $this->service->getDataForShow($user, $cryptocurrency);

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'cryptocurrency' => new DataResource($data),
        ]);
    }
}
