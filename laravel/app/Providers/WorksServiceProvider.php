<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Foundation\Works\Curl;

class WorksServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('curl', function($app) {
            return new Curl;
        });

    }
}