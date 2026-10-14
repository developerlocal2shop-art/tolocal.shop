@extends('layouts.client.vendor')
@section('title','Trademark| Listing')
@section('content')
@php $user = auth()->user(); @endphp
<style>
    @media (min-width: 779px) and (max-width: 991px){
        .col-md-9 {
            -ms-flex: 0 0 75%;
            flex: none;
            max-width: none;
            padding: 0px;
            margin-left: 16px;
        }
    }
    @media (min-width: 779px) and (max-width: 991px){
        .col-md-3 {
            -ms-flex: 0 0 75%;
            flex: none;
            max-width: none;
        }

        .categoryselect{
            padding: 3px;
        }
    }
    @media (min-width: 768px){
        .d-md-block {
            margin-right: 3px;
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
<div class="sub_header_in sticky_header filter_sub_blue_header">

    <div class="container">
        <h1>Trademarks</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<main>

    <div class="container margin_60_35">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->

    <div class="container-fluid">
        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <form action="{{ route('company.trademarks.indexsearch') }}" method="get" class="">

                        <div class="row">
                            <div class="col-md-3">
                                <h3 class="box-title">Trademarks List</h3>
                            </div>
                            <div class="col-md-9">
                                <div class="d-md-flex">
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
                                        <div class="input-group mb-4">
                                            <input type="text"  name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <input class="btn btn-primary" name="search" type="submit" value="Go!" />&nbsp;&nbsp;
                                            </div>
                                        </div>

                                        <div class="input-group mb-4">

                                        
                                        <?php
                                            $getFeatureUsesValue = Helper::getFeatureUsesValue('trademarks');
                                            if($getFeatureUsesValue <= count($trademark)){
                                        ?> 
                                        <a onclick="alert('Please upgrade profile. you can not add trademarks greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white trad-btn">Add New Trademarks</a>
                                        <?php }else{ ?> 
                                            @if ($user->hasPermission('add') == true)
                                            <a href="{{ route('company.trademarks.create') }}" class="btn btn-primary  d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white trad-btn">Add New Trademarks</a>
                                            @endif
                                        <?php } ?>


                                        </div>

                                        <div class="input-group mb-4">
                                            @if ($user->hasPermission('view') == true)
                                            <a href="{{ route('company.trademarkcategory.index') }}" class="btn btn-primary  d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white trad-btn">Trademarks category</a>
                                            @endif
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
                                    @if($row->status == 0)
                                        Pending
                                    @elseif ($row->status == 1)
                                        Approved
                                    @elseif ($row->status == 2)
                                        Rejected
                                    @endif
                                    </td>
                                    <td>
                                     @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-sm" href="<?php echo url("/company/trademarks-edit", ['id' => @$row->id]); ?>">EDIT</a>
                                    @endif
                                    @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-sm show_confirm" href="<?php echo url("/company/trademarks-delete", ['id' => @$row->id]); ?>">DELETE</a>
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
</main>

<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });
</script>
<style>
@media(max-width:414px)
{
    .hotel-search {
        width: 100%;
    }

    .trad-btn{width: 100%;}
}
</style>
@endsection
