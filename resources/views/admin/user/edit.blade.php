@extends('layouts.adminmaster')
@section('title','User| Edit')
@section('content')

        <div class="page-wrapper">

            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Edit User Info</h4>
                    </div>
                    <!-- <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <div class="d-md-flex">
                            <ol class="breadcrumb ms-auto">
                                <li><a href="#" class="fw-normal"></a></li>
                            </ol>
                            <a href="{{ route('admin.user.create') }}" target="_blank"
                                class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add User</a>
                        </div>
                    </div> -->
                </div>

            </div>

            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        @if(session('usermsg'))
                            <div class="alert alert-success">
                                {{ session('usermsg') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul style="margin-bottom:0;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <!-- Column -->



                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.user.updateUser', $singleUserData->id) }}" method="POST" class="">
                                {{ csrf_field() }}
                                <div class="row cus-title">
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="col-md-12 p-0">First Name<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="hidden" name="company_id" id="company_id" value="{{ $singleUserData->directory_id??0 }}" >
                                            <input type="text" name="name" value="{{ old('name' ,$singleUserData->name) }}" placeholder="Enter First Name" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('name'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="col-md-12 p-0">Last Name<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="last_name" value="{{ old('last_name' ,$singleUserData->last_name) }}" placeholder="Enter Last Name" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('last_name'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row cus-title">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12">Set Phone Number<span style="color:red;">*</span></label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="radio" class="pay" name="is_default_valid" value="default" id="pay" {{ $singleUserData->is_verified=='default' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="1">Set As Default Number </label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="radio" class="pay" name="is_default_valid" value="valid" style="margin-left: 20px;" id="nonpay" {{ $singleUserData->is_verified=='valid' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="1">Valid and Active Number </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="col-md-12 p-0">Phone Number<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="phone_number" placeholder="123 456 7890" value="{{ old('phone_number' ,$singleUserData->phone) }}" placeholder="Enter Phone number" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('phone_number'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row cus-title">
                                    <div class="col-md-6 form-group mb-4">
                                        <label for="example-email" class="col-md-12 p-0">Email<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="email" value="{{ old('email' ,$singleUserData->email) }}" placeholder="Enter e-mail address" class="form-control p-0 border-0" name="example-email" id="example-email">
                                            <span class="text-danger">@error('email'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="col-md-12 p-0">Password<span style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom">
                                            <input type="text" name="password" id="password" value="{{ $singleUserData->original_password }}" readonly class="form-control border-0">
                                        </div>
                                    </div>

                                </div>
                                <div class="row cus-title">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Country<span style="color:red;">*</span></label>
                                            <select class="form-control" id="country" name="country" id="country">
                                                <option value=""> Select Country </option>
                                                @foreach($countries as $countryrow)
                                                <option value="{{ $countryrow->id }}" {{ $countryrow->name == $singleUserData->country ? 'selected' : '' }}> {{ $countryrow->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">@error('country'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>State<span style="color:red;">*</span></label>
                                            <select class="form-control" id="state" name="state" id="state">
                                                <option value="">Select State</option>
                                                <option value="{{$stateName->id??null}}" selected>{{$singleUserData->state}}</option>
                                            </select>
                                            <span class="text-danger">@error('state'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group" id="city_name" style="display: none">
                                            <label>City</label>
                                            <input class="form-control" type="text" id="cityname" name="city-name" value="{{$singleUserData->city_id}}">
                                        </div>
                                        <div class="form-group" id="city_dropdown">
                                            <label>City<span style="color:red;">*</span></label>
                                            <select class="form-control" name="city" id="city">
                                                <option value="">Select City</option>
                                                <option value="{{$cityName->id??null}}" selected>{{$singleUserData->city}}</option>
                                            </select>
                                            <span class="text-danger">@error('city'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-4 form-group mb-4">
                                        <label class="col-md-12 p-0">Country</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="country" value="{{ $singleUserData->country }}" placeholder="Enter Country" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('country'){{$message}} @enderror</span>
                                        </div>
                                    </div> -->


                                    <!-- <div class="col-md-4 form-group mb-4">
                                        <label class="col-md-12 p-0">State</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="state" value="{{ $singleUserData->state }}" placeholder="Enter State" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('state'){{$message}} @enderror</span>
                                        </div>
                                    </div> -->

                                    <!-- <div class="col-md-4 form-group mb-4">
                                        <label class="col-md-12 p-0">City</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="city" value="{{ $singleUserData->city }}" placeholder="Enter City" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('city'){{$message}} @enderror</span>
                                        </div>
                                    </div> -->

                                    <div class="row cus-title">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Role<span style="color:red;">*</span></label>
                                                <select class="form-control" id="role" name="role" >
                                                    <option value="">Select Role</option>
                                                    <option value="1" @if($singleUserData->role_id == 1) selected @endif>Vendor</option>
                                                    <option value="0" @if($singleUserData->role_id == 0) selected @endif>User</option>
                                                </select>
                                                <span class="text-danger">@error('role'){{$message}} @enderror</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6" id="commission_field" style="display: none;">
                                            <div class="form-group">
                                                <label>Commission in Percentage<span style="color:red;">*</span></label>
                                                <input class="form-control" type="text" id="commission"  name="commission" value="{{ old('commission' ,$singleUserData->commission) }}">
                                                <span class="text-danger">@error('commission'){{$message}} @enderror</span>
                                            </div>
                                        </div>

                                        <div class="form-group mb-4 col-md-12">
                                            <label class="col-md-12 p-0">Address</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <textarea rows="5" name="address" class="form-control p-0 border-0" style="border: 1px solid #ccc !important;">{{ old('address' ,$singleUserData->address) }}</textarea>
                                                <span class="text-danger">@error('address'){{$message}} @enderror</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row cus-title">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="form-check-input" name="import_optional" id=""  {{ $singleUserData->import_optional=='1' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="1" style="display: inline-flex;">
                                                    <div id="tooltip">
                                                        <span id="tooltipText">Please check this for import all optional product's fields.</span>
                                                        <span><i class="fas fa-info-circle" style="color:#3c3c3e"></i></span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="form-check-input" name="show_import_file" id=""  value="1" {{ $singleUserData->show_import_file=='1' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="1" style="display: inline-flex;">
                                                    <div id="tooltip">
                                                        <span id="tooltipText">Please check this to show vendor's product import file.</span>
                                                        <span><i class="fas fa-info-circle" style="color:#3c3c3e"></i></span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>



                                    <!-- <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Password</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="password" name="password" value="{{ $singleUserData->password }}" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('password'){{$message}} @enderror</span>
                                        </div>
                                    </div> -->
                                    <!--  -->
                                    <!-- <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Message</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" class="form-control p-0 border-0"></textarea>
                                        </div>
                                    </div> -->


                                    <div class="form-group mb-4">

                                        <span class="text-danger">@error('popular'){{$message}} @enderror</span>

                                            <button type="submit" class="btn btn-info">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    <!-- Column -->
                </div>


                <div class="row mb-4">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <div class="panel-heading cust-tab-head">
                            <h4 class="box-title">Address List</h4>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">
                    @if(Session::has('addresssuccess'))
                        <div class="alert alert-success">
                        {{ Session::get('addresssuccess') }}
                        @php
                            Session::forget('addresssuccess');
                        @endphp
                        </div>
                    @endif

                    </div>

                    <div class="col-md-12" style="text-align: right;">
                        <a class="btn btn-success address_add_btn" href="{{ url('/admin/account/add-form-address/'.$singleUserData->id) }}">Add New Address</a><hr>
                    </div>


                    <?php $i = 0; $count = count($addressData); if ($count>0){?>

                        <?php foreach($addressData as $address){
                            $shippingcountryName = Helper::getCountryName($address['country']);
                            $shippingstateName = Helper::getStateName($address['state']);
                            $shippingCityName = Helper::getCityName($address['city_id']);
                        ?>
                        <div class="col-md-6">

                            <div class="add-list">
                            <?php if($address['default_address']=="yes"){ ?>
                                <b style="color: #11d511;">
                                    <p><?php echo $address['first_name']." ".$address['last_name']; ?><br>
                                        <?php echo $address['email'];?><br>
                                        <?php echo $address['phone'];?><br>
                                        <?php echo $address['address1'];?><br>
                                        <?php echo $address['address2'];?><br>
                                        <?php if($address['city']!="")echo $shippingCityName->name??''.",";?> <?php if($address['state']!="")echo $shippingstateName->name??'';?><br>
                                        <?php if($address['country']!="")echo $shippingcountryName->name??'';?> - <?php echo $address['zipcode'];?>
                                    </p>
                                </b>
                            <?php }else{ ?>
                                <p><?php echo $address['first_name']." ".$address['last_name']; ?><br>
                                    <?php echo $address['email'];?><br>
                                    <?php echo $address['phone'];?><br>
                                    <?php echo $address['address1'];?><br>
                                    <?php echo $address['address2'];?><br>
                                    <?php if($address['city']!="")echo $shippingCityName->name??''.",";?> <?php if($address['state']!="")echo $shippingstateName->name??'';?><br>
                                    <?php if($address['country']!="")echo $shippingcountryName->name??'';?> - <?php echo $address['zipcode'];?>
                                </p>
                            <?php } ?>

                            <p><a class="btn btn-primary btn-sm" href="<?php echo url("/admin/account/edit-address/".$address['id']); ?>">Edit</a>
                            <a class="btn btn-danger btn-sm show_confirm" onclick="return confirm('Are you sure?')" href="<?php echo url("/admin/account/delete-address/".$address['id']); ?>">Delete</a></p>
                        </div>
                        </div>
                        <?php }?>

                    <?php }else{ ?>

                        <div class="col-md-12">
                            <p>There is no address</p>
                        </div>

                    <?php } ?>

                    <!-- /row -->

                </div>

            </div>



        </div>

    </div>

    <script>
        $(document).ready(function() {

            @if($singleUserData->role_id == 1)
                $('#commission').prop('required', true);
                document.getElementById('commission_field').style.display = "block";
            @endif

            $('#role').on('change', function() {
                var role_id = this.value;
                if(role_id == 1){
                    $('#commission').prop('required', true);
                    document.getElementById('commission_field').style.display = "block";
                }else{
                    $('#commission').prop('required', false);
                    document.getElementById('commission_field').style.display = "none";
                }
            });


            var city = $('#city').val();
            var cityText = $('#cityname').val();
            if(cityText){
                document.getElementById('city_name').style.display = "block";
                document.getElementById('city_dropdown').style.display = "none";
            }else if(city){
                document.getElementById('city_name').style.display = "none";
                document.getElementById('city_dropdown').style.display = "block";
            }
        });
    </script>
    <script type="text/javascript">

            $('#country').on('change', function() {

                var country_id = this.value;

                $("#state").html('');

                $.ajax({
                    url: baseUrl + '/get-countrybyid'
                    , type: "POST"
                    , data: {
                        country_id: country_id
                        , _token: '{{csrf_token()}}'
                    }
                    , dataType: 'json'
                    , success: function(result2) {
                        console.log(result2);
                    }
                });

                $.ajax({
                    url: baseUrl + '/get-states-by-country'
                    , type: "POST"
                    , data: {
                        country_id: country_id
                        , _token: '{{csrf_token()}}'
                    }
                    , dataType: 'json'
                    , success: function(result) {
                        //console.log(result.states[0].name);
                        $('#state').html('<option value="">Select State</option>');
                        $.each(result.states, function(key, value) {
                            $("#state").append('<option value="' + value.id + '" >' + value.name + '</option>');
                        });

                    }
                });

            });

            $('#state').on('change', function() {
                var state_id = this.value;
                $("#city").html('');
                $.ajax({
                    url: baseUrl + '/get-cities-by-state'
                    , type: "POST"
                    , data: {
                        state_id: state_id
                        , _token: '{{ csrf_token() }}'
                    }
                    , dataType: 'json'
                    , success: function(result) {
                        //console.log(result.cities.length);
                        if(result.cities.length== 0){
                        // $('#city_name').style.display = "block";
                            document.getElementById('city_name').style.display = "block";
                            document.getElementById('city_dropdown').style.display = "none";

                        }else{
                            document.getElementById('city_name').style.display = "none";
                            document.getElementById('city_dropdown').style.display = "block";

                            $('#city').html('<option value="">Select City</option>');
                            $.each(result.cities, function(key, value) {
                                $("#city").append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                        }

                    }
                });
            });

    </script>
    @endsection
