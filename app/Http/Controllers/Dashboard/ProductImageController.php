<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    public function uploads(Request $request)
    {
        if($request->hasFile('image'))
       {
        $files = $request->file('image');
        $images = [];
        foreach($files as $file)
        {
            $fileName = rand(0,9999999) .'.'.$file->getClientOriginalExtension();
            $images [] = $fileName;
            $file->move(public_path("uploads/temp"),$fileName);
        }
        return response([
            'status' => 200,
            'message' => 'Image uploaded successfully',
            'image' => $images,
        ]);

       }
    }
    public function cancel(Request $request)
    {
        $temp =public_path("uploads/temp/".$request->image);
        if(File::exists($temp)){
            File::delete($temp);
            return response([
                'status' =>200,
                'message' => 'Image Canceled Successfully'
            ]);
        }
    }
}
