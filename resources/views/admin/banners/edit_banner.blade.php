@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Banners</a> <a href="#" class="current">Edit Banner</a> </div>
    <h1>Banners</h1>
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
  </div>
  <div class="container-fluid"><hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>Edit Banner</h5>
          </div>
          <div class="widget-content nopadding"> {{-- The enctype attribute specifies how the form-data should be encoded when submitting it to the server.The enctype attribute can be used only if method="post".No characters are encoded. This value is required when you are using forms that have a file upload control --}}
            <form enctype="multipart/form-data" class="form-horizontal" method="post" action="{{ url('/admin/edit-banner/'.$bannerDetails->id)}}" name="edit_banner" id="edit_banner" novalidate="novalidate">{{ csrf_field()}}
            <div class="control-group">
                <label class="control-label">Banner Image</label>
                <div class="controls">
                  <input type="file" name="image" id="image">
                  @if(!empty($bannerDetails->image))
                    <img style="width:140px" src="{{ asset('/images/frontend_images/banners/'.$bannerDetails->image) }}"> | <a href="{{ url('/admin/delete-banner-image/'.$bannerDetails->id) }}" class="btn btn-danger btn-mini">Delete</a>
                  @endif
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">Title</label>
                <div class="controls">
                  <input type="text" name="title" id="title" value="{{$bannerDetails->title}}">
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">Link</label>
                <div class="controls">
                  <input type="text" name="link" id="link" value="{{$bannerDetails->link}}">
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">Enable</label>
                <div class="controls">
                  <input type="checkbox" name="status" id="status" value="1"  @if($bannerDetails->status=="1") checked 
                @endif value="1">
                </div>
              </div>
              <div class="form-actions">
                <input type="submit" value="Edit Banner" class="btn btn-success">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection