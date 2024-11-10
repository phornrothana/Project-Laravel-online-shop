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
    public function list(Request $request)
    {
        //pagination form
        $limit = 5;
        $page  = $request->page;  //2

        $offset = ($page - 1) * $limit;

        if(!empty($request->search)){
            $brands = Brand::where('name','like','%'.$request->search.'%')
                            ->orderBy("id","DESC")->with('category')
                            ->limit($limit)
                            ->offset($offset)
                            ->get();
            $totalRecord = Brand::where('name','like','%'.$request->search.'%')->count();
        }else{
            $brands = Brand::orderBy("id","DESC")->with('category')
                            ->limit($limit)
                            ->offset($offset)
                            ->get();
            $totalRecord = Brand::count();
        }



        //totalRecord


        $totalPage   = ceil($totalRecord / 5);  // 2.1 => 3

        return response([
            'status' => 200,
            'page' => [
                'totalRecord' => $totalRecord,
                'totalPage'  => $totalPage,
                'currentPage' => $page,
            ],
            'brands' => $brands
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
