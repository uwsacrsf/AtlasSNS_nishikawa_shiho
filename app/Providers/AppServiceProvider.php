<?php /*共通のデータを自動的に渡す*/

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $loggedInUser = Auth::user();

            if ($loggedInUser) {
                $followingsCount = $loggedInUser->followings()->count();
                $followersCount = $loggedInUser->followers()->count();
                $view->with('loggedInUser', $loggedInUser)
                     ->with('followingCount', $followingsCount)
                     ->with('followerCount', $followersCount);
            }
        });

    }
}
