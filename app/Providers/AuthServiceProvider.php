<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\models\party;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerPaperPolicies();
    }
    public function registerPaperPolicies(){
        Gate::define('create-paper',function ($user){
            return $user->party()->hasAccess(['create-post']);
        });
        Gate::define('read-paper',function ($user){
            return $user->party->hasAccess(['read-paper']);
        });
    }
}
