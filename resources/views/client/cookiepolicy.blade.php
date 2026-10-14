@extends('layouts.client.contact')
@section('title','Cookie Policy | tolocal.shop ')
@section('content')
</a>
<div class="sub_header_in sticky_header">
	<div class="container">
	  <h1>Cookie Policy</h1>
	</div>
</div>
	<div class="container margin_60_35">
		<div class="row justify-content-center">
			<div class="col-lg-12">
				
				@foreach($cookiedata as $cookiepol)
				{!!($cookiepol->cookie_policy)!!}
				@endforeach	
					
			</div>
		</div>
	</div>
@endsection