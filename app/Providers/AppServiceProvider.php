<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Announcement;

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
    View::composer('*', function ($view) {

        $newAnnouncements = 0;

        if (Auth::check()) {

            $user = Auth::user();

            $query = Announcement::query();

            // Les employés voient uniquement leur service + les annonces générales
            if (!in_array($user->role, ['admin', 'chef'])) {

                $query->where(function ($q) use ($user) {
                    $q->whereNull('service_id')
                      ->orWhere('service_id', $user->service_id);
                });
            }

            $newAnnouncements = $query
                ->whereDoesntHave('reads', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->count();
        }

$view->with('newAnnouncements', $newAnnouncements);    });
}
}