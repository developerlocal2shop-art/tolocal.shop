@extends('layouts.adminmaster')
@section('title','Offline Reports | Edit')
@section('content')

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
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Offline Reports</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    {{-- @if ($user->hasPermission('add') == true) --}}
                    <a href="{{ route('admin.offline.reports') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Offline Reports</a>
                    {{-- @endif --}}
                </div>
            </div>

        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.offline.reports.update', $rData->id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">From date</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="date" required name="fromdate" value="{{ $rData->fromdate }}" placeholder="fromdate" class="form-control p-0 border-0" autocomplete=off>

                                    </div>
                                    <span class="text-danger">@error('fromdate'){{$message}} @enderror</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="example-email" class="col-md-12 p-0">To date</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="date" required name="todate" value="{{ $rData->todate }}" placeholder="todate" class="form-control p-0 border-0" autocomplete=off>

                                    </div>
                                    <span class="text-danger">@error('todate'){{$message}} @enderror</span>
                                </div>
                            </div>
                            

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="example-email" class="col-md-12 p-0">City</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" required name="city" value="{{ $rData->city }}" placeholder="city" class="form-control p-0 border-0" autocomplete=off>

                                    </div>
                                    <span class="text-danger">@error('value'){{$message}} @enderror</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="example-email" class="col-md-12 p-0">Zip code</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" required name="zipcode" value="{{ $rData->zipcode }}" placeholder="zipcode" class="form-control p-0 border-0" autocomplete=off>

                                    </div>
                                    <span class="text-danger">@error('value'){{$message}} @enderror</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-4">
                                    <label for="example-email" class="col-md-12 p-0">Value</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" required name="value" value="{{ $rData->value }}" placeholder="value" class="form-control p-0 border-0" autocomplete=off>

                                    </div>
                                    <span class="text-danger">@error('value'){{$message}} @enderror</span>
                                </div>
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
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <footer class="footer text-center">
        <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
    </footer>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->


@endsection
