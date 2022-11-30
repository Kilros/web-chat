<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    public function readFile($filename){
        try {
            $extension = explode(".",$filename)[1];
            $allowedfileExtension=['jpg','jpeg','png','gif']; 
            if(in_array(strtolower($extension),$allowedfileExtension)){
                $path = resource_path() . '/appchat/image/' . $filename;
            }   
            else if($extension == "mp3"){
                $path = resource_path() . '/appchat/audio/' . $filename;
            }  
            else if($extension == "mp4"){
                $path = resource_path() . '/appchat/video/' . $filename;
            } 
            if(!File::exists($path)) {
                return response()->json(['message' => 'Image not found.'], 404);
            }
            $file = File::get($path);
            $type = File::mimeType($path);
        
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Image not found.'], 404);
        }
    }
}
