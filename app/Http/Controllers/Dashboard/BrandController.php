<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index()
    {
        $category = Category::orderBy("id","DESC")->get();
        return view('backend.Brand.brand',compact('category'));

    }
    public function list()
    {
        $brand = Brand::orderBy("id","ASC")->with('category')->get();
        return response([
            'status' =>200,
            'brands' =>$brand
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:brands,name'
        ]);
        if($validator->passes())
        {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->category_id = $request->category;
            $brand->status = $request->status;
            $brand->save();

            return response([
                'status' =>200,
                'message' => 'Brand created Successfully'
            ]);
        }else{
            return response([
                'status' =>500,
                'error' =>$validator->errors()
            ]);
        }
    }
    public function edit(Request $request)
    {
        $brand = Brand::find($request->id);
        return response([
            'status' =>200,
            'brands' =>$brand
        ]);
    }
    public function destroy(Request $request)
    {
        $brand =Brand::find($request->id);
        if($brand == null)
        {
            return response([
                'status' =>404,
                'message' =>'Brand not found with id '+$request->id
            ]);
        }else{
            $brand->delete();
            return response([
                'status' =>200,
                'message' =>'Brand Delete Successfully'

            ]);
        }

    }
    public function update(Request $request)
    {
        $brand = Brand::find($request->id);
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:brands,name'
        ]);
        if($validator->passes())
        {
            $brand->name = $request->name;
            $brand->category_id = $request->category;
            $brand->status = $request->status;
            $brand->save();
            return response([
                'status' =>200,
                'message' => 'Brand Update Successfully'
            ]);

        }
        else{
            return response([
                'status' =>500,
                'error' =>$validator->errors()
            ]);
        }

    }
}
