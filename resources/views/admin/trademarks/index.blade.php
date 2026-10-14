@extends('layouts.adminmaster')
@section('title','Trademark| Listing')
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

<?php
// if (isset($data['blogs'])) {
//     $blogs = $data['blogs'];
// }
//echo "<pre>"; print_r($blogs); die;
?>
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
                <h4 class="page-title">Trademarks</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                     @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.trademarks.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Trademarks</a>
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
                    <form action="{{ route('admin.trademarks.indexsearch') }}" method="get" class="">
                    {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-5">
                                <h3 class="box-title">Trademarks List</h3>
                            </div>
                            <div class="col-md-7">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>


                                        <div class="input-group mb-4">
                                            <div class="hotel-search">
                                            
                                                <select name="category" id="category" class="form-control categoryselect " data-allow-clear="true" data-placeholder="Select category">
                                                    <option value="" >Select Category</option>
                                                    @foreach($catdata as $cat)
                                                    <option value="{{ $cat->id }}" {{ $catid == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="text"  name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
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
                                    <th class="border-top-0">Preview</th>
                                    <th class="border-top-0">Name</th>
                                    <th class="border-top-0">Category</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($trademark as $row)
                                <tr>
                                   <td><img src="{{ env('IMAGE_PATH').'files/trademark/'.$row->name_thumbnail }}" height="50px" width="50px"></td>
                                    <td>{{ $row->name }}</td>
                                    <td>
                                        @php
                                        $tradeCategoey = Helper::getTradeCategory($row->category_id);
                                        echo isset($tradeCategoey[0]->name)?$tradeCategoey[0]->name:"None";
                                        @endphp
                                    </td>
                                    <td>
                                    
                                    <select class="btn @if($row->status == 0) btn-primary @elseif($row->status == 1) btn-success @else btn-danger  @endif btn-xs" name="status" id="status" data-id="{!! $row->id !!}">
                                        <option value="0" <?php if($row->status == 0): ?> selected="selected" <?php endif; ?>>Pending</option>
                                        <option value="1" <?php if($row->status == 1): ?> selected="selected" <?php endif; ?>>Approved</option>
                                        <option value="2" <?php if($row->status == 2): ?> selected="selected" <?php endif; ?>>Rejected</option>
                                    </select>
                                    </td>
                                    <td>
                                     @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/trademarks-edit", ['id' => @$row->id]); ?>">EDIT</a>
                                    @endif
                                      @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/trademarks-delete", ['id' => @$row->id]); ?>">DELETE</a>
                                    @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $trademark->appends($query)->links() }}
                        @else
                        {!! $trademark->links() !!}
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

    $(document).on('change', '#status', function(){
		var status = $(this).val();
		var id = $(this).data('id');
		var token = "{{ csrf_token() }}";
		$.ajax({
		  url: baseUrl + "/admin/changeTrademarkStatus",
		  type: "POST",
		  data: {status:status,id:id, _token:token},
		  success: function(responce){
			var obj = jQuery.parseJSON(responce);
			console.log(obj);
			if(obj.status == 'success'){
			 
                toastr.success(obj.msg);
                setTimeout(function(){ location.reload(); }, 1000);
			} else {
                toastr.error(obj.msg);
				//swal(obj.status, obj.msg, "error");
			}
		  }
		});
	});


</script>

@endsection
