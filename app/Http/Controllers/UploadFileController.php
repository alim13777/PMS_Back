<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
class UploadFileController extends Controller
{
    public function uploadFile($data){
        $file=$data->file;
        $destinationPath = 'uploads';
        $file->move($destinationPath,$file->getClientOriginalName());
    }
    public function getFile($data){
        $basePath=base_path();
        $fileName=$data->name;
        $file = File::get($basePath.'/public/uploads/'.$fileName);
        $type = File::mimeType($basePath.'/public/uploads/'.$fileName);
        $response = response()->make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
    public function deleteFile($data){
        $basePath=base_path();
        $fileName=$data->name;
        File::delete($basePath.'/public/uploads/'.$fileName);
    }
}
