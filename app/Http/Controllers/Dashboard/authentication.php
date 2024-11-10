<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class authentication extends Controller
{
    public function login()
    {
        if(Auth::check())
        {
            return redirect()->route('user.index');
        }else{
            return view('backend.Login.login');
        }
;
    }
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        ]);
        if($validator->passes())
        {
            $credentials = $request->only('email','password');
            if(Auth::attempt($credentials))
            {
                return redirect()->route('category.index')->with('succuss','login Successfully');
            }else{
                return redirect()->back()->with('error','Invalid Password or Email');
            }

        }else{
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.index');
    }
}
