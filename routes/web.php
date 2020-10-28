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
Route::get('a7189273981723987', function (){
    $client = new \GuzzleHttp\Client();
    $item = "http://49.233.206.202:8048/BC140/ODataV4/Company('Voltage')/VoltageItem";
    $bom = "http://49.233.206.202:8048/BC140/ODataV4/Company('Voltage')/VoltageBOM";
    $project = "http://49.233.206.202:8048/BC140/ODataV4/Company('Voltage')/VoltageProject";

    $response1 = $client->request('GET', $item, [
        'auth' => [
            'Administrator',
            'Welcome123456',
            'ntlm'
        ]
    ]);

    $response2 = $client->request('GET', $bom, [
        'auth' => [
            'Administrator',
            'Welcome123456',
            'ntlm'
        ]
    ]);

    $response3 = $client->request('GET', $project, [
        'auth' => [
            'Administrator',
            'Welcome123456',
            'ntlm'
        ]
    ]);
    if($response1->getStatusCode() == 200){
        $res1 = json_decode($response1->getBody()->getContents(), true);
        $res2 = json_decode($response2->getBody()->getContents(), true);
        $res3 = json_decode($response3->getBody()->getContents(), true);
        $res1['value'][0]['bom'] = [10002,10003,10004];
        $res1['value'][1] = [
            "No" => "10002",
            "No_2" => "",
            "Description" => "test2",
            "Description_2" => "",
            "Base_Unit_of_Measure" => "PCS",
            "Item_Category_Code" => "CABLE",
            "Unit_Price" => 0,
            "bom" => []
        ];
        dd($res1,$res2,$res3);
    }
});
