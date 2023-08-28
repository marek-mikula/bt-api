<?php

namespace Domain\Cryptocurrency\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\DataResourceCollection;
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

        $perPage = $request->integer('perPage', 100);

        $data = $this->service->getDataForIndex(page: $page, perPage: $perPage);

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'cryptocurrencies' => [
                'data' => new DataResourceCollection($data),
                'meta' => [
                    'page' => $page,
                    'perPage' => $perPage,
                    'end' => $data->count() < $perPage,
                ],
            ],
        ]);
    }
}
