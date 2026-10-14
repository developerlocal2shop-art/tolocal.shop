@extends('layouts.client.company')
@section('title','User | Add User Permision')
@section('content')
<div class="sub_header_in sticky_header">

    <div class="container">
        <h1>Create User Permision</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<style>
    #password-strength-status {
        padding: 5px 10px;
        color: #FFFFFF;
        border-radius: 4px;
        margin-top: 5px;
    }

    .medium-password {
        background-color: #b7d60a;
        border: #BBB418 1px solid;
    }

    .weak-password {
        background-color: #ce1d14;
        border: #AA4502 1px solid;
    }

    .strong-password {
        background-color: #12CC1A;
        border: #0FA015 1px solid;
    }

</style>


<main>
    <div class="container margin_60_35">

        <div class="page-wrapper">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="page-title">Add User</h4>
            </div>

            <div class="container-fluid">

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('company.permission.submit') }}" method="POST" class="">
                            {{ csrf_field() }}
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Full Name<span class="notranslate" style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter Full Name" class="form-control p-0 border-0" autocomplete=off>

                                </div>
                                <span class="text-danger">@error('name'){{$message}} @enderror</span>
                            </div>
                            <div class="form-group mb-4">
                                <label for="example-email" class="col-md-12 p-0">Email<span class="notranslate" style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter e-mail address" class="form-control p-0 border-0" autocomplete=off id="example-email">

                                </div>
                                <span class="text-danger">@error('email'){{$message}} @enderror</span>
                            </div>
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Password<span class="notranslate" style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="password" name="password" id="password" value="" class="form-control p-0 border-0 password">

                                </div>
                                <span class="text-danger">@error('password'){{$message}} @enderror</span>
                                <div class="col-md-6" id="password-strength-status"></div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Role</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control p-0 border-0" name="role" id="role" required>
                                        <option value=''>Select role</option>
                                        @foreach($role as $val)
                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-4" style="display: none;">
                                <label class="col-md-12 p-0">Modules</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control p-0 border-0" style="width:100%" required multiple name="modules[]" id="modules">

                                        @foreach($modules as $val)
                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-4" style="display: none;">
                                <label class="col-md-12 p-0">Permission</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control p-0 border-0" style="width:100%" required multiple name="permissions[]" id="permissions">

                                        @foreach($permissions as $val)
                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Message</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" class="form-control p-0 border-0"></textarea>
                                        </div>
                                    </div> -->

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" id="compBtn" class="btn btn-primary">Save</button>
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
<script>



    $(document).ready(function() {

        $("#permissions,#modules").select2({
            width: 'element'
            , minimumResultsForSearch: Infinity
        });


    });

    /*
	* dnkdkf
	* created on : 19-05-2022
	*/
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
