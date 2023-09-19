<?php

namespace Domain\WhaleAlert\Http;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\WhaleAlertPaginatedResourceCollection;
use App\Repositories\WhaleAlert\WhaleAlertRepositoryInterface;
use Illuminate\Http\JsonResponse;

class WhaleAlertController extends ApiController
{
    public function __construct(
        private readonly WhaleAlertRepositoryInterface $whaleAlertRepository,
    ) {
    }

    public function index(AuthRequest $request): JsonResponse
    {
        $page = $request->integer('page', 1);

        $whaleAlerts = $this->whaleAlertRepository->index($page);

        return $this->sendJsonResponse(ResponseCodeEnum::OK, data: [
            'whaleAlerts' => new WhaleAlertPaginatedResourceCollection($whaleAlerts),
        ]);
    }
}
