@extends('layouts.client.blog')
@section('title','Company Dashboard')
@section('content')

<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Company Dashboard</h1>
    </div>
    <!-- /container -->
</div>
<!-- /sub_header -->
<!-- Main page -->
<main>
    <div class="container margin_60_35">
        <ul class="list-group">
            <div class="row">
                <div class="col-lg-6">
                    <li class="list-group-item"><a href="{{ route('account') }}">User Dashboard</a></li>
                </div>
                <div class="col-lg-6">
                    <li class="list-group-item"><a href="{{ route('companyprofile') }}">Company Dashboard</a></li>
                </div>
            </div>
        </ul>
    </div>
</main>

<script>
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select Option"
            , allowClear: true
        });
    });

</script>

@endsection
