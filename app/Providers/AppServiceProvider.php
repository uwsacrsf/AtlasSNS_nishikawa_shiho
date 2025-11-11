<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 'layouts.login' ビューに常にデータを共有する例
        // あなたのレイアウトファイル名に合わせて変更してください
        // 例: 'layouts.app' もしくは 'layouts.main' など
        View::composer('layouts.login', function ($view) {
            $loggedInUser = Auth::user();

            if ($loggedInUser) {
                $followingsCount = $loggedInUser->followings()->count();
                $followersCount = $loggedInUser->followers()->count();
                $view->with('loggedInUser', $loggedInUser)
                     ->with('followingsCount', $followingsCount)
                     ->with('followersCount', $followersCount);
            }
        });

        // もしサイドバーが特定のビュー（例: side_bar.blade.php）としてインクルードされているなら、
        // View::composer('partials.sidebar', ...); のように指定することもできます。
        // この場合は、サイドバーが含まれている親のレイアウトに渡すのが最も簡単です。
    }
}
