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

    public function editBanner(Request $request,$id=null){
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;

            if(empty($data['status'])){
                $status = 0;
            }else{
                $status = 1;
            }

            if(empty($data['title']))
            {
                $data['title'] = '';
            }

            if(empty($data['link']))
            {
                $data['link'] = '';
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
            }
        }else if(!empty($data['current_image'])){
            $filename = $data['current_image'];
        }
         else{
             $filename = '';
         }
            Banner::where('id',$id)->update(['status'=>$status,'title'=>$data['title'],'link'=>$data['link'],'image'=>$filename]);
            return redirect()->back()->with('flash_message_success','Banner Updated Successfully!');
          
        }
        $bannerDetails = Banner::where('id',$id)->first();
        return view('admin.banners.edit_banner')->with(compact('bannerDetails'));
    }

    public function deleteBannerImage($id=null){
        //Get Product Image Name
        $bannerImage = Banner::where(['id'=>$id])->first();

        //Get Product Image path
        $banner_path = 'images/frontend_images/banners/';

        //Delete Large Image if not exists in folder
        if(file_exists($banner_path.$bannerImage->image));
        unlink($banner_path.$bannerImage->image);


        //Delete Image From Products Table
        Banner::where(['id'=>$id])->update(['image'=>'']);
        return redirect()->back()->with('flash_message_success','Banner Image Deleted Successfully!');
    }

    public function viewBanners(){
        $banners = Banner::get();
        return view('admin.banners.view_banners')->with(compact('banners'));
    }

    public function deleteBanner($id=null){
        Banner::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Banner Deleted Successfully!');
    }
   
}
