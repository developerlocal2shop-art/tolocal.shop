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
                <h4 class="page-title">Pending Trademarks</h4>
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
                    <form method="get" class="">
                   
                        <div class="row">
                            <div class="col-md-3">
                                <h3 class="box-title">Trademarks Pending List</h3>
                            </div>
                            <div class="col-md-9">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>

                                        <div class="input-group mb-4">
                                            <div class="hotel-search">
                                               <select class="btn btn-primary" name="statusm" id="statusm">
                                                    <option value="1" >Approved</option>
                                                    <option value="2" >Rejected</option>
                                                </select>
                                            </div>&nbsp;
                                            <button class="btn btn-primary btnch" id="btnch" name="search" > Go!</button>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="hidden"  name="tradmark_id" id="tradmark_id" value="" aria-describedby="basic-addon2"> 
                                            <div class="input-group-append">
                                                
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
                                    <th class="border-top-0"><input type="checkbox" id="checkAll"> Check All</th>
                                    <th class="border-top-0">Preview</th>
                                    <th class="border-top-0">Name</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($pendingdata as $row)
                                <tr>
                                  <td><input type="checkbox" name="name[]" id="checkbox-<?=$i?>" value="{!! $row->id !!}" class="custom"/></td>
                                   <td><img src="{{ env('IMAGE_PATH').'files/trademark/'.$row->name_thumbnail }}" height="50px" width="50px"></td>
                                    <td>{{ $row->name }}</td>
                                    
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
                        {{ $pendingdata->appends($query)->links() }}
                        @else
                        {!! $pendingdata->links() !!}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script type="text/javascript">

    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });


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

     $(document).on('click', '#btnch', function(event){

         event.preventDefault();

         var tradmark_id = $('.custom:checked').map(function() {
        return this.value;
        }).get().join(',');
        if (tradmark_id != '') {
            $('#tradmark_id').val(tradmark_id);

        } else {
            alert('Please select at least one record');
            return false;
        }

		var status = $('#statusm').val();
		var id = $('#tradmark_id').val();
		var token = "{{ csrf_token() }}";
		$.ajax({
		  url: baseUrl + "/admin/changeTrademarkbulkStatus",
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
