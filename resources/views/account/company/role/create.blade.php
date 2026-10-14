@extends('layouts.client.company')
@section('title','User | Add Role')
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
            <div class="card">
                <div class="card-body">
                   <h4 class="page-title">Role</h4>
                    <form action="{{ route('company.role.submit') }}" method="POST" class="">
                        {{ csrf_field() }}
                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Role Name</label>
                            <div class="col-md-12 border-bottom p-0">
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter Name" class="form-control p-0 border-0" autocomplete=off>

                            </div>
                            <span class="text-danger">@error('name'){{$message}} @enderror</span>
                        </div>

                        <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Modules</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control p-0 border-0" style="width:100%" required multiple name="modules[]" id="modules">

                                        @foreach($modules as $val)
                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Permission</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control p-0 border-0" style="width:100%" required multiple name="permissions[]" id="permissions">

                                        @foreach($permissions as $val)
                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        <div class="form-group mb-4">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Column -->
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
