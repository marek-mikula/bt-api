<?php

namespace Domain\Quiz\Providers;

use Domain\Quiz\Http\Middleware\QuizMiddleware;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class QuizRouteServiceProvider extends RouteServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('/api/quiz')
                ->as('api.quiz.')
                ->group(__DIR__.'/../Routes/quiz.php');
        });

        $this->aliasMiddleware('quiz', QuizMiddleware::class);
    }
}
