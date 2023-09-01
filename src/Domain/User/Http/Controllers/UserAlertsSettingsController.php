<?php

namespace Domain\User\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\AlertResource;
use App\Http\Resources\AlertResourceCollection;
use App\Models\Alert;
use App\Repositories\Alert\AlertRepositoryInterface;
use Domain\User\Http\Requests\StoreAlertRequest;
use Domain\User\Services\UserAlertsSettingsService;
use Illuminate\Http\JsonResponse;

class UserAlertsSettingsController extends ApiController
{
    public function __construct(
        private readonly UserAlertsSettingsService $service,
        private readonly AlertRepositoryInterface $alertRepository,
    ) {
    }

    public function index(): JsonResponse
    {
        $alerts = $this->alertRepository->index();

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'alerts' => new AlertResourceCollection($alerts),
        ]);
    }

    public function store(StoreAlertRequest $request): JsonResponse
    {
        $alert = $this->service->store($request->user(), $request->toData());

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'alert' => new AlertResource($alert),
        ]);
    }

    public function delete(Alert $alert): JsonResponse
    {
        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'state' => $alert->delete()
        ]);
    }
}
