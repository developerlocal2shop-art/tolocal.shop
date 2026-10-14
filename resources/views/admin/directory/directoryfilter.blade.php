@extends('layouts.adminmaster')
@section('title','Directory| Listing')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
<?php $directoryDetails = $data['directoryDetails'] ; ?>
    <style>
        .toggle{
            border-radius:20rem !important;
            height:10px !important;
        }
        .toggle.btn {
            min-height: 26px !important;
        }
        .btn-danger{
            color: #fff !important;
        }
        
    </style>
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Directory</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <div class="d-md-flex">
                            <ol class="breadcrumb ms-auto">
                                <li><a href="#" class="fw-normal"></a></li>
                            </ol>
                            @if ($user->hasPermission('add') == true)
                            <a href="{{ route('admin.directory.create') }}" 
                                class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add New Directory</a>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
           <div class="container-fluid">
            @if(session()->get('msg'))
                <div class="alert alert-success">
                {{ session()->get('msg') }}  
                </div><br />
            @endif
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="row">
                                <div class="col-md-8">
                                    <h3 class="box-title">Directory List</h3>
                                </div>
                                
                            </div>
                            <!-- <h3 class="box-title">Directory List</h3> -->
                            <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">S.No</th>
                                            <th class="border-top-0">Title</th>
                                            <th class="border-top-0">Location</th>
                                            <th class="border-top-0">Status</th>
                                            <th class="border-top-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=0; ?>
                                        @foreach($directoryDetails as $directoryrow)
                                        <tr>
                                            <?php //echo "<pre>"; print_r($row); ?>
                                            <td><?php echo ++$i; ?></td>
                                            <td>{{ $directoryrow->title }}</td>
                                          
                                            <td>
                                                @php
                                                    echo App\Http\Controllers\Admin\Directory\DirectoryController::getCountry($directoryrow->country_id);
                                                @endphp
                                            </td>
                                            <td>{{ $directoryrow->status }}</td>
                                            <td>
                                            @if ($user->hasPermission('edit') == true)
                                                <a class="btn btn-primary btn-xs"  href="<?php echo url("/admin/directory-edit",['id' => @$directoryrow->directory_id]); ?>" >EDIT</a>
                                            @endif
                                            @if ($user->hasPermission('delete') == true)    
                                                <a class="btn btn-danger btn-xs show_confirm"  href="<?php echo url("/admin/directory-delete",['id' => @$directoryrow->directory_id]); ?>" >DELETE</a>
                                            @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                                
                            </div>
                            <div class="d-flex justify-content-center cust-page">
                                @if(isset($query))
                                {{ $directoryDetails->appends($query)->links() }}
                                @else
                                {!! $directoryDetails->links() !!}
                                @endif
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
      </div>
    </div>
    
    <script type="text/javascript">
        $('.show_confirm').click(function(e) {
            if(!confirm('Are you sure you want to delete this?')) {
                e.preventDefault();
            }
        });
    </script>
    
@endsection