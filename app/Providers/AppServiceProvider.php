<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
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
            $user = Auth::user();
            $unreadMessages = 0;

            if ($user) {
                $unreadMessages = Message::whereHas('conversation.users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->where('sender_id', '!=', $user->id)
                ->whereNull('read_at')
                ->count();
            }


            $view->with('unreadMessages', $unreadMessages);
        });
    }
}
