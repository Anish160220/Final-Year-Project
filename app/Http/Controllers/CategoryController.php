<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function addCategory(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
           $category= new Category;
           $category->name = $data['category_name'];
           $category->description = $data['description'];
           $category->url = $data['url'];
           $category->save();
           return redirect('/admin/view-categories')->with('flash_message_success','Category added Successfully');
        }
        return view('admin.categories.add_category');
    }

    public function viewCategories(){
        $categories = Category::get(); //user Modle to get all record
        return view('admin.categories.view_categories')->with(compact('categories'));
    }
}
