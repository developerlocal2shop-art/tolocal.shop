@extends('layouts.adminmaster')
@section('title','Mail List')
@section('content')

    <style>
        @media (max-width: 767px) {
            .pull-right{
                margin-top: -30px !important;
            }
            .d-none{
                display: unset !important;
            }

        }
        .toggle{
            border-radius:20rem !important;
            height:10px !important;
        }
        .toggle.btn {
            min-height: 26px !important;
        }
        .mailtable.table td, .mailtable.table th {
            white-space:normal;
            word-break: break-word;
        }

        @media(max-width:991px){
            table.table.mailtable tr th:nth-child(3), table.table.mailtable tr td:nth-child(3)
            {
                white-space: normal;
                word-break: break-word;
            }

            .mailtable.table p {
                word-break: break-word;
                width: 350px;
            }

            table.table.mailtable tr th:nth-child(4), table.table.mailtable tr td:nth-child(4), table.table.mailtable tr th:last-child {
                white-space: nowrap!important;
                width: 150px!important;
            }

            .mailtable.table td, .mailtable.table th {
                white-space: nowrap;
                word-break: break-word;
            }
        }
    </style>
        <div class="page-wrapper">
           <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Mail List</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <div class="d-md-flex">
                            <ol class="breadcrumb ms-auto">
                                <li><a href="#" class="fw-normal"></a></li>
                            </ol>
                            <a href="{{ route('admin.mail.create') }}"
                                class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Send Mail</a>
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
                            <div class="col-md-4">
                                <h3 class="box-title">Mail List</h3>
                            </div>
                            <div class="col-md-8">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>

                                    <form action="{{ route('admin.mail.indexsearch') }}" method="POST" class="">
                                    {{ csrf_field() }}
                                        <div class="input-group mb-3">
                                        <input type="date" name="fromdate" title="From Date" value="{{ $fromDate }}" id="fromdate" class="form-control" placeholder="From date">
                                        <input type="date" name="todate" title="To Date" value="{{ $toDate }}" id="todate" class="form-control" placeholder="To date">
                                            <input type="text"  name="searchtext" id="search" value="{{ $searchtext??'' }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
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
                                <table class="table mailtable">
                                    <thead>
                                        <tr>
                                            <!-- <th class="border-top-0">#</th> -->
                                            <th class="border-top-0" style="width:300px;white-space: normal;word-break: break-word;">Mail to</th>
                                            <th class="border-top-0" style="width:400px;white-space: normal;word-break: break-word;">Subject</th>
                                            <!-- <th class="border-top-0" style="width:400px;white-space: normal;word-break: break-word;">Content</th> -->
                                            <th class="border-top-0" style="width:80px;white-space: normal;word-break: break-word;">Status</th>
                                            <th class="border-top-0" style="width:120px;white-space: normal;word-break: break-word;">Date</th>
                                            <th class="border-top-0" style="width:100px;white-space: normal;word-break: break-word;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=0; $datacount = count($maildata); if ($datacount>0){ foreach ($maildata as $row){ //echo "<pre>"; print_r($row);                                     ?>
                                        <tr>
                                            <!-- <td><?php //echo ++$i ?></td> -->
                                            <td><?php echo $row->to_email ?></td>
                                            <td><?php echo $row->subject ?></td>
                                            <!-- <td><?php //echo $row->content ?></td> -->
                                            <td><?php echo $row->status ?></td>
                                            <td><?php echo $row->send_date ?></td>
                                            <td>
                                                <a class="btn btn-primary btn-xs"  href="<?php echo url("/admin/mail-view",['id' => @$row->id]); ?>" >VIEW</a>
                                                <!-- <a class="btn btn-danger btn-xs show_confirm"  href="<?php //echo url("/admin/mail-view",['id' => @$row->id]); ?>" >DELETE</a> -->
                                            </td>
                                        </tr>
                                    <?php } }else{?>
                                        <tr>
                                            <td align="center" colspan="4">No Data Found</td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                            <div class="d-flex justify-content-center cust-page">
                                @if(isset($query))
                                {{ $maildata->appends($query)->links() }}
                                @else
                                {!! $maildata->links() !!}
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
