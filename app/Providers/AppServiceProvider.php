<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Cache;
use App\Models\Category;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
	{
		\App\Models\User::observe(\App\Observers\UserObserver::class);
		\App\Models\Reply::observe(\App\Observers\ReplyObserver::class);
		\App\Models\Topic::observe(\App\Observers\TopicObserver::class);
        \App\Models\Link::observe(\App\Observers\LinkObserver::class);

        //
        \Carbon\Carbon::setLocale('zh');
        //分配前台通用的数据
        view()->composer('layouts/*', function($view) {
            $categories = Cache::remember('common:category', 10080, function () {
                // 获取分类导航
                return Category::select('id', 'name')->orderBy('created_at')->get();
            });
            // 分配数据
            $assign = compact('categories');
            $view->with($assign);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        if(app()->isLocal()){
            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }

    }
}
