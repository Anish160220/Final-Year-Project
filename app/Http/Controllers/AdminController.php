<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\User;
use Illuminate\Support\Facades\Hash;
class AdminController extends Controller
{
    public function login(Request $request){
        if($request->isMethod('post')){
            $data=$request->input();
            if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password'],'admin'=>'1'])){
               return redirect('/admin/dashboard');
            }
            else{
               return redirect('/admin')->with('flash_message_error','Invalid Username or PAssword');
            }
        }
        return view('admin.admin_login');
    }

    public function dashboard(){
        return view('admin.dashboard');
    }

    public function settings(){
        return view('admin.settings');
    }

    public function chkPassword(Request $request){
        $data = $request->all();
        $current_password = $data['current_pwd'];
        $check_password = User::where(['admin'=>'1'])->first(); //User Model
        if(Hash::check($current_password,$check_password->password)){  //To check HAsh Password
            echo "true"; die;
        }else{
            echo "false"; die;
        }
    }

    public function updatePassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $check_password = User::where(['email'=>Auth::user()->email])->first(); //User Model
            $current_password = $data['current_pwd'];
            if(Hash::check($current_password,$check_password->password)){  //To check HAsh Password
                $password = bcrypt($data['new_pwd']);
                User::where('id','1')->update(['password'=>$password]);
                return redirect('/admin/settings')->with('flash_message_success','Password Update Successfully');
            }else{
                return redirect('/admin/settings')->with('flash_message_error','Incorrect Current Password');
            }
        }
    }

    public function logout(){
       Session::flush(); /** Clear all the session */
       return redirect('/admin')->with('flash_message_success','Logged Out Successfully');
    }
}
