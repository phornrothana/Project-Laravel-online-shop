<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        return view('backend.Product.product');
    }
    public function data()
    {
        $category =  Category::orderBy("id","DESC")->get();
        $brand = Brand::orderBy("id","DESC")->get();
        $color = Color::orderBy("id","DESC")->get();


        return response([
            "status" => 200,
            "data" =>[
                "categories" =>$category,
                "brands" => $brand,
                "colors" => $color
            ]
        ]);
    }
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'title' => 'required',
        'price' => 'required|numeric',
        'qty' => 'required|integer',
        'color' => 'array' // Ensure color is an array
    ]);

    if ($validator->passes()) {
        // Save Product to table in db
        $product = new Product();
        $product->name = $request->title;
        $product->decs = $request->desc;
        $product->price = $request->price;
        $product->qty = $request->qty;
        $product->category_id = $request->category;
        $product->brand_id = $request->brand;
        $product->color = implode(",", $request->color);



        $product->user_id = Auth::user()->id;
        $product->status = $request->status;
        $product->save();

        // Handle images if present
        if ($request->images != null) {
            $images = $request->images;
            foreach ($images as $img) {
                $image = new ProductImage();
                $image->image = $img;
                $image->product_id = $product->id;

                if (File::exists(public_path("uploads/temp/$img"))) {
                    File::copy(public_path("uploads/temp/$img"), public_path("uploads/product/$img"));
                    File::delete(public_path("uploads/temp/$img"));
                }
                $image->save();
            }
        }

        return response([
            "status" => 200,
            "message" => "Product saved successfully"
        ]);
    } else {
        return response([
            'status' => 500,
            'message' => 'Validation Failed',
            'error' => $validator->errors() // Return validation errors
        ]);
    }
}

    public function list()
    {
        $product =Product::orderBy("id","DESC")->with(["Image","Categories","Brands"])->get();
        return response([
            "status" =>200,
            "products" =>$product
        ]);
    }
    public function edit(Request $request)
    {
        $product = Product::find($request->id);
        $productImage = ProductImage::where("product_id",$request->id)->get();
        $brand =Brand::orderBy("id","DESC")->get();
        $categories = Category::orderBy("id","DESC")->get();
        $color =Color::orderBy("id","DESC")->get();

        return response([
            'status' =>200,
            'data' => [
                'product' =>$product,
                'productImage' => $productImage,
                'brands' =>$brand,
                'categories' => $categories,
                'colors' =>$color
            ]
        ]);
    }
}


