<?php

namespace App\Providers;

use App\Models\SocialMedia;
use App\Policies\SocialMediaPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    // Dans app/Providers/AuthServiceProvider.php
protected $policies = [
    SocialMedia::class => SocialMediaPolicy::class,
];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       Model::automaticallyEagerLoadRelationships();
    }


}
