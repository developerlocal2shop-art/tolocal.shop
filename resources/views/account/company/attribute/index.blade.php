@extends('layouts.client.vendor')
@section('title','Attribute| Listing')
@section('content')
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Product's Attribute</h1>
        <x-client.company_account_menu />
    </div>
</div>
<div class="loader" id="loader"></div>
<main>
    <div class="container margin_60_35">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <h4 class="page-title">Attribute List </h4>
            </div>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                <div class="d-md-flex">
                    {{-- @if ($user->hasPermission('add') == true) --}}
                    {{-- <a href="{{ route('company.attribute.create') }}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add New Attribute</a> --}}
                    {{-- @endif --}}
                </div>
            </div>
        </div>
        <div class="white-box">
            <div class="row">
                @if(session()->get('msg'))
                <div class="alert alert-success">
                    {{ session()->get('msg') }}
                </div><br />
                @endif
                <div class="col-sm-12">
                    <div class="white-box">
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="border-top-0">Attr Id</th>
                                        <th class="border-top-0">Attribute Name</th>
                                        <th class="border-top-0">Attribute Content</th>
                                        <th class="border-top-0">Status</th>
                                        {{-- <th class="border-top-0">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($attribute as $row)
                                    <tr>
                                        <td>{{ $row->id }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->content }}</td>
                                        <td>
                                            {{-- @if ($user->hasPermission('status') == true) --}}
                                            <input data-id="{{$row->AttributeStatus->id??'0'}}" data-attrid="{{$row->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ ($row->AttributeStatus->status??'1'=='1') ? 'checked' : '' }} onChange="changeStatus('<?php echo $row->AttributeStatus->status??'' ?>','<?php echo $row->AttributeStatus->id??'0' ?>','<?php echo $row->id; ?>');">
                                            {{-- @endif --}}
                                        </td>
                                        {{-- <td> --}}
                                            {{-- @if ($user->hasPermission('edit') == true) --}}
                                            {{-- <a class="btn btn-primary btn-xs" href="{{ route('company.attribute.edit',$row->id) }}">EDIT</a> --}}
                                            {{-- @endif
                                            @if ($user->hasPermission('delete') == true) --}}
                                            {{-- <a class="btn btn-danger btn-xs show_confirm" href="{{ route('company.attribute.destroy',$row->id) }}">DELETE</a> --}}
                                            {{-- @endif --}}
                                        {{-- </td> --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center cust-page">
                            @if(isset($query))
                            {{ $attribute->appends($query)->links() }}
                            @else
                            {!! $attribute->links() !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>

<script type="text/javascript">

    $(document).ready(function() {
	    $("#loader").fadeOut("slow");
    });

    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });

    function changeStatus(statusdata, id, attrid) {
        var status = (statusdata != '0') ? '0' : '1';
        console.log(status);
        $.ajax({
            type: "GET"
            , dataType: "json"
            , url: baseUrl + '/company/changeAttributeStatus'
            , data: {
                'status': status,
                'attrid': attrid, 
                'id': id
            }
            , success: function(data) {
                console.log(data.success);
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        });
    }
</script>
<style>
    @media (max-width: 767px) {
        .pull-right {
            margin-top: -30px !important;
        }
        .d-none {
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
    .hotel-search {
        margin-right: 25px;
        width: 90%;
    }
</style>
@endsection
