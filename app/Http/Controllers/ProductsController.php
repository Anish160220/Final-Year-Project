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

            if(!empty($data['care'])){
                $product->care = $data['care'];
            }else{
                $product->care = '';
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
                     } 
                    }else if(!empty($data['current_image'])){
                        $filename = $data['current_image'];
                    }
                     else{
                         $filename = '';
                     }
            
            if(empty($data['description'])){
                $data['description'] = '';
            }
            if(empty($data['care'])){
                $data['care'] = '';
            }

           Product::where(['id'=>$id])->update(['category_id'=>$data['category_id'],
           'product_name'=>$data['product_name'],'product_code'=>$data['product_code'],
           'product_color'=>$data['product_color'],'description'=>$data['description'],'care'=>$data['care'],
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
        $products = Product::orderby('id','DESC')->get();
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
        //Get Product Image Name
        $productImage = Product::where(['id'=>$id])->first();

        //Get Product Image path
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';

        //Delete Large Image if not exists in folder
        if(file_exists($large_image_path.$productImage->image));
        unlink($large_image_path.$productImage->image);

        //Delete Medium Image if not exists in folder
        if(file_exists($medium_image_path.$productImage->image));
        unlink($medium_image_path.$productImage->image);

        //Delete small Image if not exists in folder
        if(file_exists($small_image_path.$productImage->image));
        unlink($small_image_path.$productImage->image);


        //Delete Image From Products Table
        Product::where(['id'=>$id])->update(['image'=>'']);
        return redirect()->back()->with('flash_message_success','Product Image Deleted Successfully!');
    }

    public function addAttributes(Request $request, $id=null){
        $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
        //$productDetails = json_decode(json_encode($productDetails));
         //echo "<pre>"; print_r($productDetails); die;
        
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            foreach($data['sku'] as $key => $val){
                if(!empty($val)){
                    //Prevent Duplicate SKU Check
                    $attrCountSKU = ProductsAttribute::where('sku',$val)->count();
                    if($attrCountSKU>0){
                        return redirect()->back()->with('flash_message_error','SKU Already Exists! Please Add Another SKU.');
                    }

                    //Prevent Duplicate Size Check
                    $attrCountSizes = ProductsAttribute::where(['product_id'=>$id,'size'=>$data['size'][$key]])->count();
                    if($attrCountSizes>0){
                        return redirect()->back()->with('flash_message_error','"'.$data['size'][$key].'" Size Already Exists! Please Add Another Size.');
                    }
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

    public function deleteAttribute($id=null){
        ProductsAttribute::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success',' Attribute Deleted Successfully!');
     
    }

    public function products($url = null){
        //Show 404 page if category url doesnot exist
        $countCatogory = Category::where(['url'=>$url,'status'=>1])->count();
        if($countCatogory==0){
            // abort(404);
            return view('404');
        }

        //Get All Categories and Sub Categoris
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();

        $categoryDetails = Category::where(['url'=> $url])->first();

        if($categoryDetails->parent_id==0){
                //If url is Main-Category
                $subCategories = Category::where(['parent_id'=>$categoryDetails->id])->get();
                foreach($subCategories as $subcat){
                    $cat_ids[] = $subcat->id;
                }
                $productsAll = Product::whereIn('category_id',$cat_ids)->get();
                // $productsAll = json_decode(json_encode($productsAll));
                // echo "<pre>"; print_r($productsAll); die;
        }else{
            //If url is Sub-Category
            $productsAll = Product::where(['category_id' => $categoryDetails->id])->get();
        }

        
        return view('/products.listing')->with(compact('categories','categoryDetails','productsAll'));
    }

    public function product($id = null){
        //Get PRoduct Details
       $productDetails = Product::with('attributes')->where('id',$id)->first();

        //Get All Categories and Sub Categoris
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();

       return view('products.detail')->with(compact('productDetails','categories'));
    }

    public function getProductPrice(Request $request){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $proArr = explode("-",$data['idSize']);
        $proArr = ProductsAttribute::where(['product_id' => $proArr[0],'size' => $proArr[1]])->first();
        echo $proArr->price;
    }
}