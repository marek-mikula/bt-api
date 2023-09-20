<?php

namespace Domain\Search\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\DataResourceCollection;
use Domain\Search\Services\SearchService;
use Illuminate\Http\JsonResponse;

class SearchController extends ApiController
{
    public function __construct(
        private readonly SearchService $searchService,
    ) {
    }

    public function search(AuthRequest $request): JsonResponse
    {
        $q = $request->string('q', '');

        $results = $this->searchService->search($q);

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'results' => new DataResourceCollection($results),
        ]);
    }
}
