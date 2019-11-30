<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;

class CouponsController extends Controller
{
    public function addCoupon(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $coupon = new Coupon;
            $coupon->coupon_code = $data['coupon_code'];
            $coupon->amount = $data['amount'];
            $coupon->amount_type = $data['amount_type'];
            $coupon->expiry_date = $data['expiry_date'];
            if(empty($data['status'])){
                $status = 0;
            }else{
                $status = 1;
            }
            $coupon->status = $status;
            $coupon->save();

            return redirect()->action('CouponsController@viewCoupons')->with('flash_message_success','Coupon Added Successfully!');
        }
        return view('admin.coupons.add_coupon');
    }

    public function editCoupon(Request $request,$id=null){
        if($request->isMethod('post')){
            $data = $request->all();
            $coupon = Coupon::find($id);
            $coupon->coupon_code = $data['coupon_code'];
            $coupon->amount = $data['amount'];
            $coupon->amount_type = $data['amount_type'];
            $coupon->expiry_date = $data['expiry_date'];
            if(empty($data['status'])){
                $status = 0;
            }else{
                $status = 1;
            }
            $coupon->status = $status;
            $coupon->save();
            
            return redirect()->action('CouponsController@viewCoupons')->with('flash_message_success','Coupon Updated Successfully!');
       
        }
        $couponDetails = Coupon::find($id);
        return view('admin.coupons.edit-coupon')->with(compact('couponDetails'));
    }

    public function viewCoupons(){
        $coupons = Coupon::get();
        // $coupons = json_decode(json_encode($coupons));
        // foreach($coupons as $key => $val){
        //     $category_name = Category::where(['id'=>$val->category_id])->first();
        //     $coupons[$key]->category_name = $category_name->name;
        // }
        return view('admin.coupons.view_coupons')->with(compact('coupons'));
    }
}
