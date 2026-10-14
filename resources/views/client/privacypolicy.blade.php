@extends('layouts.client.contact')
@section('title','Privacy Policy | tolocal.shop ')
@section('content')
</a>
<div class="sub_header_in sticky_header">
	<div class="container">
	  <h1>Privacy Policy</h1>
	</div>
</div>
	<div class="container margin_60_35">
		<div class="row justify-content-center">
			<div class="col-lg-12">
				
				@foreach($privacy as $privacypolicy)
				{!!($privacypolicy->privacy_policy)!!}
				@endforeach			
					
			</div>
		</div>
	</div>

@endsection