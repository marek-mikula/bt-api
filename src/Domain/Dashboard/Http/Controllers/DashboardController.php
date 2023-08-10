<?php

namespace Domain\Dashboard\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\DataResourceCollection;
use Domain\Dashboard\Cache\DashboardCache;
use Illuminate\Http\JsonResponse;

class DashboardController extends ApiController
{
    public function __construct(
        private readonly DashboardCache $dashboardCache,
    ) {
    }

    public function index(): JsonResponse
    {
        $topCrypto = $this->dashboardCache->getTopCryptocurrencies();

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'topCrypto' => new DataResourceCollection($topCrypto),
        ]);
    }
}
