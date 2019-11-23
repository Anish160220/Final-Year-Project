<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class IndexController extends Controller
{
    public function index(){
        // //In Ascending Order By Default
        // $productsAll = Product::get();
        // // In Descending Order
        // $productsAll = Product::orderby('id','DESC')->get();

        //In Random
        $productsAll = Product::inRandomOrder('id','DESC')->get();
        return view('index')->with(compact('productsAll'));
    }
}
