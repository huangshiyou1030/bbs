<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Cache;
use App\Models\Category;
use App\Models\Link;
use App\Models\Tag;
use App\Models\Config;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Category $category, Link $link,Tag $tag)
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
        Config::loadConfig();
        //分配前台通用的数据
        view()->composer('layouts/*', function($view)use($category,$link,$tag){
            $links = $link->getAllCached();
            $tags = $tag->getAllCached();
            $categories = $category->getAllCached();
            // 分配数据
            $assign = compact('links', 'tags', 'categories');
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
