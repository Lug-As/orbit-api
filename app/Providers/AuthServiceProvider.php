<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Offer;
use App\Models\Project;
use App\Models\Request;
use App\Models\Response;
use App\Policies\AccountPolicy;
use App\Policies\OfferPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\RequestPolicy;
use App\Policies\ResponsePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Account::class => AccountPolicy::class,
        Offer::class => OfferPolicy::class,
        Project::class => ProjectPolicy::class,
        Request::class => RequestPolicy::class,
        Response::class => ResponsePolicy::class,
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
