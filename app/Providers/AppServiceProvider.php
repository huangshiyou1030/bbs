<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Cache;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
	{
        /**
         * 增加内存防止中文分词报错
         */
        ini_set('memory_limit', "256M");
		\App\Models\User::observe(\App\Observers\UserObserver::class);
		\App\Models\Reply::observe(\App\Observers\ReplyObserver::class);
		\App\Models\Topic::observe(\App\Observers\TopicObserver::class);
        \App\Models\Link::observe(\App\Observers\LinkObserver::class);
        \App\Models\Tag::observe(\App\Observers\TagObserver::class);
        \App\Models\Category::observe(\App\Observers\CategoryObserver::class);
        //
        \Carbon\Carbon::setLocale('zh');


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
