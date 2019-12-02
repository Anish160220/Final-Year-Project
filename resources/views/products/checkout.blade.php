@extends('layouts.frontLayout.front_design')
@section('content')

<section id="form" style="margin-top:10px;"><!--form-->
		<div class="container">
        <form action="#">
			<div class="row">
				<div class="col-sm-4 col-sm-offset-1">
					<div class="login-form"><!--login form-->
                        <h2>Bill To</h2>
                        <div class="form-group">
                            <input type="text" placeholder="Billing Name" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input type="address" placeholder="Billing Address" class="form-control" />
                            </div>
                        <div class="form-group">
                            <input type="city" placeholder="Billing City" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <input type="state" placeholder="Billing State" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <input type="country" placeholder="Billing Country" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <input type="pincode" placeholder="Billing Pincode" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <input type="mobile" placeholder="Billing Mobile" class="form-control"/>
                            </div>
                            <div class="form-check">
    <input type="checkbox" class="form-check-input" id="billtoship">
    <label class="form-check-label" for="billtoship">Shipping Address Same As Billing Address</label>
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
                        <input type="text" placeholder="Shipping Name" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <input type="address" placeholder="Shipping Address" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <input type="city" placeholder="Shipping City" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <input type="state" placeholder="Shipping State" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <input type="country" placeholder="Shipping Country" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <input type="pincode" placeholder="Shipping Pincode" class="form-control"/>
                            </div>
                        <div class="form-group">
                            <input type="mobile" placeholder="Shipping Mobile" class="form-control"/>
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