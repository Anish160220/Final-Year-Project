@extends('layouts.frontLayout.front_design')
@section('content')

<div class="container text-center">
		<div class="content-404">
			<img src="{{ asset('images/frontend_images/404/404.png') }}" style="width:300px;" class="img-responsive" alt="" />
			<h1><b>OPPS!</b> We Couldn’t Find this Page</h1>
			<p>Uh... So it looks like you brock something. The page you are looking for has up and Vanished.</p>
            <h2><a href="{{ asset('./') }}">Bring me back Home</a></h2>
            <br>
		</div>
	</div>

@endsection