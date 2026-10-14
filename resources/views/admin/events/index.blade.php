@extends('layouts.adminmaster')
@section('title','Events| Listing')
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
                <h4 class="page-title">Events</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                     @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.events.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Events</a>
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
                            <h3 class="box-title">Events List</h3>
                        </div>
                        <div class="col-md-4">
                            <div class="d-md-flex">
                                <ol class="breadcrumb ms-auto">
                                    <li><a href="#" class="fw-normal"></a></li>
                                </ol>
                                <form action="{{ route('admin.events.indexsearch') }}" method="get" class="">
                                    {{ csrf_field() }}
                                    <div class="input-group mb-3">
                                        <input type="text"  name="searchtext" id="search" value="{{ $eventssearch }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">Image</th>
                                    <th class="border-top-0">Title</th>
                                    <th class="border-top-0">Visitors</th>
                                    <th class="border-top-0">Likes</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($events as $row)
                                <tr>
                                    <td><img src="{{ env('IMAGE_PATH').'files/events/'.$row->event_image }}" height="50px" width="50px"></td>
                                    <td>{{ $row->title }}</td>
                                    <td>
                                        @if(count(Helper::CountVisitorsId('event',$row->id)) != 0)
                                            {{ count(Helper::CountVisitorsId('event',$row->id)) }}
                                        @else
                                        {{ 0 }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(count(Helper::CountLikeData('event',$row->id)) != 0)
                                            {{ count(Helper::CountLikeData('event',$row->id)) }}
                                        @else
                                        {{ 0 }}
                                        @endif
                                    </td>
                                    <td>
                                        <input data-id="{{$row->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ ($row->status=='Active') ? 'checked' : '' }} onChange="changeStatus('<?php echo $row->status ?>','<?php echo $row->id ?>');">
                                        {{-- {{ $row->status }} --}}
                                    </td>
                                    <td>
                                        @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/events-edit", ['id' => @$row->id]); ?>">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/events-delete", ['id' => @$row->id]); ?>">DELETE</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $events->appends($query)->links() }}
                        @else
                        {!! $events->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script type="text/javascript">

    function changeStatus(statusdata,id){
        var status = (statusdata!='Inactive')?'Inactive':'Active';
        $.ajax({
            type: "GET",
            dataType: "json",
            url: baseUrl+'/admin/changeEventStatus',
            data: {'status': status, 'id': id},
            success: function(data){
                console.log(data.success);
            }
        });
    }

    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });
</script>

@endsection
