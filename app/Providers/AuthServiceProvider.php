<?php

namespace App\Providers;

use App\Models\Board;
use App\Policies\BoardPolicy;
use App\Models\BoardList;
use App\Policies\ListPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Board::class => BoardPolicy::class,
        // BoardList::class => ListPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
