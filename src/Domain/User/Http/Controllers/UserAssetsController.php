<?php

namespace Domain\User\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AssetResourceCollection;
use App\Repositories\Asset\AssetRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserAssetsController extends ApiController
{
    public function __construct(
        private readonly AssetRepositoryInterface $assetRepository,
    ) {
    }

    public function index(AuthRequest $request): JsonResponse
    {
        $assets = $this->assetRepository->getByUser($request->user('api'));

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'assets' => new AssetResourceCollection($assets),
        ]);
    }
}
