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

//Admin Login and Logout
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

//USer Login Register Page
Route::get('/login-register','UsersController@userLoginRegister');

//USer Register Form submit
Route::post('/user-register','UsersController@register');

//USer Login Form Submit
Route::post('/user-login','UsersController@login');

//USers Logout
Route::get('/user-logout','UsersController@logout');

Route::group(['middleware' => ['frontlogin']],function(){
    //User Account Page
    Route::match(['get','post'],'/account','UsersController@account');
    //Check User Current Password
    Route::post('/check-user-pwd','UsersController@chkUserPassword');
    //Update user password
    Route::post('/update-user-pwd','UsersController@updatePassword');
    //Check Out Page
    Route::match(['get','post'],'/checkout','ProductsController@checkout');
    //Order Review PAge
    Route::match(['get','post'],'/order-review','ProductsController@orderReview');
    //Place Order
    Route::match(['get','post'],'/place-order','ProductsController@placeOrder');
    //Thanks Page
    Route::get('/thanks','ProductsController@thanks');
    //USers Order Page
    Route::get('/orders','ProductsController@userOrders');
});

//Check if user already exist
Route::match(['get','post'],'/check-email','UsersController@checkEmail');

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

    //Admin Product
    Route::match(['get','post'],'/admin/add-product','ProductsController@addProduct');
    Route::match(['get','post'],'/admin/edit-product/{id}','ProductsController@editProduct');
    Route::get('/admin/view-products','ProductsController@viewProducts');
    Route::get('/admin/delete-product/{id}','ProductsController@deleteProduct');
    Route::get('/admin/delete-product-image/{id}','ProductsController@deleteProductImage');
    Route::get('/admin/delete-alt-image/{id}','ProductsController@deleteALtImage');

    //Admin Producs Attribute
    Route::match(['get','post'],'/admin/add-attributes/{id}','ProductsController@addAttributes');
    Route::match(['get','post'],'/admin/edit-attributes/{id}','ProductsController@editAttributes');
    Route::match(['get','post'],'/admin/add-images/{id}','ProductsController@addImages');
    Route::get('/admin/delete-attribute/{id}','ProductsController@deleteAttribute');
   
    //Admin Coupon Route
    Route::match(['get','post'],'/admin/add-coupon','CouponsController@addCoupon');
    Route::match(['get','post'],'/admin/edit-coupon/{id}','CouponsController@editCoupon');
    Route::get('/admin/view-coupons','CouponsController@viewCoupons');
    Route::get('/admin/delete-coupon/{id}','CouponsController@deleteCoupon');

    //Admin Banners Route
    Route::match(['get','post'],'/admin/add-banner','BannersController@addBanner');
    Route::match(['get','post'],'/admin/edit-banner/{id}','BannersController@editBanner');
    Route::get('/admin/view-banners','BannersController@viewBanners');
    Route::get('/admin/delete-banner/{id}','BannersController@deleteBanner');
    Route::get('/admin/delete-banner-image/{id}','BannersController@deleteBannerImage');
});

