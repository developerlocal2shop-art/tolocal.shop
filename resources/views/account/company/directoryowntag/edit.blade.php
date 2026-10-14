@extends('layouts.client.company')
@section('title','Directory own tag Edit')
@section('content')

<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Member Account</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>

<main>
    <div class="container margin_60_35">
        <div class="row">
            <div class="col-md-12">
                @if(Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                    @php
                    Session::forget('error');
                    @endphp
                </div>
                @endif
                @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                    @php
                    Session::forget('success');
                    @endphp
                </div>
                @endif
            </div>
        </div>
        <!--Main Area-->
        <form id="submitFrm" action="{{ route('company.directoryowntags.update', $data['tagseditdata']->id) }}" method="POST" class="">
            {{ csrf_field() }}
            <input type="hidden" name="director_id" value="{{auth()->user()->directory_id}}">
            <input type="hidden" name="createdby" value="{{auth()->user()->id}}">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-4">
                        <label class="col-md-12 p-0">Directory Own Tags Category</label>
                        <div class="">

                            <select class="form-control" name="directorycategory">
                                <option>None</option>
                                @foreach($categorydata as $categorysrow)
                                <option value="{{ $categorysrow->id }}" {{ $categorysrow->id == $data['tagseditdata']->category_id ? 'selected' : '' }}> {{ $categorysrow->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-4">
                        <label class="col-md-12 p-0">Directory Own Tags</label>
                        <div class="col-md-12 border-bottom p-0">
                            <input type="text" name="tags_name" data-role="tagsinput" id="tags_name" value="{{ $data['tagseditdata']->tag_name }}" placeholder="Enter Tags" required="" class="form-control p-0 border-0 required">
                        </div>
                        <span class="text-danger">@error('tags_name'){{$message}} @enderror</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" id="add-tags-to-page" class="form-check-input" name="add_tags_to_page" value="yes" <?php if($data['tagseditdata']->add_to_directory_page=="yes"){ echo 'checked'; } ?>>
                                <label class="custom-control-label">Add to Directory Page</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="createdby" class="form-control" value="{{auth()->user()->id }}">
            <div class="form-group mb-4">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-info submitbtn">Update</button>
                </div>
            </div>
        </form>
    </div>
    </div>
    <!-- /container -->
</main>
<script src="{{ asset('assets/plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        //$('.ckeditor').ckeditor();

        $(document).on('click', '.submitbtn', function(e) {
            e.preventDefault();

            $(document).find('.required').removeClass('is-invalid');
            $(document).find('.invalid-feedback').remove();

            var obj = $(this);
            let requestTxt = $(this).attr('process-text');
            requestTxt = (typeof(requestTxt) != "undefined") ? requestTxt : 'Please wait';
            var oldhtml = $(this).html();

            var form = $(this).closest("form");
            var url = form.attr('action');
            var method = form.attr('method');
            var loader = `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
            <span class="">${requestTxt}...</span>`;

            var allformdata = new FormData(form[0]);

            $.ajax({
                type: method
                , url: url
                , headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                , data: allformdata
                , mimeType: "multipart/form-data"
                , contentType: false
                , cache: false
                , processData: false
                , dataType: "json"
                , beforeSend: function() {
                    obj.attr("disabled", true);
                    obj.html(loader);
                },

                success: function(response) {

                    console.log(response);

                    if (response.status == false) {

                        $.each(response.errors, function(index, value) {
                            form.find('.required').each(function() {
                                var fieldName = $(this).attr('name');

                                if (fieldName == index && value != "") {
                                    $(this).addClass("is-invalid");
                                    $(this).after(`<span class="red invalid-feedback" role="alert">${value}</span>`);
                                    $(this).focus();
                                }
                            });
                        });

                    } else {
                        if (typeof(response.message) != "undefined") {
                            console.log(response.message);

                            toastr.success(response.message);
                            window.setTimeout(function() {
                                window.location.href = "{{ url('company/directory-own-tags') }}";
                            }, 2000);
                        }
                    }
                    obj.attr("disabled", false);
                    obj.html(oldhtml);
                }
                , error: function(err) {}
            });

        });
    });

    $(document).ready(function() {
        // Warning
        $(window).on('beforeunload', function() {
            return "Any changes will be lost";
        });
        // Form Submit
        $(document).on("submit", "form", function(event) {
            $(window).off('beforeunload');
        });
    });

</script>
<style>
    [type="radio"]:checked,
    [type="radio"]:not(:checked) {
        position: unset;
    }
    .custom-control-label {
        position: unset;
    }
    .red {
        background: none;
    }
</style>
@endsection
