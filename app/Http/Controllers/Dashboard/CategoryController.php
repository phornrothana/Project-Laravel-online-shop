<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Uid\NilUlid;

class CategoryController extends Controller
{
    public function index(){
        return view('backend.category.category');
    }
    public function upload(Request $request){
        $validate = Validator::make($request->all(),[
            'image' => 'required'
         ]);
         if($validate->passes()){
                $file = $request->file('image');
                $imageName = rand(0,99999999).'.'.$file->getClientOriginalExtension();
                 $file->move('uploads/temp',$imageName);
                 return response()->json([
                    'status' =>200,
                    'message' =>'image Uploads successfully',
                    'image' => $imageName
                 ]);
         }else{
            return response()->json([
                'status' =>500,
                'error' =>$validate->errors()
            ]);
         }

    }

    public function cancel(Request $request){
        if($request->image)
        {
            $tempDir = public_path("uploads/temp/$request->image");
            if(File::exists($tempDir)){
                File::delete($tempDir);
                return response()->json([
                'status' =>200,
                'message' =>'Image Cancel Successfully'
               ]);
            }
        }
    }
    public  function store(Request $request){
        $validate = Validator::make($request->all(),[
            'name' =>'required'
        ]);
        if($validate->passes()){
            $category = new Category();
            $category->name = $request->name;
            $category->status = $request->status;

            $tempDir = public_path("uploads/temp/".$request->input('category-image'));
            $cateDir = public_path("uploads/category/".$request->input('category-image'));

            if(File::exists($tempDir)){
                File::copy($tempDir,$cateDir);
                File::delete($tempDir);
            }
            $category->image = 'uploads/category/' . $request->input('category-image');
            $category->save();
            return response([
                'status' => 200,
                'message' => "Category created successful"
            ]);
        }else{
            return response()->json([
               'status' => 500,
                'error' => $validate->errors(),
            ]);
        }

    }
    public function list(){
        $category = Category::orderBy('id','ASC')->get();
        return response([
            'status' =>200,
            'categories' =>$category
        ]);
    }
    public function destroy(Request $request){
        $category = Category::find($request->id);

        if($category == null){
            return response([
                'status' => 404,
                'message' =>'User not found with' + $request->id
            ]);
        }
        $imagePath = public_path($category->image);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $category ->delete();
        return response([
            'status' =>200,
            'message' => "user delete successfully"
        ]);
    }
    public function edit(Request $request) {
        $category = Category::find($request->id);
        if ($category) {
            return response()->json(['status' => 200, 'categories' => $category]);
        } else {
            return response()->json(['status' => 404, 'message' => 'Category not found']);
        }
    }

    // public function update(Request $request) {
    //     $category = Category::find($request->id);
    //     if ($category == null) {
    //         return response([
    //             'status' => 404,
    //             'message' => 'Category not found with id ' . $request->id
    //         ]);
    //     }
    //     $category->name = $request->name;
    //     $category->status = $request->status;
    //     // Handle image upload logic
    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');
    //         $imageName = time() . '.' . $image->getClientOriginalExtension();
    //         $image->move(public_path('uploads/category'), $imageName);
    //         $category->image = $imageName;

    //         // Optionally delete old image if needed
    //         if ($category->getOriginal('image')) {
    //             $oldImagePath = public_path('uploads/category/' . $category->getOriginal('image'));
    //             if (File::exists($oldImagePath)) {
    //                 File::delete($oldImagePath);
    //             }
    //         }
    //     } elseif ($request->input('old_image')) {
    //         // Keep the old image if no new image is uploaded
    //         $category->image = $request->input('old_image');
    //     }

    //     try {
    //         $category->save();
    //         return response([
    //             'status' => 200,
    //             'message' => 'Update Category Successfully'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response([
    //             'status' => 500,
    //             'message' => 'Database error: ' . $e->getMessage()
    //         ]);
    //     }
    // }

    public function update(Request $request){
        $category = Category::find($request->id);
        if($category == null)
        {
        return response([
        'status' => 404,
        'message' =>'Category not found with id ' + $request->id
        ]);
        }
        $validate = Validator::make($request->all(),[
        'name' => 'required'
        ]);
        if($validate->passes())
        {
        $category->name = $request->name;
        $category->status = $request->status;
        if($request->input('category-image')){
            $tempDir = public_path("uploads/temp/".$request->input('category-image'));
            $cateDir = public_path("uploads/category/".$request->input('category-image'));

            if(File::exists($tempDir)){
                File::copy($tempDir,$cateDir);
                File::delete($tempDir);
            }


            $cateDir = public_path('uploads/category'.$request->image);
            if(File::exists($cateDir)){
                File::delete($cateDir);
            }
            // $image = $request->input('category-image');
            $image = 'uploads/category/' . $request->input('category-image');
        }elseif($request->file('old_image')){
            $image = 'uploads/category/' . $request->input('old_image');;
        }
        $category->image =$image;
        $category->save();

        return response([
            'status'=>200,
            'message' => 'Update Category Successfully'
        ]);
     }
     else {
        return response([
            'status' =>500,
            'message' => 'error category update'
        ]);
     }
   }
}
