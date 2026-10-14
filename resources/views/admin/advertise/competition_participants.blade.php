@extends('layouts.adminmaster')
@section('title','Competition Participants | List')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
<style>

    @if ($isWinner > 0)
        .prize_details{
            /* display: none !important; */
        }
    @endif

    .form-check-input.cb-element {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .form-check-label {
        cursor: pointer;
        color: #495057;
    }

    .badge.bg-success {
        font-size: 13px;
        border-radius: 20px;
        box-shadow: 0 2px 6px rgba(25,135,84,.2);
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
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">×</font></font></span>
                </button>
            </div>
            <div class="modal-body">
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

<div class="page-wrapper">

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Competition Participants</h4>
            </div>
            <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">



            </div>

            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <div class="d-md-flex">
                        <ol class="breadcrumb ms-auto">
                            <li><a href="#" class="fw-normal"></a></li>
                        </ol>

                        <a href="{{ url('admin/export-answer/'.$advertisementDetails->id) }}"
                        class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">
                            Export Excel
                        </a>

                        <button type="button" data-queid="{{ $advertisementDetails->id }}" class=" badge rounded-pill bg-warning px-3 py-2 user_answer_btn" style=" margin: 20px 10px 0px 10px; right: 0; font-size: 14px;" >
                          Select Winner
                        </button>

                        <button type="button" data-queid="{{ $advertisementDetails->id }}"   class="badge rounded-pill bg-success px-3 py-2 admin_winner_list_btn" style=" margin: 20px 10px 0px 10px; right: 0;">
                                <i class="fas fa-trophy"></i> Winners Detail
                        </button>

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

                <div class="white-box participants_list">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="box-title" style="color: #02a107;">
                                Competition : {{ $advertisementDetails->heading ?? '' }} <span style="color:#201e1e;font-size: 15px;">( Valid Until: {{ \Carbon\Carbon::parse($advertisementDetails->end_date)->format('d F Y') }})</span>
                            </h3>
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
                                    {{-- <th class="border-top-0">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach($advertisementDetails->questions as $row)

                                @php
                                    $questionsAns = Helper::QuestionsAnsById($row->id);
                                @endphp

                                <tr>
                                    <td>
                                       {{ $i++ }}.
                                    </td>
                                    <td>
                                       <b>{{ $row->question_text }}</b>
                                       <b style="color:#f33155;">
                                            @php
                                                $getCompanyName = Helper::getDirectoryIdSlug($row->company_id);
                                            @endphp
                                            {{ $getCompanyName->title??'Unknown Company' }}
                                        </b>
                                       @if ($questionsAns->answers->count() > 0)

                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="border-top-0" style="color: #099b21; font-size: 16px;">Winner</th>
                                                    <th class="border-top-0">Customer</th>
                                                    <th class="border-top-0">Answer</th>
                                                    <th class="border-top-0">Created</th>
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
                                                                <span class="badge rounded-pill bg-success px-3 py-2">
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

                                                            @if ($answers_val->is_correct == 1)
                                                            <input type="hidden" name="winner_answer_ids" class="cb-element" value="{!! $answers_val->id !!}" />
                                                            @endif


                                                        </td>
                                                        <td>{{ $getUser->name }} <span class='user-email'>({{ $getUser->email }})</span></td>
                                                        <td>{{ $answers_val->answer }}</td>
                                                        <td>{{ $formattedDate }}</td>
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

</div>

<script type="text/javascript">



    $(".user_answer_btn").click(function() {

        var allids = [];
        $("input:checkbox[name=answer_ids]:checked").each(function(){
            allids.push($(this).val());
        });
        if(allids.length <= 0){
            toastr.error('Please select at least one winner answer!');
            return false;
        }

        var exp_date = $('#expiry-date').val();
        $('.prize_expiry').val(exp_date);

        var token = "{{ csrf_token() }}";
            $.ajax({
                url: baseUrl + '/admin/get-selected-answersid'
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
                                    <p><strong>Discount:</strong> ${item.advertisement.price}</p>

                                </div>
                                <input type="hidden" name="question_id[]" value="${item.question.id}">

                        `;

                        if (item.prize_code) {
                            html += `<input type="hidden" class="item_prize_code" value="${item.prize_code}"> <input type="hidden" class="item_prize_description" value="${item.prize_description}"> <input type="hidden" class="item_expiry_date" value="${item.exp_date}">  `;
                        }
                    });

                    html += `
                        <hr>
                        <div class="form-group prize_details" >
                            <input type="hidden" class="discount" name="discount" id="discount" value="{{ $advertisementDetails->price ?? '' }}" >
                            <label for="exp_date">Expiry date:</label>
                            <input type="date"
                            class="form-control"
                            name="exp_date"
                            id="exp_date"
                            value="{{ !empty($advertisementDetails->end_date) ? date('Y-m-d', strtotime($advertisementDetails->end_date)) : '' }}"
                            required>
                            <label for="description">Description:</label>
                            <input type="text" class="form-control" name="description" id="description" required>
                            <a class="form-control" id="select_winner_btn" style="margin-top:10px; background:#2563eb; color:#fff; border:none; cursor:pointer; text-align: center; font-weight: 600;">
                                    Select Winner
                            </a>
                        </div>
                    `;

                    $('#user_answer_data').html(html);

                    var item_prize_code = $('.item_prize_code').val();
                    var item_prize_description = $('.item_prize_description').val();
                    var item_expiry_date = $('.item_expiry_date').val();

                    $('#prize_code').val(item_prize_code);
                    $('#description').val(item_prize_description);
                    $('#exp_date').val(item_expiry_date.split(' ')[0]);

                    @if ($isWinner > 0)
                        $('#prize_code').prop('readonly', true);
                    @endif

                }
            });

        $('#user_answer').modal('show');
    });

    $(".admin_winner_list_btn").click(function() {

        const allids = $("input[name='winner_answer_ids']")
            .map(function () {
                return this.value;
            }).get();

        if (!allids.length) {
            toastr.error('Please select at least one winner answer!');
            return false;
        }

        var token = "{{ csrf_token() }}";
            $.ajax({
                url: baseUrl + '/admin/get-selected-answersid'
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
                                       <p><strong>Prize code:</strong> ${item.prize_code}</p>
                                       <p><strong>Discount:</strong> ${item.advertisement.price}</p>
                                       <p><strong>Expiry date:</strong> ${item.exp_date}</p>
                                       <p><strong>Description:</strong> ${item.prize_description}</p>
                                    @endif
                                </div>
                                <input type="hidden" name="question_id[]" value="${item.question.id}">

                        `;

                        if (item.prize_code) {
                            html += `<input type="hidden" class="item_prize_code" value="${item.prize_code}"> <input type="hidden" class="item_prize_description" value="${item.prize_description}"> <input type="hidden" class="item_expiry_date" value="${item.exp_date}">  `;
                        }
                    });

                    $('#user_answer_data').html(html);

                    var item_prize_code = $('.item_prize_code').val();
                    var item_prize_description = $('.item_prize_description').val();
                    var item_expiry_date = $('.item_expiry_date').first().val();

                    $('#prize_code').val(item_prize_code);
                    $('#description').val(item_prize_description);
                    $('#exp_date').val(item_expiry_date.split(' ')[0]);

                    @if ($isWinner > 0)
                        $('#prize_code').prop('readonly', true);
                    @endif

                }
            });

        $('#user_answer').modal('show');
    });

    $(".modal_header_close_btn").click(function() {
        $('#user_answer').modal('hide');
        $('#view_winner_user').modal('hide');
    });

    $(".close").click(function() {
        $('#user_answer').modal('hide');
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
        var prize_description = $('#description').val();
        var competition_id = $('#competition_id').val();

        if(exp_date == ''){
            toastr.error('Expiry date is required');
            return false;
        }
        if(prize_description == ''){
            toastr.error('Prize description is required');
            return false;
        }
        let result = confirm("Are you sure you want to continue?");
        if (result) {
            $.ajax({
                url: baseUrl + '/admin/choose-competition-winner',
                method: 'POST',
                data: {
                    answerIds: answerIds,
                    exp_date: exp_date,
                    discount: discount,
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





</script>

@endsection
