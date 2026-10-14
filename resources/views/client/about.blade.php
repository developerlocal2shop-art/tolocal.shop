@extends('layouts.client.contact')
@section('title','About | tolocal.shop ')
@section('content')
</a>
<div class="sub_header_in sticky_header">
	<div class="container">
	  <h1>About</h1>
	</div>
</div>
	<div class="container margin_60_35">
		<div class="row justify-content-center">
			<div class="col-lg-12">
				
				@foreach($about as $aboutus)
				{!!($aboutus->about_us)!!}
				@endforeach	
					
			</div>
		</div>
	</div>
@endsection