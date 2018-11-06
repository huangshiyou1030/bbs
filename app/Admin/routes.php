<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->get('config', 'ConfigController@index')->name('admin.config.index');
    $router->post('config', 'ConfigController@update')->name('admin.config.update');
    $router->get('users', 'UserController@index');
    $router->get('topics', 'TopicController@index')->name('admin.topics.index');
    $router->get('topics/{topics}', 'TopicController@show')->name('admin.topics.show');
    $router->post('topics/{topics}/handleState', 'TopicController@handleState')->name('admin.topics.handleState');
    $router->get('categories', 'CategoryController@index');
    $router->get('categories/create', 'CategoryController@create');
    $router->get('categories/{id}/edit', 'CategoryController@edit');
    $router->post('categories', 'CategoryController@store');
    $router->put('categories/{id}', 'CategoryController@update');
    $router->delete('categories/{id}', 'CategoryController@destroy');
    $router->get('reply', 'ReplyController@index');

    $router->get('tags', 'TagController@index');
    $router->get('tags/create', 'TagController@create');
    $router->get('tags/{id}/edit', 'TagController@edit');
    $router->post('tags', 'TagController@store');
    $router->put('tags/{id}', 'TagController@update');
    $router->delete('tags/{id}', 'TagController@destroy');

});
