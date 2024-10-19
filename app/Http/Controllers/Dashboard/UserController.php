<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        // $user= User::orderBy('id','ASC')-> get();
        return view('backend.index');
    }
    public function create(){
        return view('backend.user.create');
    }
    public function list(){
        $user= User::orderBy('id','ASC')-> get();
        return response([
            'status' =>200,
            'users' =>$user
        ]);
    }
    public function store(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required',
     ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'errors' => $validator->errors()
        ], 400);
    }

     try {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();
        return response()->json([
            'status' => 200,
            'message' => 'User created successfully'
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'An error occurred while creating the user.',
            'error' => $e->getMessage() // Optionally include error message
        ], 500);
     }
    }

    public function destroy(Request $request){
        $user =User::find($request->id);

        if($user == null){
            return response([
                'status' => 404,
                'message' =>'User not found with' + $request->id
            ]);
        }
        $user ->delete();
        return response([
            'status' =>200,
            'message' => "user delete successfully"
        ]);
    }
}
