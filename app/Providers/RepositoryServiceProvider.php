<?php

namespace App\Providers;

use App\Contexts\Membership\Infrastructure\Persistence\CommitteeRepositoryPort;
use App\Contexts\Membership\Infrastructure\Persistence\EloquentCommitteeRepository;
use App\Interfaces\ImageRepositoryInterface;
use App\Repositories\ImageRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind(ImageRepositoryInterface::class, function(){
            return new ImageRepository();
        });

        app()->bind(CommitteeRepositoryPort::class, EloquentCommitteeRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
