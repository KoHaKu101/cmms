<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        /* define a admin user role */
        Gate::define('isAdminandManager', function($user) {
           return $user->role_v2 == ('admin'||'manager_ma'||'manager_pd');
        });
        Gate::define('isAdminandMA', function($user) {
           return $user->role_v2 == ('admin'||'manager_ma');
        });
        Gate::define('isAdminandPD', function($user) {
           return $user->role_v2 == ('admin'||'manager_pd');
        });
        Gate::define('isAdmin', function($user) {
           return $user->role_v2 == 'admin';
        });
        Gate::define('isManager_Ma', function($user) {
            return $user->role_v2 == 'manager_ma';
        });
        Gate::define('isManager_Pd', function($user) {
            return $user->role_v2 == 'manager_pd';
        });
        Gate::define('isUser', function($user) {
            return $user->role_v2 == 'user';
        });
        //
    }
}
