<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        //分配前台通用的数据
        view()->composer('layouts/*', function($view) {
            $cateModel = new  \App\Models\Category();
            $categories = $cateModel->getAllCached();
            $tagModel = new  \App\Models\Tag();
            $tags = $tagModel->getAllCached();
            // 分配数据
            $assign = compact('categories','tags');
            $view->with($assign);
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
