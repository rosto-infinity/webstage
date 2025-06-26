<?php

namespace App\Providers;

use App\Models\SocialMedia;
use App\Policies\SocialMediaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        SocialMedia::class => SocialMediaPolicy::class,
    ];

    /**
     * Register any authentication/authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
