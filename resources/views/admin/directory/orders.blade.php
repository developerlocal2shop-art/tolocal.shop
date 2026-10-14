@extends('layouts.adminmaster')
@section('title','Directory| Orders')
@php $user = auth()->guard('webadmin')->user(); @endphp
@section('content')

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
       
        <div class="page-wrapper">
           
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Directory Orders</h4>
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
                                    <h3 class="box-title">Directory Orders List</h3>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-md-flex">
                                        <ol class="breadcrumb ms-auto">
                                            <li><a href="#" class="fw-normal"></a></li>
                                        </ol>
                                        <form action="{{ route('admin.directory.indexsearch') }}" method="POST" class="">
                                            {{ csrf_field() }}
                                            <div class="input-group mb-3">
                                                <input type="text" name="searchtext" id="search" value="" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                                <div class="input-group-append">
                                                    <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                                </div>
                                            </div>
                                        
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                        <th class="border-top-0">S.No</th>
                                            <th class="border-top-0">Full Name</th>
                                            <th class="border-top-0">Phone</th>
                                            <th class="border-top-0">Email</th>
                                            <th class="border-top-0">Total Amount</th>
                                            <th class="border-top-0">Total Tax</th>
                                            <th class="border-top-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0; $datacount = count($dirOrdersData); 
                                        if ($datacount>0){?>
                                        @foreach($dirOrdersData as $orderdata)
                                            <tr>
                                                <td><?php echo ++$i; ?></td>
                                                <td>{{ $orderdata->first_name.' '.$orderdata->last_name }}</td>
                                                <td>{{ $orderdata->phone }}</td> 
                                                <td>{{ $orderdata->email }}</td>
                                                <td>{{ $orderdata->currency }} {{ $orderdata->total_amount }}</td>
                                                <td>{{ $orderdata->currency }} {{ $orderdata ->total_tax }}</td>
                                                <td>
                                                    @if ($user->hasPermission('view') == true)
                                                    <a class="btn btn-primary btn-xs" href="
                                                    <?php echo url("/admin/view",['id' => @$orderdata->id]); ?>">VIEW
                                                    </a>
                                                    @endif
                                                    @if ($user->hasPermission('delete') == true)
                                                    <a class="btn btn-danger btn-xs show_confirm" href="#">DELETE</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        <?php }else{ ?> 
                                            <tr>
                                                <td align="center" colspan="10">No Data Found</td>
                                            </tr>
                                        <?php } ?> 
                                    </tbody>
                                </table>
                                
                            </div>
                            <div class="d-flex justify-content-center cust-page">
                                @if(isset($query))
                                {{ $dirOrdersData->appends($query)->links() }}
                                @else
                                {!! $dirOrdersData->links() !!}
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