@extends('layouts.client.company')
@section('title','Directory Own Tags List')
@section('content')
@php $user = auth()->user(); @endphp
<div class="sub_header_in sticky_header filter_sub_blue_header">

	<div class="container">
		<h1>Directory Own Tags</h1>
		<x-client.company_account_menu/>
	</div>
	<!-- /container -->
</div>
<main>
	<div class="container margin_60_35">
		<div class="row">
			<div class="col-lg-12">
					@if(Session::has('error'))
						<div class="alert alert-danger">
						{{ Session::get('error') }}
						@php
						Session::forget('error');
						@endphp
						</div>
					@endif

					@if(Session::has('success'))
					<div class="alert alert-success">
						{{ Session::get('success') }}
						@php
							Session::forget('success');
						@endphp
					</div>
					@endif
					</div>
				</div>
					<!--Main Area-->
					<div class="container-fluid">
							@if(session()->get('msg'))
							<div class="alert alert-success">
								{{ session()->get('msg') }}
							</div><br />
							@endif
							<div class="row">
								<div class="col-sm-12">
									<div class="white-box">
									<form action="{{ route('company.directoryowntags.indexsearch') }}" method="POST" class="">
											{{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-md-3">
                                                <h3 class="box-title">Directory Own Tags List</h3>
                                            </div>
                                            <div class="col-md-9">
                                                <div class=" row d-md-flex">
                                                    <div class="col-md-3 input-group mb-4">
                                                        <div class="hotel-search">

                                                            <select name="category" id="category" class="form-control categoryselect " data-allow-clear="true" data-placeholder="Select category">
                                                                <option value="" >Select Category</option>
                                                                @foreach($catdata as $cat)
                                                                <option value="{{ $cat->id }}" {{ $catid == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class=" col-md-3 input-group mb-4">
                                                        <input type="text"  name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                                        <div class="input-group-append">
                                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />&nbsp;&nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 input-group mb-4">
														<?php
															$getFeatureUsesValue = Helper::getFeatureUsesValue('directory-own-tags');
															if($getFeatureUsesValue <= count($tags)){
														?> 
														<a onclick="alert('Please upgrade profile. you can not add directory Own Tags greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Tags</a>
														<?php }else{ ?> 
														
														@if ($user->hasPermission('add') == true)
                                                            <a href="{{ route('company.directoryowntags.create') }}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Tags</a>
                                                        @endif
														<?php } ?>
                                                    </div>

                                                    <div class="col-md-4 input-group mb-4">
														<?php
															$getFeatureUsesValue = Helper::getFeatureUsesValue('directory-own-tags');
															if($getFeatureUsesValue <= count($tags)){
														?> 
														<a onclick="alert('Please upgrade profile. you can not add directory own tags category greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Directory Own Tags category</a>
														<?php }else{ ?> 
														       <a href="{{ route('company.directoryowntagscategory.index')}}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">
																Directory Own Tags category
																</a>
														<?php } ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</form>
										<div class="table-responsive">
											<table class="table text-nowrap">
												<thead>
													<tr>
														<th class="border-top-0">Directory Tag Id</th>
														<th class="border-top-0">Tags Name</th>
														<th class="border-top-0">Directory</th>
                                                        <th class="border-top-0">Category</th>
														<th class="border-top-0">Status</th>
														<th class="border-top-0">Action</th>
													</tr>
												</thead>
												<tbody>
												<?php $i = 0; $datacount = count($tags); if ($datacount>0){ ?>
													@foreach($tags as $rowtag)
															<tr>
																<td>{{ $rowtag->id }}</td>
																<td>{{ $rowtag->tag_name }}</td>
																<td>
																	@php
																	$directoryData = Helper::getDirectory($rowtag->directory_id);
																	echo $directoryData[0]->title;
																	@endphp
																</td>
                                                                <td>
                                                                    @php
                                                                    $directoryCategoey = Helper::getDirectoryOwnTagsCategory($rowtag->category_id);
                                                                    echo isset($directoryCategoey[0]->name)?$directoryCategoey[0]->name:"None";
                                                                    @endphp
                                                                </td>
																<td>
																@if ($user->hasPermission('status') == true)
																<input data-id="{{@$rowtag->id}}" class="toggle-class" type="checkbox" onChange="statusChange('<?php echo $rowtag->status ?>','<?php echo $rowtag->id ?>');" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ (@$rowtag->status=="Active") ? 'checked' : '' }} >
																@endif
																</td>
																<td>
																@if ($user->hasPermission('edit') == true)
																	<a class="btn btn-primary btn-sm" href="<?php echo url("/company/directory-own-tags-edit", ['id' => @$rowtag->id]); ?>">EDIT</a>
																@endif
																@if ($user->hasPermission('delete') == true)
																	<a class="btn btn-danger btn-sm show_confirm" href="<?php echo url("/company/directory-own-tags-delete", ['id' => @$rowtag->id]); ?>">DELETE</a>
																@endif
																</td>
															</tr>
													@endforeach
													<?php }else{ ?>
														<tr>
															<td align="center" colspan="4">No Data Found</td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
										<div class="d-flex justify-content-center cust-page">
											@if(isset($query))
											{{ $tags->appends($query)->links() }}
											@else
											{!! $tags->links() !!}
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
				</div>
				<!-- /row -->
		<!-- /row -->
	</div>
	<!-- /container -->
</main>

<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });

    function statusChange(statusdata,id){
        var status = (statusdata=="Active")?"InActive":"Active";
        console.log("under statuhhhsChange : ",statusdata);
        console.log("under id : ",id);
        $.ajax({
            type: "GET",
            dataType: "json",
            url: baseUrl+'/company/changedirectoryownTagStatus',
            data: {'status': status, 'user_id': id},
            success: function(data){
                console.log(data.success)
				toastr.success(data.success);
            }
        });
    }
</script>
<style>
[type="radio"]:checked, [type="radio"]:not(:checked) {
    position: unset;
}
.custom-control-label {
    position: unset;
}

@media (min-width: 779px) and (max-width: 991px){
        .col-md-9 {
            -ms-flex: 0 0 75%;
            flex: none;
            max-width: none;

        }
    }
    @media (min-width: 779px) and (max-width: 991px){
        .col-md-3 {
            -ms-flex: 0 0 75%;
            flex: none;
            max-width: none;
        }
    }

</style>
@endsection
