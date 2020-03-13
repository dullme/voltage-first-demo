<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');

    //客户
    $router->resource('clients', ClientController::class);

    //项目
    $router->resource('projects', ProjectController::class);
    $router->get('po-client/edit/{id}', 'PoClientController@getPoClient');
    $router->post('po-client/edit/{id}', 'PoClientController@save');
    $router->post('po-client/add', 'PoClientController@add');
    $router->post('po-client/delete/{id}', 'PoClientController@delete');
    $router->post('po-factory/add', 'PoFactoryController@add');

    $router->post('po-factory', 'PoFactoryController@add');
    $router->get('po-factory/edit/{id}', 'PoFactoryController@getPoFactory');
    $router->post('po-factory/edit/{id}', 'PoFactoryController@save');
    $router->post('po-factory-batch', 'PoFactoryController@addBatch');
    $router->post('delete/batch/{id}', 'PoFactoryController@deleteBatch');
    $router->post('restore/batch/{id}', 'PoFactoryController@restoreBatch');
    $router->post('deleted/batch/{id}', 'PoFactoryController@deletedBatch');
    $router->post('delete/factory/{id}', 'PoFactoryController@deleteFactory');
    $router->get('batch/{id}', 'PoFactoryController@getBatch');
    $router->post('po-factory-batch/edit/{id}', 'PoFactoryController@editBatch');
});
