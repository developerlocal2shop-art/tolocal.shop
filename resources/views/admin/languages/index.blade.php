@extends('layouts.adminmaster')
@section('title','Languages| Listing')
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

<?php //echo "<pre>";print_r($languages);die;
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
                <h4 class="page-title">Languages</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.languages.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add New Languages</a>
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
        <!-- ============================================================== -->
        <!-- Three charts -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="box-title">Languages List</h3>
                        </div>
                        <div class="col-md-4">
                            <div class="d-md-flex">
                                <ol class="breadcrumb ms-auto">
                                    <li><a href="#" class="fw-normal"></a></li>
                                </ol>
                                <form action="{{ route('admin.languages.indexsearch') }}" method="POST" class="">
                                    {{ csrf_field() }}
                                    <div class="input-group mb-3">
                                        <input type="text"  name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>
                                    <!-- <input type="text" name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" />
                                    <input type="submit" name="search" value="Go" /> -->

                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table id="tbl_results" class="table text-nowrap">
                            <thead>
                                <tr>
                                    <!-- <th class="border-top-0">#</th> -->
                                    <th class="border-top-0">Name</th>
                                    <th class="border-top-0">Code</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; $datacount = count($languages); if ($datacount>0){ ?>
                                @foreach($languages as $row)
                                <tr>
                                    <?php //echo "<pre>"; print_r($row);
                                    ?>
                                    <!-- <td><?php //echo ++$i; ?></td> -->
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->code }}</td>
                                    <td>{{ $row->status }}</td>
                                    <td>
                                    @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/languages-edit", ['id' => @$row->id]); ?>">EDIT</a>
                                    @endif
                                    @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/languages-delete", ['id' => @$row->id]); ?>">DELETE</a>
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
                        {{ $languages->appends($query)->links() }}
                        @else
                        {!! $languages->links() !!}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        function fetch_customer_data(query = '') {
            info = [];
            info[0] = 'name';
            info[1] = 'code';
            info[2] = 'status';
            $.ajax({
                url: baseUrl + '/admin/get-search-data',
                method: 'GET',
                data: {
                    query: query,
                    tbl_name: "languages",
                    editurl: "/admin/languages-edit",
                    deleteurl: "/admin/languages-delete",
                    info: info,
                },
                dataType: 'json',
                success: function(data) {
                    $('tbody').html(data.table_data);
                }
            })
        }

        // $(document).on('keyup', '#search', function() {
        //     var query = $(this).val();
        //     console.log(query);
        //     fetch_customer_data(query);
        // });
    });
</script>
<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });
</script>

@endsection
