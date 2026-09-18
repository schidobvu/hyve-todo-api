<?php

namespace App\Providers;

use App\Repositories\Contracts\JobStatusRepositoryInterface;
use App\Repositories\Contracts\TodoRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\EloquentJobStatusRepository;
use App\Repositories\Eloquent\EloquentTodoRepository;
use App\Repositories\Eloquent\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(TodoRepositoryInterface::class, EloquentTodoRepository::class);
        $this->app->bind(JobStatusRepositoryInterface::class, EloquentJobStatusRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
