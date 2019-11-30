<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use App\Banner;

class BannersController extends Controller
{
    public function addBanner(Request $request){
        if($request->isMethod('post')){
        $data = $request->all();
        //echo "<pre>"; print_r($data); die;
        $banner = new Banner;
        $banner->title = $data['title'];
        $banner->link = $data['link'];
        
        if(empty($data['status'])){
            $status = 0;
        }else{
            $status = 1;
        }
        // Upload Image
        if($request->hasFile('image')){
            $image_tmp = $request->file('image'); 
            if($image_tmp->isValid()){   
                $extention = $image_tmp->getClientOriginalExtension(); //returns the original file extension
                $filename = rand(111,99999).'.'.$extention; //rand function generate random number min 111 and max 99999
                $banner_path = 'images/frontend_images/banners/'.$filename;
               
                //Resize Image
                Image::make($image_tmp)->resize(1140,340)->save($banner_path);
                //Store Image name in banner table
                $banner->image = $filename;
            }
        }
        $banner->status = $status;
        $banner->save();
        return redirect()->back()->with('flash_message_success','Banner Added Successfully!');
    }
        return view('admin.banners.add_banner'); 

    }
}
