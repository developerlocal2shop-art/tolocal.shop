@extends('layouts.client.vendor')
@section('title','Competition Participants | List')
@section('content')
@php
$user = auth()->guard('web')->user();
$today = date('Y-m-d H:i:s');
@endphp
<style>

    @if ($isWinner > 0)
        .prize_details{
            display: none !important;
        }
    @endif

    .alluser_vendor_autowinner_btn{
        margin:0px 5px;
    }

    input.form-control {
        width: 70%;
        height: 30px;
    }
    #user_answer_data .answer_box strong {
        color: #0f172a;
        font-weight: 600;
        float: left;
        margin: 0px 5px;
    }
    .modal-content {
        background-color: #fff;
        color: #fff;
        padding: 5px 4px;
        margin: 80px 0px;
    }
    .modal-header {
        padding: 5px;
    }
    .modal-xl {
        max-width: 1040px;
    }
    #user_answer_data {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: -15px;
        padding: 4px;
    }

    #user_answer_data h2 {
        width: 100%;
        text-align: center;
        font-size: 30px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e2e8f0;
    }

    #user_answer_data .answer_box {
        width: calc(50% - 10px) !important;
        background: #ffffff;
        border: 1px solid #e5e7eb !important;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        margin: 0 auto !important;
    }

    #user_answer_data .answer_box:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        border-color: #2563eb;
    }

    #user_answer_data .answer_box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: #2563eb;
    }

    #user_answer_data .answer_box p {
        margin-bottom: 12px;
        font-size: 15px;
        line-height: 1.7;
        color: #475569;
        word-break: break-word;
    }

    #user_answer_data .answer_box strong {
        color: #0f172a;
        font-weight: 600;
    }

    #user_answer_data .prize_details {
        width: 50% !important;
        background: #ffffff;
        border-radius: 14px;
        padding: 30px;
        margin-top: 10px !important;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        margin: 0 auto;
    }

    #user_answer_data .prize_details label {
        display: block;
        margin-bottom: 8px;
        margin-top: 18px;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
    }

    #user_answer_data .prize_details .form-control {
        width: 100%;
        height: 35px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 5px 10px;
        font-size: 15px;
        color: #334155;
        transition: all 0.3s ease;
        box-shadow: none;
    }

    #user_answer_data .prize_details .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        outline: none;
    }

    #user_answer_data .prize_details input[type="submit"] {
        background: #2563eb !important;
        color: #ffffff !important;
        border: none !important;
        font-size: 16px;
        font-weight: 600;
        margin-top: 25px;
        transition: all 0.3s ease;
    }

    #user_answer_data .prize_details input[type="submit"]:hover {
        background: #1d4ed8 !important;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {

        #user_answer_data .answer_box,
        #user_answer_data .prize_details {
            width: 100%;
        }

        #user_answer_data h2 {
            font-size: 24px;
        }
    }

    /* =========================
    Participants List Design
    ========================= */

    .participants_list {
        background: #ffffff;
        border-radius: 14px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        border: 1px solid #edf0f5;
    }

    /* Title */
    .participants_list .box-title {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        line-height: 1.4;
    }

    /* Main Table */
    .participants_list .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0 14px;
    }

    .participants_list .table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 14px;
        font-weight: 700;
        padding: 16px 18px;
        border: none !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .participants_list .table tbody tr {
        background: #ffffff;
        transition: all 0.25s ease;
        box-shadow: 0 2px 12px rgba(15, 23, 42, 0.05);
    }

    .participants_list .table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    }

    .participants_list .table tbody td {
        padding: 6px;
        vertical-align: top;
        border-top: none !important;
        border-bottom: none !important;
        color: #334155;
        font-size: 15px;
    }

    /* Serial Number */
    .participants_list .table tbody td:first-child {
        width: 60px;
        font-weight: 700;
        color: #0f172a;
    }

    /* Question Text */
    .participants_list .table tbody td b {
        display: inline-block;
        font-size: 18px;
        color: #0f172a;
        line-height: 1.6;
        margin-bottom: 15px;
        font-weight: 700;
    }

    /* Company Name */
    .participants_list .table tbody td b[style] {
        color: #ef4444 !important;
        font-size: 16px;
        font-weight: 700;
    }

    /* Nested Answer Table */
    .participants_list .table .table {
        margin-top: 2px;
        background: #f8fafc;
        border-radius: 10px;
        overflow: hidden;
        border-spacing: 0;
        box-shadow: none;
    }

    .participants_list .table .table thead th {
        background: #eef2f7;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        padding: 14px 16px;
        border: none !important;
    }

    .participants_list .table .table tbody tr {
        background: transparent;
        box-shadow: none;
        transform: none !important;
    }

    .participants_list .table .table tbody td {
        padding: 14px 16px;
        border-top: 1px solid #e2e8f0 !important;
        font-size: 14px;
        color: #334155;
    }

    /* Created Date */
    .participants_list td:nth-child(4) {
        color: #64748b;
        font-weight: 500;
        white-space: nowrap;
    }

    /* Action Buttons */
    .participants_list .btn-xs {
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 8px;
        margin-right: 6px;
        border: none;
        transition: all 0.25s ease;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .participants_list .btn-primary {
        background: #2563eb;
        color: #fff;
    }

    .participants_list .btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .participants_list .btn-danger {
        background: #ef4444;
        color: #fff;
    }

    .participants_list .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    /* Pagination */
    .participants_list .cust-page {
        margin-top: 25px;
    }

    /* Responsive */
    @media (max-width: 768px) {

        .participants_list {
            padding: 15px;
        }

        .participants_list .box-title {
            font-size: 20px;
        }

        .participants_list .table thead {
            display: none;
        }

        .participants_list .table tbody tr {
            display: block;
            margin-bottom: 18px;
            border-radius: 12px;
            overflow: hidden;
        }

        .participants_list .table tbody td {
            display: block;
            width: 100%;
            padding: 12px 15px;
        }

        .participants_list .table tbody td::before {
            content: attr(data-label);
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .participants_list .table tbody td b {
            font-size: 16px;
        }

        .participants_list .btn-xs {
            margin-top: 5px;
        }
    }

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
    hr {
        margin: 1px 0;
    }
    .toggle.btn {
        min-height: 26px !important;
    }

    .btn-danger {
        color: #fff !important;
    }

</style>
<div class="AJloader" id="AJloader"></div>

<div class="modal fade listing_detail_gallery" id="autoWinnerModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Auto Winner Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="autoWinnerContent"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade listing_detail_gallery" id="user_answer" role="dialog"  >
    <div class="modal-dialog modal-xl modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" style="margin: 0 auto;">
                    Winner
                    @if ($isWinner > 0)
                    @else
                    Selection
                    @endif
                    </h5>

            </div>
            <div class="modal-body">
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">×</font></font></span>
                </button>
                <div class="row">
                    <div class="col-md-12">
                        <form method="post" action="/">
                            {{ csrf_field() }}
                            <div class="row" id="user_answer_data">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="sub_header_in sticky_header">
    <div class="container">
        <h1>Competition Participants</h1>
        <x-client.company_account_menu />
    </div>
</div>

<main>
    <div class="container margin_60_35">

        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif

        <div class="row">
            <div class="col-sm-12">

                <div class="white-box participants_list">

                    <a href="{{ url('export-answer/'.$advertisementDetails->id) }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white" style="margin: 0px 5px;">
                        Export Excel
                    </a>

                    @if($advertisementDetails->end_date <= $today)
                           @php
                                $expiredMsg = "This competition has expired.";
                            @endphp

                            <button type="button"
                                class="btn btn-info d-none d-md-block pull-right ms-3 waves-effect waves-light text-white"
                                onclick="alert('{{ $expiredMsg }}')">
                                🎉
                                {{ $isWinner > 0 ? '' : 'Select ' }}Winner
                            </button>

                            <button type="button" style="margin: 0px 5px;"
                                class="btn btn-info d-none d-md-block pull-right ms-3 waves-effect waves-light text-white"
                                onclick="alert('{{ $expiredMsg }}')">
                                🎉 All Winner
                            </button>
                        @else
                            <button type="button" data-queid="{{ $advertisementDetails->id }}"   class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white user_answer_btn">
                            🎉
                            @if ($isWinner > 0)
                            @else
                                Select
                            @endif
                            Winner
                            </button>
                            <button type="button" data-queid="{{ $advertisementDetails->id }}"   class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white alluser_vendor_autowinner_btn">
                            🎉
                            All Winner
                            </button>
                    @endif


                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="box-title" style="color: #02a107;">
                                Competition : <span style="font-size: 20px; color: #5f5310;">{{ $advertisementDetails->heading ?? '' }} </span>
                                <span style="font-size: 14px; color: #caad05;">({{ \Carbon\Carbon::parse($advertisementDetails->end_date)->format('d M Y') }})</span>


                                @if($advertisementDetails->end_date <= $today)
                                    <span class="badge bg-danger text-white">Expired</span>
                                    @else
                                    <span class="badge bg-success text-white">Active</span>
                                @endif
                            </h4>
                        </div>
                    </div>
                    <input type="hidden" id="competition_id" value="{{ $advertisementDetails->id }}" >

                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">SN</th>
                                    <th class="border-top-0">Question</th>
                                    <th class="border-top-0">Created</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach($advertisementDetails->questions as $row)

                                @php
                                    $questionsAns = Helper::QuestionsAnsByVendorId($row);

                                    if($row->company_id != auth()->user()->directory_id){
                                        continue;
                                    }
                                @endphp

                                <tr>
                                    <td>
                                       {{ $i++ }}.
                                    </td>
                                    <td>
                                       <b>{{ $row->question_text }}</b>
                                       @if ($questionsAns->answers->count() > 0)

                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="border-top-0" style="color: #099b21; font-size: 16px;">Winner</th>
                                                    <th class="border-top-0">Customer</th>
                                                    <th class="border-top-0">Answer</th>
                                                    <th class="border-top-0">Created</th>
                                                    <th class="border-top-0">Auto winner</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                   @php
                                                        foreach ($questionsAns->answers as $answers_val) {
                                                            $getUser = Helper::getUserById($answers_val->customer_id);
                                                            $formattedDate = \Carbon\Carbon::parse($answers_val->created_at)->format('d M Y');
                                                   @endphp
                                                    <tr>
                                                        <td>

                                                            @if ($answers_val->is_correct == 1)
                                                                <input type="hidden" name="winner_answer_ids" class="cb-element" value="{!! $answers_val->id !!}" />
                                                                <span class="badge rounded-pill bg-success px-3 py-2" style="color: #fff;">
                                                                    <i class="fas fa-trophy"></i> Winner Already Selected
                                                                </span>
                                                            @else
                                                                <div class="form-check">
                                                                    <input type="checkbox" name="answer_ids" class="cb-element" value="{!! $answers_val->id !!}" />
                                                                    <label class="form-check-label" for="winner{{ $answers_val->id }}">
                                                                        <i class="fas fa-award text-warning me-1"></i>
                                                                        Select Winner
                                                                    </label>
                                                                </div>
                                                            @endif

                                                        </td>
                                                        <td>{{ $getUser->name ?? 'Unknown user' }} <span class='user-email'>({{ $getUser->email ?? '' }})</span></td>
                                                        <td>{{ $answers_val->answer }}</td>
                                                        <td>{{ $formattedDate }}</td>
                                                        <td>
                                                            @if ($answers_val->auto_reply_status == 1)
                                                                <button type="button" class="btn btn-sm btn-primary auto-winner-btn"
                                                                    data-reply-description="{{ $answers_val->reply_description ?? '' }}"
                                                                    data-auto-reply-response="{{ $answers_val->auto_reply_response ?? '' }}"
                                                                    data-prize-code="{{ $answers_val->prize_code ?? '' }}"
                                                                    data-toggle="modal"
                                                                    data-target="#autoWinnerModal">
                                                                    View Auto Winner Details
                                                                </button>
                                                            @else
                                                            N/A
                                                            @endif

                                                        </td>
                                                    </tr>
                                                    @php

                                                        }
                                                    @endphp
                                            </tbody>
                                        </table>

                                       @endif
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}</td>

                                </tr>

                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        {{-- @if(isset($query))
                        {{ $advertisementDetails->questions->appends($query)->links() }}
                        @else
                        {!! $advertisementDetails->questions->links() !!}
                        @endif --}}

                    </div>
                </div>

            </div>
        </div>


    </div>

</main>

<script type="text/javascript">

    var baseUrl = "{{URL::to('/')}}";

    $(document).on('click', '.auto-winner-btn', function () {
        let replyDescription = $(this).data('reply-description');
        let autoReplyResponse = $(this).data('auto-reply-response');
        let prizeCode = $(this).data('prize-code');

        let html = `
            <div class="auto-winner-details" style="padding: 15px;">
                <div style="margin-bottom: 15px; padding: 12px; background: #f8f9fa; border-left: 4px solid #2563eb; border-radius: 4px;">
                    <strong style="color: #1e293b;">Reply Customer Message:</strong>
                    <p style="margin: 8px 0 0 0; color: #475569;">${replyDescription || 'N/A'}</p>
                </div>
                <div style="margin-bottom: 15px; padding: 12px; background: #f8f9fa; border-left: 4px solid #2563eb; border-radius: 4px;">
                    <strong style="color: #1e293b;">Auto Reply Response:</strong>
                    <p style="margin: 8px 0 0 0; color: #475569;">${autoReplyResponse || 'N/A'}</p>
                </div>
                <div style="margin-bottom: 15px; padding: 12px; background: #f8f9fa; border-left: 4px solid #2563eb; border-radius: 4px;">
                    <strong style="color: #1e293b;">Prize Code:</strong>
                    <p style="margin: 8px 0 0 0; color: #475569;">${prizeCode || 'N/A'}</p>
                </div>
            </div>
        `;

        $('#autoWinnerContent').html(html);
        $('#autoWinnerModal').modal('show');
    });

    $(document).on('change', '.prize_code, .exp_date, .prize_description', function () {

        @if(strtotime($advertisementDetails->end_date) <= strtotime($today))
            toastr.error('This competition has expired. You cannot update winner details now!');
            return false;
        @endif


        let question_id = $(this).data('question-id');
        let answer_id = $(this).data('answer-id');

        let competition_id = $(this).data('competition_id');
        let customer_id = $(this).data('customer_id');
        let vendor_id = $(this).data('vendor_id');

        let prize_code = $.trim($('#prize_code_' + question_id).val());
        let exp_date = $.trim($('#exp_date_' + question_id).val());
        let prize_description = $.trim($('#prize_description_' +question_id).val());

        if (!prize_code || !exp_date || !prize_description) {
            toastr.error('Please fill all the fields');
            return false;
        }

         if (!confirm('Are you sure you want to update winner details?')) {
            return false;
        }

        $.ajax({
            url: "{{ route('update.question.winner.data') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                question_id: question_id,
                answer_id: answer_id,
                prize_code: prize_code,
                exp_date: exp_date,
                prize_description: prize_description,
                competition_id: competition_id,
                customer_id: customer_id,
                vendor_id: vendor_id
            },

            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    setTimeout(function () {
                        location.reload();
                    }, 5000);
                } else {
                    toastr.error('Something went wrong');
                }
            }
        });

    });

    $(".user_answer_btn").click(function() {

        const allids = $("input[name='winner_answer_ids']")
            .map(function () {
                return this.value;
            }).get();

        if (!allids.length) {
            toastr.error('Please select at least one winner answer!');
            return false;
        }

        var exp_date = $('#expiry-date').val();
        $('.prize_expiry').val(exp_date);

        var token = "{{ csrf_token() }}";
            $.ajax({
                url: baseUrl + '/get-selected-answersid'
                , type: "post"
                , data: {
                    answer_ids: allids, _token: token
                }
                , success: function(response) {
                    console.log('responce2 - ',response);
                    let html = '<h2 style="text-align: center;">{{ $advertisementDetails->heading ?? '' }}</h2>';

                    $.each(response.answer_data, function(index, item) {

                        html += `
                                <div class="answer_box" style="border:1px solid #ddd; padding:10px; margin:5px; width:48%;">
                                    <input type="hidden" name="answer_ids[]" id="answer_id_${item.id}" value="${item.id}">
                                    <p><strong>Customer:</strong> ${item.customer.name} ${item.customer.last_name} (${item.customer.email})</p>
                                    <p><strong>Question:</strong> ${item.question.question_text}</p>
                                    <p><strong>Answer:</strong> ${item.answer}</p>

                                    @if ($isWinner > 0)
                                        <p>
                                            <strong>Discount:</strong>
                                            ${
                                                item.advertisement?.price
                                                ? item.advertisement.price
                                                : `<input type="text"
                                                        class="form-control discount"
                                                        value="{{ $advertisementDetails->price }}"
                                                        name="discount"
                                                        id="discount_${item.question.id}"
                                                        data-question-id="${item.question.id}"
                                                        data-answer-id="${item.id}"
                                                        data-competition_id="${item.advertisement_id}"
                                                        data-customer_id="${item.customer_id}"
                                                        data-vendor_id="${item.vendor_id}"
                                                        required>`
                                            }
                                        </p>

                                        <p>
                                            <strong>Prize code:</strong>
                                            ${
                                                item.prize_code
                                                ? item.prize_code
                                                : `<input type="text"
                                                        class="form-control prize_code"
                                                        name="prize_code"
                                                        id="prize_code_${item.question.id}"
                                                        data-question-id="${item.question.id}"
                                                        data-answer-id="${item.id}"
                                                        data-competition_id="${item.advertisement_id}"
                                                        data-customer_id="${item.customer_id}"
                                                        data-vendor_id="${item.vendor_id}"
                                                        required>`
                                            }
                                        </p>

                                        <p>
                                            <strong>Expiry date:</strong>
                                            ${
                                                item.exp_date
                                                ? item.exp_date
                                                : `<input type="date"
                                                        class="form-control exp_date"
                                                        name="exp_date"
                                                        id="exp_date_${item.question.id}"
                                                        data-question-id="${item.question.id}"
                                                        data-answer-id="${item.id}"
                                                        data-competition_id="${item.advertisement_id}"
                                                        data-customer_id="${item.customer_id}"
                                                        data-vendor_id="${item.vendor_id}"
                                                        required>`
                                            }
                                        </p>

                                        <p>
                                            <strong>Description:</strong>
                                            ${
                                                item.prize_description
                                                ? item.prize_description
                                                : `<input type="text"
                                                        class="form-control prize_description"
                                                        name="prize_description"
                                                        id="prize_description_${item.question.id}"
                                                        data-question-id="${item.question.id}"
                                                        data-answer-id="${item.id}"
                                                        data-competition_id="${item.advertisement_id}"
                                                        data-customer_id="${item.customer_id}"
                                                        data-vendor_id="${item.vendor_id}"
                                                        required>`
                                            }
                                        </p>

                                    @endif

                                </div>
                                <input type="hidden" name="question_id[]" value="${item.question.id}">
                        `;
                    });

                    html += `
                        <hr>
                        <div class="form-group prize_details" >
                            <label for="exp_date">Expiry date:</label>
                            <input type="date" class="form-control" name="exp_date" id="exp_date" required>

                            <label for="discount">Discount:</label>
                            <input type="text" class="form-control discount" value="{{ $advertisementDetails->price }}" name="discount" id="discount" required>
                            <label for="prize_code">Prize code:</label>
                            <input type="text" class="form-control" name="prize_code" id="prize_code" required>
                            <label for="description">Description:</label>
                            <input type="text" class="form-control" name="description" id="description" required>
                            <a class="form-control" id="select_winner_btn" style="margin-top:10px; background:#2563eb; color:#fff; border:none; cursor:pointer; text-align: center; font-weight: 600;">
                                Select Winner
                            </a>
                        </div>
                    `;

                    $('#user_answer_data').html(html);

                }
            });

        $('#user_answer').modal('show');
    });

    $(".modal_header_close_btn").click(function() {
        $('#user_answer').modal('hide');
        $('#view_winner_user').modal('hide');
    });

    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });


    $(document).on('click', '#select_winner_btn', function (e) {

         e.preventDefault();

        let answerIds = [];
        $('input[name="answer_ids[]"]').each(function () {
            answerIds.push($(this).val());
        });

        var exp_date = $('#exp_date').val();
        var discount = $('.discount').val().trim();
        var prize_code = $('#prize_code').val();
        var prize_description = $('#description').val();
        var competition_id = $('#competition_id').val();

        if(exp_date == ''){
            toastr.error('Expiry date is required');
            return false;
        }
        if(discount == ''){
            toastr.error('Discount is required');
            return false;
        }
        if(prize_code == ''){
            toastr.error('Prize code is required');
            return false;
        }
        if(prize_description == ''){
            toastr.error('Prize description is required');
            return false;
        }
        let result = confirm("Are you sure you want to continue?");
        if (result) {
            $.ajax({
                url: baseUrl + '/company/choose-competition-winner',
                method: 'POST',
                data: {
                    answerIds: answerIds,
                    exp_date: exp_date,
                    discount: discount,
                    prize_code: prize_code,
                    prize_description: prize_description,
                    competition_id: competition_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success){
                        toastr.success(response.success);
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    }else{
                        toastr.error(response.error);
                    }
                }
            });
        }
    });


    $(document).on('click', '.alluser_vendor_autowinner_btn', function (e) {

         e.preventDefault();

         $('#AJloader').show();

        let answerIds = [];
        $('input[name="answer_ids"]').each(function () {
            answerIds.push($(this).val());
        });
        var competition_id = $('#competition_id').val();

        let result = confirm("Are you sure you want to continue?");
        if (result) {
            $.ajax({
                url: baseUrl + '/company/choose-auto-alluser-competition-winner',
                method: 'POST',
                data: {
                    answerIds: answerIds,
                    competition_id: competition_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#AJloader').hide();
                    if(response.success){
                        toastr.success(response.success);
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    }else{
                        toastr.error(response.error);
                    }
                }
            });
        }else{
            $('#AJloader').hide();
        }
    });



</script>

@endsection
