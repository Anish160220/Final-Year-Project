<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::match(['get','post'],'/admin','AdminController@login');

Route::get('/logout','AdminController@logout');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Index Page
Route::get('/','IndexController@index');

//Listing/Category  PAge
Route::get('/products/{url}','ProductsController@products');

//Product detail page
Route::get('/product/{id}','ProductsController@product');

//Get Product Attribute Price
Route::get('/get-product-price','ProductsController@getProductPrice');

//Addto cart
Route::match(['get','post'],'/add-cart','ProductsController@addtocart');

//Cart Page
Route::match(['get','post'],'/cart','ProductsController@cart');

//Delete Product From Cart PAge
Route::get('/cart/delete-product/{id}','ProductsController@deleteCartProduct');

//Update Product Quantity in Cart   
Route::get('/cart/update-quantity/{id}/{quantity}','ProductsController@updateCartQuantity');

//Apply Coupon
Route::post('/cart/apply-coupon','ProductsController@applyCoupon');

Route::group(['middleware' => ['auth']],function(){
    Route::get('/admin/dashboard','AdminController@dashboard');
    Route::get('/admin/settings','AdminController@settings');
    Route::get('/admin/check-pwd','AdminController@chkPassword');
    Route::match(['get','post'],'/admin/update-pwd','AdminController@updatePassword');

    //Categories Routes (Admin)
    Route::match(['get','post'],'/admin/add-category','CategoryController@addCategory');
    Route::match(['get','post'],'/admin/edit-category/{id}','CategoryController@editCategory'); //Category Id is passed
    Route::match(['get','post'],'/admin/delete-category/{id}','CategoryController@deleteCategory'); //Category Id is passed
    Route::get('/admin/view-categories','CategoryController@viewCategories');

    //Product
    Route::match(['get','post'],'/admin/add-product','ProductsController@addProduct');
    Route::match(['get','post'],'/admin/edit-product/{id}','ProductsController@editProduct');
    Route::get('/admin/view-products','ProductsController@viewProducts');
    Route::get('/admin/delete-product/{id}','ProductsController@deleteProduct');
    Route::get('/admin/delete-product-image/{id}','ProductsController@deleteProductImage');
    Route::get('/admin/delete-alt-image/{id}','ProductsController@deleteALtImage');

    //Producs Attribute
    Route::match(['get','post'],'/admin/add-attributes/{id}','ProductsController@addAttributes');
    Route::match(['get','post'],'/admin/edit-attributes/{id}','ProductsController@editAttributes');
    Route::match(['get','post'],'/admin/add-images/{id}','ProductsController@addImages');
    Route::get('/admin/delete-attribute/{id}','ProductsController@deleteAttribute');
   
    //Coupon Route
    Route::match(['get','post'],'/admin/add-coupon','CouponsController@addCoupon');
    Route::match(['get','post'],'/admin/edit-coupon/{id}','CouponsController@editCoupon');
    Route::get('/admin/view-coupons','CouponsController@viewCoupons');
    Route::get('/admin/delete-coupon/{id}','CouponsController@deleteCoupon');
   
});

