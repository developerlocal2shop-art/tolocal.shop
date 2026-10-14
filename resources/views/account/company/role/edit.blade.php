@extends('layouts.client.company')
@section('title','Role| Edit')
@section('content')
<div class="sub_header_in sticky_header filter_sub_blue_header">

    <div class="container">
        <h1>Role</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>

<main>

    <div class="container margin_60_35">

    <div class="container-fluid">

        <div class="">
            <div class="card">
                <div class="card-body">
                    <h4 class="page-title">Role</h4>
                    <form action="{{ route('company.role.update', $data->id) }}" method="POST" class="">
                        {{ csrf_field() }}

                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Role Name</label>
                            <div class="col-md-12 border-bottom p-0">
                                <input type="text" name="name" value="{{ $data->name }}" placeholder="Enter Full Name" class="form-control p-0 border-0">
                                <span class="text-danger">@error('name'){{$message}} @enderror</span>
                            </div>
                        </div>

                        @php
                        $selected2 = [];
                        foreach ($data->modules as $mval){
                        $selected2[] = $mval->id;
                        }

                        $selected3 = [];
                        foreach ($data->permissions as $pval){
                        $selected3[] = $pval->id;
                        }
                        @endphp

                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Modules</label>
                            <div class="col-md-12 border-bottom p-0">
                                <select class="form-control p-0 border-0" style="width:100%" multiple name="modules[]" id="modules" required>
                                    @foreach($modules as $val)
                                    <option value="{{$val->id}}" {{ (in_array($val->id, $selected2)) ? 'selected' : '' }}>{{$val->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Permission</label>
                            <div class="col-md-12 border-bottom p-0">
                                <select class="form-control p-0 border-0" style="width:100%" multiple name="permissions[]" id="permissions" required>
                                    @foreach($permissions as $val)
                                    <option value="{{$val->id}}" {{ (in_array($val->id, $selected3)) ? 'selected' : '' }}>{{$val->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-info">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Column -->
        </div>

    </div>


</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->
</div>
</main>

<style>
    .select2-results__option[aria-selected=true] {
        background-color: #ddd;
    }

    li.select2-search.select2-search--inline {
        display: inline;
    }

</style>

<script>
    $(document).ready(function() {

        $("#permissions,#modules").select2();
        //$("#permissions,#modules").select2({
        // width: 'element'
        // , minimumResultsForSearch: Infinity
        //});


        $("#password").on('keyup', function() {
            var number = /([0-9])/;
            var alphabets = /([a-zA-Z])/;
            var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
            if ($('#password').val().length < 6) {
                $('#password-strength-status').removeClass();
                $('#password-strength-status').addClass('weak-password');
                $('#password-strength-status').html("Weak Password");
            } else {
                if ($('#password').val().length > 6) {
                    $('#password-strength-status').removeClass();
                    $('#password-strength-status').addClass('strong-password');
                    $('#password-strength-status').html("Strong Password");
                } else {
                    $('#password-strength-status').removeClass();
                    $('#password-strength-status').addClass('medium-password');
                    $('#password-strength-status').html("Medium Password");
                }
            }
        });
    });

</script>
<script type="text/javascript">
$(document).ready(function () {
    // Warning
    $(window).on('beforeunload', function(){
        return "Any changes will be lost";
    });
    // Form Submit
    $(document).on("submit", "form", function(event){
        // disable warning
        $(window).off('beforeunload');
    });
});
</script>
@endsection
