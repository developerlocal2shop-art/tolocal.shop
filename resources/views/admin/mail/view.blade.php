@extends('layouts.adminmaster')
@section('title','Mail| View')
@section('content')
        <div class="page-wrapper">
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Mail</h4>
                    </div>
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <?php //echo "<pre>"; print_r($mailData);die; ?>
            <div class="container-fluid">
                
                <div class="row">
                    <!-- Column -->
                    
                        <div class="card">
                            <div class="card-body">

                            <div class="row mb-4">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <div class="panel-heading cust-tab-head">
                                        <h4 class="box-title">Mail View</h4>
                                    </div>
                                </div>
                            </div>
                                
                                <table>
                                    <tr>
                                        <td> To:  {{ $mailData->to_email }}</td>

                                    </tr>
                                    <tr>
                                        <td> Subject:  <strong>{{ $mailData->subject }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td> </td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo $mailData->content; ?> </td>
                                    </tr>

                                </table>
                                
                            </div>
                        </div>
                    
                    <!-- Column -->
                </div>
                
            </div>
           
            <footer class="footer text-center"> 
                <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
            </footer>
            
        </div>
       
    </div>
    
    @endsection