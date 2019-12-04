@extends('layouts.frontLayout.front_design')
@section('content')

<section id="form" style="margin-top:10px;"><!--form-->
		<div class="container">
        <form action="{{ url('/checkout') }}" method="post"> {{ csrf_field() }}
			<div class="row">
            @if(Session::has('flash_message_error'))  
        <div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{!! session('flash_message_error') !!}</strong>
</div>   
        @endif   

        @if(Session::has('flash_message_success'))  
        <div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{!! session('flash_message_success') !!}</strong>
</div>   
        @endif  
				<div class="col-sm-4 col-sm-offset-1">
					<div class="login-form"><!--login form-->
                        <h2>Bill To</h2>
                        <div class="form-group">
                            <input value="{{$userDetails->name}}" name="billing_name" id="billing_name" type="text" placeholder="Billing Name" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input value="{{$userDetails->address}}" name="billing_address" id="billing_address" type="text" placeholder="Billing Address" class="form-control" />
                            </div>
                        <div class="form-group">
                            <input value="{{$userDetails->city}}" name="billing_city" id="billing_city" type="text" placeholder="Billing City" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <input value="{{$userDetails->state}}" name="billing_state" id="billing_state" type="text" placeholder="Billing State" class="form-control"/>
                            </div>
                        <div class="form-group">
                        <select value="{{$userDetails->country}}" id="billing_country" name="billing_country" class="form-control">
								<option value = "">Select Country </option>
								@foreach($countries as $country)
										<option value = "{{$country->country_name}}" @if($country->country_name == $userDetails->country) selected @endif>{{$country->country_name}}</option>
								@endforeach
							</select> </div>
                        <div class="form-group">
                            <input value="{{$userDetails->pincode}}" name="billing_pincode" id="billing_pincode" type="text" placeholder="Billing Pincode" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <input value="{{$userDetails->mobile}}" name="billing_mobile" id="billing_mobile" type="text" placeholder="Billing Mobile" class="form-control"/>
                            </div>
                            <div class="form-check">
    <input type="checkbox" class="form-check-input" id="copyAddress">
    <label class="form-check-label" for="copyAddress">Shipping Address Same As Billing Address</label>
</div>
                       
					</div><!--/login form-->
				</div>
				<div class="col-sm-1">
					<h2></h2>
				</div>
				<div class="col-sm-4">
					<div class="signup-form"><!--sign up form-->
                        <h2>Ship To</h2>
                        <div class="form-group">
                        <input value="{{$shippingDetails->mobile}}"  name="shipping_name" id="shipping_name" type="text" placeholder="Shipping Name" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <input value="{{$shippingDetails->address}}"  type="text" name="shipping_address" id="shipping_address" placeholder="Shipping Address" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <input value="{{$shippingDetails->city}}"  type="text" name="shipping_city" id="shipping_city"  placeholder="Shipping City" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <input value="{{$shippingDetails->state}}"  type="text" name="shipping_state" id="shipping_state" placeholder="Shipping State" class="form-control"/>
                            </div>
                        <div class="form-group">
                        <select value="{{$shippingDetails->country}}" id="shipping_country" name="shipping_country" class="form-control">
								<option value = "">Select Country </option>
								@foreach($countries as $country)
										<option value = "{{$country->country_name}}" @if($country->country_name == $shippingDetails->country) selected @endif>{{$country->country_name}}</option>
								@endforeach
							</select>  </div>
                        <div class="form-group">
                            <input value="{{$shippingDetails->pincode}}"  type="text" name="shipping_pincode" id="shipping_pincode" placeholder="Shipping Pincode" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <input value="{{$shippingDetails->mobile}}"  type="text" name="shipping_mobile" id="shipping_mobile" placeholder="Shipping Mobile" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">CheckOut</button>	
                            </div>
					</div><!--/sign up form-->
				</div>
            </div>
</form>
		</div>
	</section><!--/form-->
	

@endsection