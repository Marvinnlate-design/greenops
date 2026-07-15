<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Models\Announcement;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS en production
        if (env('APP_ENV') === 'production') {
        URL::forceScheme('https');
    };

        View::composer('*', function ($view) {

            $newAnnouncements = 0;

            if (Auth::check()) {

                $user = Auth::user();

                $query = Announcement::query();

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

            $view->with('newAnnouncements', $newAnnouncements);
        });
    }
}