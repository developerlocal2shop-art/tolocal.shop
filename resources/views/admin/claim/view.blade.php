@extends('layouts.adminmaster')
@section('title', 'Claim | View Claim')
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
                    <h4 class="page-title">View Claim</h4>
                </div>

            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="claim-data-result">
                            <div class="row">
                                <div class="col-md-12 mt-2" style="margin-bottom: 1.3rem;">
                                    <div class="col-md-6" style="border-bottom: 1px solid #ddd; width:45%;">
                                        <span><strong>Email Id : </strong></span>
                                        <span>{{ isset($viewData[0]->email)? $viewData[0]->email:''}}</span>
                                    </div>
                                    <div class="col-md-6 pull-right" style="margin-top: -23px; border-bottom: 1px solid #ddd;">
                                        <span><strong>Phone : </strong></span>
                                        <span>{{ isset($viewData[0]->phone)? $viewData[0]->phone:''}}</span>
                                    </div>
                                </div>
                            </div>
                            <p><strong>Subject</strong></p>
                            <p>{{ $claimData->subject }}</p>
                            <p><strong>Description</strong></p>
                            <p>{{ $claimData->description }}</p>
                        </div>

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
