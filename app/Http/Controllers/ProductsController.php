<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use Auth;
use Session;
use App\Category;
use App\Product;
use App\ProductsAttribute;
class ProductsController extends Controller
{
    public function addProduct(Request $request){

        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            if(empty($data['category_id'])){
                return redirect()->back()->with('flash_message_error','Under Category is missing!');

            }
            $product = new Product;
            $product->category_id = $data['category_id'];
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];
            if(!empty($data['description'])){
                $product->description = $data['description'];
            }else{
                $product->description = '';
            }
            $product->price = $data['price'];

            // Upload Image
            if($request->hasFile('image')){
                $image_tmp = $request->file('image'); 
                if($image_tmp->isValid()){   
                    $extention = $image_tmp->getClientOriginalExtension(); //returns the original file extension
                    $filename = rand(111,99999).'.'.$extention; //rand function generate random number min 111 and max 99999
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    //Resize Image
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                    //Store Image name in product table
                    $product->image = $filename;
                }
            }
            
            $product->save();
            return redirect('/admin/view-products')->with('flash_message_success','Product has been added Successfully!');

        }

        //Categories Drop down start
        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option selected disabled>Select</option>";
        foreach($categories as $cat){
            $categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
            $sub_categories =Category::where(['parent_id'=>$cat->id])->get();
            foreach($sub_categories as $sub_cat){
                $categories_dropdown .= "<option value = '".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
            }
        }
         //Categories Drop down end
        return view('admin.products.add_product')->with(compact('categories_dropdown'));

    }

    public function editProduct(Request $request, $id=null){
        if($request->isMethod("post")){
            $data = $request->all();
           // echo "<pre>"; print_r($data); die;

             // Upload Image
             if($request->hasFile('image')){
                $image_tmp = $request->file('image'); 
                if($image_tmp->isValid()){   
                    $extention = $image_tmp->getClientOriginalExtension(); //returns the original file extension
                    $filename = rand(111,99999).'.'.$extention; //rand function generate random number min 111 and max 99999
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    //Resize Image
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                     } else{
                         $filename = $data['current'];
                     }
            }

            if(empty($data['description'])){
                $data['description'] = '';
            }

           Product::where(['id'=>$id])->update(['category_id'=>$data['category_id'],
           'product_name'=>$data['product_name'],'product_code'=>$data['product_code'],
           'product_color'=>$data['product_color'],'description'=>$data['description'],
           'price'=>$data['price'],'image'=>$filename]);
           return redirect()->back()->with('flash_message_success','Product Updated Successfully!');
        }
        //Get PRoducts Detail
        $productDetails = Product::where(['id'=>$id])->first();
         //Categories Drop down start
         $categories = Category::where(['parent_id'=>0])->get();
         $categories_dropdown = "<option selected disabled>Select</option>";
         foreach($categories as $cat){
             if($cat->id==$productDetails->category_id){
                 $selected = "selected";
             }else{
                 $selected = "";
             }
             $categories_dropdown .= "<option value='".$cat->id."' ".$selected.">".$cat->name."</option>";
             $sub_categories =Category::where(['parent_id'=>$cat->id])->get();
             foreach($sub_categories as $sub_cat){
                if($sub_cat->id==$productDetails->category_id){
                    $selected = "selected";
                }else{
                    $selected = "";
                }
                 $categories_dropdown .= "<option value = '".$sub_cat->id."'".$selected.">&nbsp;--&nbsp;".$sub_cat->name."</option>";
             }
         }
          //Categories Drop down end
        return view('admin.products.edit_product')->with(compact('productDetails','categories_dropdown'));
    }

    public function viewProducts(){
        $products = Product::get();
        $products = json_decode(json_encode($products));
        foreach($products as $key => $val){
            $category_name = Category::where(['id'=>$val->category_id])->first();
            $products[$key]->category_name = $category_name->name;
        }
        return view('admin.products.view_products')->with(compact('products'));
    }

    public function deleteProduct($id=null){
        Product::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Product Deleted Successfully!');
    }

    public function deleteProductImage($id=null){
        Product::where(['id'=>$id])->update(['image'=>'']);
        return redirect()->back()->with('flash_message_success','Product Image Deleted Successfully!');
    }

    public function addAttributes(Request $request, $id=null){
        $productDetails = Product::where(['id'=>$id])->first();
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            foreach($data['sku'] as $key => $val){
                if(!empty($val)){
                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $val;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->save();
                    
                }
            }
            return redirect()->back()->with('flash_message_success','Product Attribute Added Successfully!');
        }
        return view('admin.products.add_attributes')->with(compact('productDetails'));
    }
}
