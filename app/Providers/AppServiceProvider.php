<?php

namespace App\Providers;

use App\Notifications\KonsultasiActivityNotification;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::before(function (User $user, $ability) {
            return $user->isAdmin() ? true : null;
        });

        View::composer('layouts.app', function ($view) {
            $notifications = collect();
            $unreadCount = 0;

            if (auth()->check()) {
                $user = auth()->user();
                $notifications = $user->notifications()
                    ->where('type', KonsultasiActivityNotification::class)
                    ->latest()
                    ->limit(8)
                    ->get();

                $unreadCount = $user->unreadNotifications()
                    ->where('type', KonsultasiActivityNotification::class)
                    ->count();
            }

            $view->with('konsultasiNotifications', $notifications)
                ->with('konsultasiNotificationCount', $unreadCount);
        });
    }
}
