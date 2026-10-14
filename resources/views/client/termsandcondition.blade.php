@extends('layouts.client.contact')
@section('title','Terms and condition | tolocal.shop ')
@section('content')
</a>
<div class="sub_header_in sticky_header">
	<div class="container">
	  <h1>Terms and condition</h1>
	</div>
</div>
	<div class="container margin_60_35">
		<div class="row justify-content-center">
			<div class="col-lg-12">
				
				@foreach($terms as $term)
				{!!($term->terms_and_condition)!!}
				@endforeach		
					
			</div>
		</div>
	</div>

@endsection