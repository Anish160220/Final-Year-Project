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
use App\ProductsImage;
use DB;
use Illuminate\Support\Str;
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

            if(empty($data['status'])){
                $status = 0;
            }else{
                $status = 1;
            }

            $product->status = $status;

            
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
               if(empty($data['status'])){
                $status = 0;
            }else{
                $status = 1;
            }

           Product::where(['id'=>$id])->update(['category_id'=>$data['category_id'],
           'product_name'=>$data['product_name'],'product_code'=>$data['product_code'],
           'product_color'=>$data['product_color'],'description'=>$data['description'],'care'=>$data['care'],
           'price'=>$data['price'],'image'=>$filename,'status'=>$status]);
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

    public function deleteAltImage($id=null){
        //Get Product Image Name
        $productImage = ProductsImage::where(['id'=>$id])->first();
        if(empty($productImage)){
            echo "Error"; die;
        }

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
        ProductsImage::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Product Alternate Image Deleted Successfully!');
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

    public function editAttributes(Request $request,$id=null){
        if($request->isMethod('post')){
            $data = $request->all();
            foreach($data['idAttr'] as $key => $attr){
                ProductsAttribute::where(['id'=>$data['idAttr'][$key]])->update(['price'=>$data['price'][$key],'stock'=>$data['stock'][$key]]);
            }
            return redirect()->back()->with('flash_message_success','Product Attribute Updated Successfully!');
    
        }
    }

    public function addImages(Request $request, $id=null){
        $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
        //$productDetails = json_decode(json_encode($productDetails));
         //echo "<pre>"; print_r($productDetails); die;
        
        if($request->isMethod('post')){
            $data =  $request->all();
            
            if($request->hasFile('image')){
                $files = $request->file('image'); 
                foreach($files as $file){
                    //Upload Images After Re-size
                    $image = new ProductsImage;
                    $extention = $file->getClientOriginalExtension(); 
                    $filename = rand(111,99999).'.'.$extention;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    //Resize Image
                    Image::make($file)->save($large_image_path);
                    Image::make($file)->resize(600,600)->save($medium_image_path);
                    Image::make($file)->resize(300,300)->save($small_image_path);
                    $image->image = $filename;
                    $image->product_id = $data['product_id'];
                    $image->save();
                }
               
            }
            return redirect('admin/add-images/'.$id)->with ('flash_message_success','Product Images Added Successfully');
            }
            $productsImages = ProductsImage::where(['product_id'=>$id])->get();
       
            return view('admin.products.add_images')->with(compact('productDetails','productsImages'));
    }

    public function deleteAttribute($id=null){
        ProductsAttribute::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success',' Attribute Deleted Successfully!');
     
    }

    //For admin
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
                $productsAll = Product::whereIn('category_id',$cat_ids)->where('status',1)->get();
                // $productsAll = json_decode(json_encode($productsAll));
                // echo "<pre>"; print_r($productsAll); die;


        }else{
            //If url is Sub-Category
            $productsAll = Product::where(['category_id' => $categoryDetails->id])->where('status',1)->get();
        }

        
        return view('/products.listing')->with(compact('categories','categoryDetails','productsAll'));
    }

    //For users
    public function product($id = null){
        //Show 404 page if product is disable
        $productsCount = Product::where(['id'=>$id,'status'=>1])->count();
        if($productsCount==0){
            // abort(404);
            return view('404');
        }

        //Get PRoduct Details
       $productDetails = Product::with('attributes')->where('id',$id)->first();

       //
       $relatedProducts = Product::where('id','!=',$id)->where(['category_id'=>$productDetails->category_id])->get();
       //$relatedProducts = json_decode(json_encode($relatedProducts));

    //    foreach($relatedProducts->chunk(3) as $chunk){
    //        foreach($chunk as $item){
    //            echo $item;echo "<br>";
    //        }
    //         echo "<br><br>";
    //    }
    //    echo "<pre>"; print_r($relatedProducts); die;
        //Get All Categories and Sub Categoris
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();

        //get Product Alt Image
        $productAltImages = ProductsImage::where('product_id',$id)->get();

        //To get the available Stock
         $total_stock = ProductsAttribute::where('product_id',$id)->sum('stock'); 

       return view('products.detail')->with(compact('productDetails','categories','productAltImages','total_stock','relatedProducts'));
    }

    public function getProductPrice(Request $request){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $proArr = explode("-",$data['idSize']);
        $proArr = ProductsAttribute::where(['product_id' => $proArr[0],'size' => $proArr[1]])->first();
        echo $proArr->price;
        echo "#";
        echo $proArr->stock;
    }

    public function addtocart(Request $request){
        $data =$request->all();

        if(empty($data['user_email'])){
            $data['user_email']='';
        }

        $session_id = Session::get('session_id');
        if(empty($session_id)){

            $session_id = Str::random(40); 
            Session::put('session_id',$session_id);
        }

        $sizeArr = explode("-",$data['size']);

        $countProducts =  DB::table('cart')->where(['product_id'=>$data['product_id'],'product_color'=>$data['product_color'],'size'=>$sizeArr[1],'session_id'=>$session_id])->count();
       
        if($countProducts > 0)
        {
            return redirect()->back()->with('flash_message_error','Product already exist in Cart!');
        }else{
            DB::table('cart')->insert(['product_id'=>$data['product_id'],'product_name'=>$data['product_name'],'product_code'=>$data['product_code'],'product_color'=>$data['product_color'],'price'=>$data['price'],'size'=>$sizeArr[1],'quantity'=>$data['quantity'],'user_email'=>$data['user_email'],'session_id'=>$session_id]);
            return redirect('cart')->with('flash_message_success','Product has beed added on cart!');
        }

        //echo "<pre>";print_r($data);die;
       
    }

    public function cart(){
        $session_id = Session::get('session_id');
        $userCart = DB::table('cart')->where(['session_id'=>$session_id])->get();
        foreach($userCart as $key => $product){
            $productDetails = Product::where('id',$product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
        }
        // echo "<pre>";print_r($userCart);die;
        return view('products.cart')->with(compact('userCart'));
    }

    public function deleteCartProduct($id=null){
       DB::table('cart')->where('id',$id)->delete();
       return redirect('cart')->with('flash_message_success','Product Deleted From Cart!');
    }

    public function updateCartQuantity($id=null,$quantity=null){
        DB::table('cart')->where('id',$id)->increment('quantity',$quantity);
        return redirect('cart')->with('flash_message_success','Product Quantity Updated Successfully!');
    }
}