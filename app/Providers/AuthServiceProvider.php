<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Account;
use App\Models\CreditCard;
use App\Models\Goal;
use App\Policies\UserOwnedObjectPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Account::class => UserOwnedObjectPolicy::class,
        CreditCard::class => UserOwnedObjectPolicy::class,
        Goal::class => UserOwnedObjectPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
