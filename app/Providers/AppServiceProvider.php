<?php

namespace App\Providers;

use App\Models\Konsultasi;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
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
        Gate::before(function (User $user, $ability) {
        return $user->isAdmin() ? true : null; // admin lolos semua ability
        });

        View::composer('layouts.app', function ($view) {
            $notificationItems = collect();
            $unreadNotificationCount = 0;
            $konsultasiAttentionCount = 0;

            if (auth()->check()) {
                $user = auth()->user();

                if (Schema::hasTable('notifications')) {
                    $notificationItems = $user->notifications()->latest()->limit(5)->get();
                    $unreadNotificationCount = $user->unreadNotifications()->count();
                }

                if (
                    $user->isDokter() &&
                    Schema::hasTable('konsultasi') &&
                    Schema::hasTable('konsultasi_pesan')
                ) {
                    $konsultasiAttentionCount = Konsultasi::query()
                        ->visibleTo($user)
                        ->where(function ($query) use ($user) {
                            $query->where(function ($pending) use ($user) {
                                $pending->where('dokter_tujuan_id', $user->id)
                                    ->whereIn('status', [
                                        Konsultasi::STATUS_SUBMITTED,
                                        Konsultasi::STATUS_READ,
                                    ]);
                            })->orWhereHas('pesan', function ($messages) use ($user) {
                                $messages->whereNull('dibaca_at')
                                    ->where('pengirim_id', '!=', $user->id);
                            });
                        })
                        ->count();
                }
            }

            $view->with([
                'notificationItems' => $notificationItems,
                'unreadNotificationCount' => $unreadNotificationCount,
                'konsultasiAttentionCount' => $konsultasiAttentionCount,
            ]);
        });
    }
}
