<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/update-batches', function () {
    $batches = \App\Batch::with('poFactory.poClient.project')->withTrashed()->get();
    $batches->map(function ($batch){
        if($batch->project_id == 0){
            $batch->project_id = $batch->poFactory->poClient->project->id;
            $batch->save();
        }
    });

    return 'ok';

});

Route::get('/', function () {
    return redirect('/admin');
//    return view('welcome');
});

Route::get("storage/{file_name}","FileController@browse");
