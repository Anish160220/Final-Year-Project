<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function register(Request $request){
        if($request->isMethod('post')){
            $data=$request->all();
            echo "<pre>"; print_r($data);die;

        }
        return view('users.login_register');
    }
}
