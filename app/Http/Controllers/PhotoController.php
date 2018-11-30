<?php
namespace App\Http\Controllers;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
Use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;


class PhotoController extends Controller
{
    public function image($fileName){

        $path = public_path().'/storage/'.$fileName;
        $file = File::get($path);
        $url = Storage::url($file);
//        return Image::make($file)->response();
        return $url;
    }


    public function allimage()
    {
        $allimage = Storage::disk('public')->files();
        $myarr = [];
        foreach ($allimage as $image){
            array_push($myarr,Storage::url($image));
        }
       return $myarr;
    }
}
