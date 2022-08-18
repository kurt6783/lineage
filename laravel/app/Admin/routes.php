<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group(
    [
        'prefix'     => config('admin.route.prefix'),
        'namespace'  => config('admin.route.namespace'),
        'middleware' => config('admin.route.middleware'),
    ],
    function (Router $router) {
        $router->get('/', 'HomeController@index');

        //寶物管理
        $router->resource('/treasure', 'TreasureController');

        //交易列表
        $router->resource('/transactionList', 'TransactionListController');
    }
);
