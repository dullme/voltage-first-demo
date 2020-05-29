<?php


namespace App\Http\Controllers;


class FileController
{
    function browse($file_name){
        return response()->file(storage_path().'/app/export/'.$file_name);
    }
}
