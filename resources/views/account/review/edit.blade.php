@extends('layouts.client.company')
@section('title','Company Edit Review')
@section('content')
@php $user = auth()->user(); @endphp
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>View Reviews</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>

<main>
    <div class="container margin_60_35">
        
            <div class="card">

                <div class="card-body">
                    <h3 class="box-title">Customer Reviews</h3>
                    <form action="{{ route('admin.review.updateReview', $reviewData->id) }}" method="POST"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group mb-4">
                            
                            <div class="col-md-12 border-bottom p-0">
                                <label class="col-md-12 p-0">Page</label>  
                                <input type="text" name="page" value="{{ $reviewData->page }}" readonly placeholder="Enter Page"
                                    class="form-control p-0 border-0" autocomplete=off>

                            </div>
                            <span class="text-danger">@error('page'){{$message}} @enderror</span>
                        </div>
                        <div class="form-group mb-4">
                            <label for="example-email" class="col-md-12 p-0">Content</label>
                            <div class="col-md-12 border-bottom p-0">
                                <textarea rows="5" name="content" readonly  class="form-control p-0 border-0">{{ $reviewData->content }}</textarea>

                            </div>
                            <span class="text-danger">@error('content'){{$message}} @enderror</span>

                            @if(count($reviewData->photos) > 0)
                            <label for="example-email" class="col-md-12 p-0">Photos</label>
                            @foreach ($reviewData->photos as $photosval)
                            <a data-lightbox="allGalleryImages">
                                <img data-id="{{ $photosval->id }}"
                                    src="{{ env('IMAGE_PATH') }}files/reviews_photo/{{ $photosval->photos }}"
                                    class="previewBlocks gtype_img"
                                    style="width: 90px; height: 102px; margin: 5px 5px;">
                            </a>
                            @endforeach
                            @endif
                        </div>

                    </form>
                </div>
            </div>


</div>

</div>
</main>
@endsection