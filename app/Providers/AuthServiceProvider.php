<?php

namespace App\Providers;

use App\Policies\LotPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\Entity\Lot' => 'App\Policies\LotPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('createLot', 'App\Policies\LotPolicy@createLot');
        Gate::define('buyLot', 'App\Policies\LotPolicy@buyLot');
    }
}
