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
    $router->get('client-list', 'ClientController@getClientList');
    $router->get('contact-list/{id}', 'ClientController@getContactList');

    //联系人
    $router->resource('contacts', ContactController::class);
    $router->get('/api/contact', 'ContactController@contact');


    //工厂
    $router->resource('factories', FactoryController::class);
    $router->resource('factory-contacts', FactoryContactController::class);
    $router->get('factory-list', 'FactoryController@getFactories');

    //港口
    $router->resource('ports', PortController::class);
    $router->get('port-list', 'PortController@getPortList');

    $router->resource('carriers', CarrierController::class);
    $router->get('carrier-list', 'CarrierController@getCarriers');

    $router->resource('forwarders', ForwarderController::class);
    $router->resource('forwarder-contacts', ForwarderContactController::class);
    $router->get('forwarder-contact-list', 'ForwarderContactController@getForwarderContacts');

    //项目
    $router->resource('projects', ProjectController::class);

    $router->resource('po-clients', PoClientController::class);

    $router->get('po-client/edit/{id}', 'PoClientController@getPoClient');
    $router->post('po-client/edit/{id}', 'PoClientController@save');
    $router->post('po-client/add', 'PoClientController@add');
    $router->post('po-client/delete/{id}', 'PoClientController@delete');
    $router->post('po-factory/add/{id}', 'PoFactoryController@add');

    $router->post('po-factory', 'PoFactoryController@add');

    $router->post('po-factory-factory/add', 'PoFactoryFactoryController@add');
    $router->post('delete/factory-factory/{id}', 'PoFactoryFactoryController@delete');
    $router->get('po-factory-factory/edit/{id}', 'PoFactoryFactoryController@showFactoryFactory');
    $router->post('po-factory-factory/edit/{id}', 'PoFactoryFactoryController@editFactoryFactory');

    $router->get('po-factory/edit/{id}', 'PoFactoryController@getPoFactory');
    $router->post('po-factory/edit/{id}', 'PoFactoryController@save');
    $router->post('po-factory-batch', 'PoFactoryController@addBatch');
    $router->post('delete/batch/{id}', 'PoFactoryController@deleteBatch');
    $router->post('restore/batch/{id}', 'PoFactoryController@restoreBatch');
    $router->post('deleted/batch/{id}', 'PoFactoryController@deletedBatch');
    $router->post('delete/factory/{id}', 'PoFactoryController@deleteFactory');
    $router->get('batch/{id}', 'PoFactoryController@getBatch');
    $router->get('batch/show/{id}', 'PoFactoryController@showBatch');
    $router->post('container/add', 'PoFactoryController@addContainer');
    $router->post('delete/container/{id}', 'PoFactoryController@deleteContainer');
    $router->post('container/edit/{id}', 'PoFactoryController@editContainer');
    $router->get('container/info/{id}', 'PoFactoryController@containerInfo');
    $router->post('po-factory-batch/edit/{id}', 'PoFactoryController@editBatch');

    $router->resource('batches', BatchController::class);
});
