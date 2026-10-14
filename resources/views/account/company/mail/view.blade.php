@extends('layouts.client.vendor')
@section('title','View Mail')
@section('content')

<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Mail</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<main>
    <div class="container margin_60_35">
        <div class="row">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <div class="panel-heading cust-tab-head">
                                    <h4 class="box-title">Mail View</h4>
                                </div>
                            </div>
                        </div>

                        <table>
                            <tr>
                                <td> To: {{ $mailData->to_email }}</td>

                            </tr>
                            <tr>
                                <td> Subject: <strong>{{ $mailData->subject }}</strong></td>
                            </tr>
                            <tr>
                                <td> </td>
                            </tr>
                            <tr>
                                <td> <?php echo $mailData->content; ?> </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
@endsection
