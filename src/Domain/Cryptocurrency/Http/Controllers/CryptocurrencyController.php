<?php

namespace Domain\Cryptocurrency\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\DataPaginatedResourceCollection;
use Domain\Cryptocurrency\Services\CryptocurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CryptocurrencyController extends ApiController
{
    public function __construct(
        private readonly CryptocurrencyService $service,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $page = $request->integer('page', 1);

        $data = $this->service->getDataForIndex(page: $page);

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'cryptocurrencies' => new DataPaginatedResourceCollection($data),
        ]);
    }

    public function show(): JsonResponse
    {
        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'cryptocurrencies' => []
        ]);
    }
}
