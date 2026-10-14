@extends('layouts.client.vendor')
@section('title','Permission| Edit')
@section('content')

<div class="sub_header_in sticky_header">

    <div class="container">
        <h1>Edit User Permision</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>

<main>
    <div class="container margin_60_35">

        <div class="page-wrapper">

            <div class="container-fluid">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4 class="page-title">User </h4>
                </div>


                <!-- Column -->
                @if(session()->get('msg'))
                <div class="alert alert-success">
                    {{ session()->get('msg') }}
                </div><br />
                @endif
                <div class="">
                    <div class="card-body">
                        <form action="{{ route('company.permission.update', $data->id) }}" method="POST" class="">
                            {{ csrf_field() }}
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Full Name</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="name" value="{{ $data->name }}" placeholder="Enter Full Name" class="form-control p-0 border-0">
                                    <span class="text-danger">@error('name'){{$message}} @enderror</span>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="example-email" class="col-md-12 p-0">Email</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="email" name="email" value="{{ $data->email }}" placeholder="Enter e-mail address" class="form-control p-0 border-0" name="example-email" id="example-email">
                                    <span class="text-danger">@error('email'){{$message}} @enderror</span>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Password</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="password" name="password" class="form-control p-0 border-0">
                                    <span class="text-danger">@error('password'){{$message}} @enderror</span>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Role</label>
                                <div class="col-md-12 border-bottom p-0">
                                    @php
                                    $selected = [];
                                    foreach ($data->roles as $rval){
                                    $selected[] = $rval->id;
                                    }


                                    $selected2 = [];
                                    foreach ($data->modules as $mval){
                                    $selected2[] = $mval->id;
                                    }

                                    $selected3 = [];
                                    foreach ($data->permissions as $pval){
                                    $selected3[] = $pval->id;
                                    }
                                    @endphp
                                    <select class="form-control p-0 border-0" name="role" id="role" required>
                                        @foreach($role as $val)
                                        <option value="{{$val->id}}" {{ (in_array($val->id, $selected)) ? 'selected' : '' }}>{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-4" style="display: none;">
                                <label class="col-md-12 p-0">Modules</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control p-0 border-0" style="width:100%" multiple name="modules[]" id="modules" required>
                                        @foreach($modules as $val)
                                        <option value="{{$val->id}}" {{ (in_array($val->id, $selected2)) ? 'selected' : '' }}>{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-4" style="display: none;">
                                <label class="col-md-12 p-0">Permission</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control p-0 border-0" style="width:100%" multiple name="permissions[]" id="permissions" required>
                                        @foreach($permissions as $val)
                                        <option value="{{$val->id}}" {{ (in_array($val->id, $selected3)) ? 'selected' : '' }}>{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" id="compBtn"  class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>


        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
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

    var baseUrl = "{{ url('/') }}";

    $(document).ready(function(){

        $('#role').change(function(){

            var id = $(this).val();
            $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
            });
            jQuery.ajax({
                url: baseUrl + '/company/getModulePermissionByRoleId/' + id,
                type: 'GET',
                beforeSend: function(){
				$('#compBtn').prop('disabled', true);
			   },
                success: function( data ){

                    $('#compBtn').prop('disabled', false);

                    var obj = $.parseJSON(data);
                    var param = [];
                    $.each(obj.modules, function(key, value) {
                        param.push(value.id);
                    });
                    //console.log(param.join(", "));
                    $('#modules').val(param).trigger('change');

                    var param2 = [];
                    $.each(obj.permissions, function(key, value) {
                       param2.push(value.id);
                    });
                     $('#permissions').val(param2).trigger('change');

                }
            });

        });
    });



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
