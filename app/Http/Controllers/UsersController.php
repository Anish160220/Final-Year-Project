<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Session;
use App\Country;

class UsersController extends Controller
{
    public function userLoginRegister(){
        return view('users.login_register');
    }

    public function login(Request $request){
        if($request->isMethod('post')){
            $data= $request->all();
            //echo "<pre>"; print_r($data); die;
            if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){
                Session::put('frontSession',$data['email']);
                return redirect('/cart')->with('flash_message_success','Logged In Successfully');
            }else{
                return redirect()->back()->with('flash_message_error','Invalid Username Or Password');
            }

        }
    }

    public function register(Request $request){
        if($request->isMethod('post')){
            $data=$request->all();
           //echo "<pre>"; print_r($data);die;
            // check if user already exist
            $usersCount = User::where('email',$data['email'])->count();
            if($usersCount>0){
                return redirect()->back()->with('flash_message_error','Email already exists!');
            }else{
                $user = new User;
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                $user->save();
                if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){
                    Session::put('frontSession',$data['email']);
                    return redirect('/cart')->with('flash_message_success','Registered Successfully');
                }
            }
        }
       
    }

    public function account(Request $request){
        $user_id = Auth::user()->id;
        $userDetails = User::find($user_id);
        $countries = Country::get();
       //echo "<pre>"; print_r($userDetails);die;
       if($request->isMethod('post')){
           $data=$request->all();

           if(empty($data['name'])){
            return redirect()->back()->with('flash_message_error','Please enter your name');
           }

           if(empty($data['address'])){
            $data['address']='';
           }

           if(empty($data['city'])){
            $data['city']='';
           }

           if(empty($data['state'])){
            $data['state']='';
           }

           if(empty($data['country'])){
            $data['country']='';
           }

           if(empty($data['pincode'])){
            $data['pincode']='';
           }

           if(empty($data['mobile'])){
            $data['mobile']='';
           }
           //echo "<pre>"; print_r($data);die;
           $user = User::find($user_id);
           $user->name = $data['name'];
           $user->address = $data['address'];
           $user->city = $data['city'];
           $user->state = $data['state'];
           $user->country = $data['country'];
           $user->pincode = $data['pincode'];
           $user->mobile = $data['mobile'];
           $user->save();

           return redirect()->back()->with('flash_message_success','Your Account Detail Updated Successfully!');
       }
        return view('users.account')->with(compact('countries','userDetails'));
    }

    public function logout(){
        Auth::logout();
         Session::forget('frontSession'); //Auth has done this work
        return redirect('/')->with('flash_message_success','Successfully LogOut!');
    }
    public function checkEmail(Request $request){
        $data=$request->all();
        $usersCount = User::where('email',$data['email'])->count();
        if($usersCount>0){
           echo "false";
        }else{
            echo "true";die;
        }
    }
}
