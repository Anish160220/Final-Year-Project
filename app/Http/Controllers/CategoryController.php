<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function addCategory(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();

            if(empty($data['status'])){
                $status= 0;
            }else{
                $status = 1;
            }
           $category= new Category;
           $category->name = $data['category_name'];
           $category->parent_id = $data['parent_id'];
           $category->description = $data['description'];
           $category->status = $status;
           $category->url = $data['url'];
           $category->save();
           return redirect('/admin/view-categories')->with('flash_message_success','Category added Successfully');
        }

        $levels = Category::where(['parent_id'=>0])->get();

        return view('admin.categories.add_category')->with(compact('levels'));
    }

    public function editCategory(Request $request,$id = null){
        if($request->isMethod('post')){
            $data = $request->all();
            if(empty($data['status'])){
                $status= 0;
            }else{
                $status = 1;
            }
            //echo"<prev>"; print_r($data); die;
            Category::where(['id'=>$id])->update(['name'=>$data['category_name'],'description'=>$data['description'],'url'=>$data['url'],'status'=>$status]);
            return redirect('/admin/view-categories')->with('flash_message_success','Category updated Successfully');
        }
        $categoryDetails = Category::where(['id'=>$id])->first();

        $levels = Category::where(['parent_id'=>0])->get();

        return view('admin.categories.edit_category')->with(compact('categoryDetails','levels'));
    }

    public function deleteCategory(Request $request,$id = null){
       if(!empty($id)){
           Category::where(['id'=>$id])->delete();
           return redirect()->back()->with('flash_message_success','Category deleted Successfully');
       }
    }

    public function viewCategories(){
        $categories = Category::get(); //user Modle to get all record
        $categories = json_decode(json_encode($categories)); //takes a JSON encoded string and converts it into a PHP variable.
        return view('admin.categories.view_categories')->with(compact('categories'));
    }
}
