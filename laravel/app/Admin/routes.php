<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');

    Route::group(['prefix' => 'players'], function (Router $router) {
        $router->get('/', 'PlayerController@index');
        $router->get('/create', 'PlayerController@create');
        $router->post('/', 'PlayerController@store');
        $router->get('/{id}', 'PlayerController@show');
        $router->get('/{id}/edit', 'PlayerController@edit');
        $router->put('/{id}', 'PlayerController@update');
        $router->delete('/{id}', 'PlayerController@destroy');
    });

    $router->get('/treasure', 'TreasureController@index');
    $router->get('/treasure/create', 'TreasureController@create');
    $router->post('/treasure', 'TreasureController@store');
    $router->get('/treasure/{id}/edit', 'TreasureController@edit');
    $router->put('/treasure/{id}', 'TreasureController@update');
    $router->delete('/treasure/{id}', 'TreasureController@destroy');
});
