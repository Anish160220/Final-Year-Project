<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;

class IndexController extends Controller
{
    public function index(){
        // //In Ascending Order By Default
        // $productsAll = Product::get();
        // // In Descending Order
        // $productsAll = Product::orderby('id','DESC')->get();

        //In Random
        $productsAll = Product::inRandomOrder('id','DESC')->get();

        //Get All Categories and Sub Categoris
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();
        // $categories = json_decode(json_encode($categories));
        // echo "<pre>"; print_r($categories); die;

        return view('index')->with(compact('productsAll','categories'));
    }
}
