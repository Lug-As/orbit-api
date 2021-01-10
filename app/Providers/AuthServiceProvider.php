<?php

namespace App\Providers;

use App\Models\Offer;
use App\Models\Request;
use App\Policies\OfferPolicy;
use App\Policies\RequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Request::class => RequestPolicy::class,
        Offer::class => OfferPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
