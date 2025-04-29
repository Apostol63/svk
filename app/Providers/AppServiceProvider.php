<?php

namespace App\Providers;

use App\Repositories\Api\Contracts\EventRepositoryInterface;
use App\Repositories\Api\EventRepository;
use App\Repositories\Api\OtherEventRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            EventRepositoryInterface::class,
            EventRepository::class
        );

        //TODO тот же интерфейс, другая внутренняя реализация. Контроллеры и сервисы никак не затрагиваются
//        $this->app->bind(
//            EventRepositoryInterface::class,
//            OtherEventRepository::class
//        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
