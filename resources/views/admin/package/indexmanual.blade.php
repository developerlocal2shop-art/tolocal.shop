@extends('layouts.adminmaster')
@section('title','Package| Listing')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
<style>
    @media (max-width: 767px) {
        .pull-right{
            margin-top: -30px !important;
        }
        .d-none{
            display: unset !important;
        }

    }
    .toggle {
        border-radius: 20rem !important;
        height: 10px !important;
    }

    .toggle.btn {
        min-height: 26px !important;
    }

    .btn-danger {
        color: #fff !important;
    }
</style>

<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Manual Assign Packages  </h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                     @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.manual.package.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Assign Package</a>
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
                <form action="{{ route('admin.manual.package.indexsearch') }}" method="get" class="">
                {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="box-title">Manual Assign Packages</h3>
                        </div>
                        <div class="col-md-8">
                        <span class="text-danger" style="padding-left:12px;">{{ $emptysearch??'' }}</span>
                            <div class="d-md-flex">
                                <ol class="breadcrumb ms-auto">
                                    <li><a href="#" class="fw-normal"></a></li>
                                </ol>
                                    <select name="whattosearch" id="whattosearch" class="form-control" data-allow-clear="true" >
                                        <option value="" >Choose any option for search</option>
                                        <option value="directoryname" {{ 'directoryname'== $searchstatus ? 'selected':''}}>Search by Directory Name</option>
                                        <option value="emailvendor" {{ 'emailvendor'== $searchstatus ? 'selected':''}}>Search by Email</option>
                                    </select>
                                    <div class="input-group mb-3">
                                        <input type="text"  name="searchtext" id="search" value="{{ $manualpackagesearch }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>
                                    
                            </div>
                           
                        </div>
                    </div>
                    </form>
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">Company</th>
                                    <th class="border-top-0">Vendor Detail</th>
                                    <th class="border-top-0">Package </th>
                                    <th class="border-top-0">Transaction Id</th>
                                    <th class="border-top-0">Transaction Mode</th>
                                    <th class="border-top-0">Notes</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 0; 
                                $datacount = count($plans); 
                                if ($datacount>0){ ?>
                                    @foreach($plans as $row)
                                    <tr>
                                        <td>{{ $row->directoryName->title??'' }}</td>
                                        <td><strong>{{ $row->vendor->name??'' }}</strong></br>({{$row->vendor->email??''}})</td>
                                        <td>{{ $row->plan->name??'' }}</td>
                                        <td>{{ $row->transaction_id }}  </td>
                                        <td>{{ $row->transaction_mode }} </td>
                                        <td>{{ $row->notes }} </td>
                                        <td> @if($row->status == 'Active') Active  @else InActive @endif  </td>
                                        <td>
                                            @if ($user->hasPermission('edit') == true)
                                            <a class="btn btn-primary btn-xs" href="{{ route('admin.manual.package.edit',$row->id) }}">EDIT</a>
                                            @endif
                                            @if ($user->hasPermission('delete') == true)
                                            <a class="btn btn-danger btn-xs show_confirm" href="{{ route('admin.manual.package.destroy',$row->id) }} ">DELETE</a>
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
                        {{ $plans->appends($query)->links() }}
                        @else
                        {!! $plans->links() !!}
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
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });
</script>

@endsection
