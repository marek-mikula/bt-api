<?php

namespace Domain\Search\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\JsonResponse;

class SearchController extends ApiController
{
    public function search(AuthRequest $request): JsonResponse
    {
        $query = $request->string('query', '');

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [

        ]);
    }
}
