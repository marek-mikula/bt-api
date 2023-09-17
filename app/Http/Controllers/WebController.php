<?php

namespace App\Http\Controllers;

use Domain\WhaleAlert\Http\WhaleAlertApi;
use Illuminate\Contracts\View\View;

class WebController extends Controller
{
    public function welcome(): View
    {
        return view('welcome');
    }

    public function test(): void
    {
        /** @var WhaleAlertApi $api */
        $api = app(WhaleAlertApi::class);

        $response = $api->transactions(
            from: now()->startOfHour(),
            to: now()->endOfHour(),
            min: 1_000_000,
        );

        file_put_contents(
            domain_path('WhaleAlert', 'Resources/mocks/transactions.json'),
            $response->body(),
        );
    }
}
