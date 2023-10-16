<?php

namespace Domain\User\Providers;

use App\Http\Requests\AuthRequest;
use App\Models\Alert;
use App\Repositories\Alert\AlertRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class UserRouteServiceProvider extends RouteServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('/api/users')
                ->as('api.users.')
                ->group(__DIR__.'/../Routes/user.php');
        });

        $this->bootBindings();
    }

    public function bootBindings(): void
    {
        Route::bind('alert', static function (int $value): Alert {
            /** @var AlertRepositoryInterface $repository */
            $repository = app(AlertRepositoryInterface::class);

            /** @var AuthRequest $request */
            $request = request();

            $alert = $repository->findOfUser($request->user('api'), $value);

            if ($alert) {
                return $alert;
            }

            throw (new ModelNotFoundException())->setModel(Alert::class, [$value]);
        });
    }
}
