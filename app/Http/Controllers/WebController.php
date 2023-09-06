<?php

namespace App\Http\Controllers;

use Domain\User\Services\AssetSyncService;
use Illuminate\Contracts\View\View;

class WebController extends Controller
{
    public function welcome(): View
    {
        return view('welcome');
    }

    public function test(): void
    {
        /** @var AssetSyncService $service */
        $service = app(AssetSyncService::class);

        $service->sync();
    }
}
