@extends('layouts.client.company')
@section('title','Directory Own Tags Category| Listing')
@section('content')
@php $user = auth()->user(); @endphp
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Directory Own Tags Categories</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<main>
    <div class="container margin_60_35">
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
                        <div class="col-md-5">
                            <h3 class="box-title">Directory Own Tags Categories List</h3>
                        </div>
                        <div class="col-md-4">
                            <div class="d-md-flex">
                                <form action="{{ route('company.directoryowntagscategory.indexsearch') }}" method="get" class="">
                                    <div class="input-group mb-3">
                                        <input type="text" name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                        <div class="col-md-3">
                            {{-- @if ($user->hasPermission('add') == true) --}}
                            <a href="{{ route('company.directoryowntagscategory.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Directory Own Tags Category</a>
                            {{-- @endif --}}
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">Id</th>
                                    <th class="border-top-0">Category Name</th>
                                    <th class="border-top-0">Category description</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; $datacount = count($directoryowntagscategory); if ($datacount>0){?>
                                @foreach($directoryowntagscategory as $row)
                                <tr>
                                    <td><?php echo ++$i; ?></td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->description }}</td>
                                    <td>{{ $row->status }}</td>
                                    <td>
                                    {{-- @if ($user->hasPermission('edit') == true) --}}
                                        <a class="btn btn-primary btn-xs" href="<?php echo url("/company/directory-own-tags-category-edit", ['id' => @$row->id]); ?>">EDIT</a>
                                    {{-- @endif     --}}
                                    {{-- @if ($user->hasPermission('delete') == true) --}}
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/company/directory-own-tags-category-delete", ['id' => @$row->id]); ?>">DELETE</a>
                                    {{-- @endif  --}}
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
                        {!! $directoryowntagscategory->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</main>
<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });
</script>
<style>
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
@endsection
