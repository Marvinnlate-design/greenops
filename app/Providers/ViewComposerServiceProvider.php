<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot(): void
{
    View::composer('layouts.navigation', function ($view) {

        $newAnnouncements = 0;

        if (Auth::check()) {

            $user = Auth::user();

            $query = Announcement::query();

            // Si ce n'est ni un admin ni un chef,
            // on ne garde que les annonces de son service
            // ou celles destinées à tous.
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

    public function register(): void
    {
        //
    }
}