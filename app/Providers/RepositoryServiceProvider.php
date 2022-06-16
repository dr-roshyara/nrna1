<?php

namespace App\Providers;

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
        //
        app()->bind(ImageRepositoryInterface::class, function(){
            return new ImageRepository();
            // dd("test");
        });
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
