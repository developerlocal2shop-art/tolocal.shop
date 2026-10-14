@extends('layouts.client.blog')
@section('title','Shopping Card Offer')
@section('content')
<style>

    .reply-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.55rem 1.15rem;
        border-radius: 999px;
        font-size: 0.92rem;
        font-weight: 600;
        color: #ffffff;
        background: linear-gradient(135deg, #1b6ec2 0%, #0a4d9d 100%);
        border: 1px solid #0d4a97;
        transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
        cursor: pointer;
        margin-top: 8px;
    }
    .reply-btn:hover,
    .reply-btn:focus {
        background: linear-gradient(135deg, #144f97 0%, #0a3c7c 100%);
        box-shadow: 0 8px 20px rgba(15, 91, 176, 0.18);
        transform: translateY(-1px);
        color: #ffffff;
        outline: none;
    }
    #replyModalLabel {
        font-size: 16px;
    }
    .modal-content {
        color: #575757;
        background: #fff !important;
    }
    .seller_heading_box {
        border-left: 4px solid #eca262;
        padding-left: 12px;
        margin-bottom: 15px;
    }

    .seller_heading {
        font-size: 18px;
        font-weight: 600;
        color: #eca262;
        margin: 0;
    }
    #replyModal {
        margin: 50px 0px;
    }
    .user_answer_questionnaire textarea.form-control {
        border-radius: 8px;
        min-height: 40px;
    }

    .user_answer_questionnaire label {
        font-weight: 500;
        margin-bottom: 8px;
        display: block;
    }

    .user_answer_questionnaire {
        padding: 10px 0;
    }

    /* Header */
    .user_answer_questionnaire .filter_sub_blue_header {
        background: linear-gradient(90deg, #007bff, #0056b3);
        padding: 18px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .user_answer_questionnaire .filter_sub_blue_header h1 {
        color: #fff;
        font-size: 22px;
        margin: 0;
        font-weight: 600;
    }

    .user_answer_questionnaire .status_text {
        display: block;
        font-size: 14px;
        color: #ffe082;
        margin-top: 5px;
    }

    /* Competition Info */
    .user_answer_questionnaire .competition_info {
        background: #fff;
        padding: 15px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .user_answer_questionnaire .competition_info h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .user_answer_questionnaire .competition_info span {
        font-size: 14px;
        color: #c39922;
    }

    /* Question Card */
    .user_answer_questionnaire .question_card {
        background: #fff;
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 15px;
        box-shadow: 0 5px 18px rgba(0,0,0,0.04);
        border-left: 4px solid #007bff;
    }

    /* Question title */
    .user_answer_questionnaire .question_title {
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }

    /* Textarea */
    .user_answer_questionnaire textarea.form-control {
        border-radius: 8px;
        font-size: 14px;
        padding: 10px;
        border: 1px solid #ddd;
        transition: 0.3s;
    }

    .user_answer_questionnaire textarea.form-control:focus {
        border-color: #007bff;
        box-shadow: none;
    }

    /* Answered state */
    .user_answer_questionnaire .id_answered {
        background: #f0f8ff;
        border-color: #007bff;
    }

    /* Status badges */
    .user_answer_questionnaire .answer_status {
        margin-top: 8px;
        font-size: 13px;
    }

    .user_answer_questionnaire .badge_status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        margin-right: 5px;
        font-weight: 500;
    }

    .badge_status.correct {
        background: #e6f4ea;
        color: #28a745;
    }

    .badge_status.wrong {
        background: #fdecea;
        color: #dc3545;
    }

    .badge_status.pending {
        background: #fff3cd;
        color: #856404;
    }

    /* Submit button */
    .user_answer_questionnaire .answers_btn {
        background: linear-gradient(90deg, #28a745, #218838);
        border: none;
        padding: 10px 25px;
        font-size: 15px;
        border-radius: 6px;
        margin-top: 10px;
    }

    .user_answer_questionnaire .answers_btn:hover {
        background: linear-gradient(90deg, #218838, #1e7e34);
    }
    .profile_data {
        margin: 0px 20px;
    }
    .disabled-field {
        background: #f7f7f7;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .sub_header_in.sticky_header {
        margin: 84px 0px 20px 0px !important;
        padding: 6px 4px 10px 0px;
    }

    .filter_sub_blue_header.newClass {
        margin: -9px -15px !important;
    }
    .answer-btn {
      display: inline-flex;
      align-items: center;
      padding: 10px 20px;
      border-radius: 25px;
      color: #fff;
      font-weight: bold;
      margin: 10px 0;
      width: fit-content;
    }
    .correct {
      background-color: #28a745; /* Bootstrap green */
    }
    .wrong {
      background-color: #dc3545; /* Bootstrap red */
    }
    .pending {
      background-color: #dcaa35; /* Bootstrap red */
    }

    .reply-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.55rem 1.15rem;
        border-radius: 999px;
        font-size: 0.92rem;
        font-weight: 600;
        color: #ffffff;
        background: linear-gradient(135deg, #1b6ec2 0%, #0a4d9d 100%);
        border: 1px solid #0d4a97;
        transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
        cursor: pointer;
        margin-top: 8px;
    }
    .reply-btn:hover,
    .reply-btn:focus {
        background: linear-gradient(135deg, #144f97 0%, #0a3c7c 100%);
        box-shadow: 0 8px 20px rgba(15, 91, 176, 0.18);
        transform: translateY(-1px);
        color: #ffffff;
        outline: none;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 0.9rem;
        border-radius: 999px;
        font-size: 0.88rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background: #e9f6ea;
        color: #1f7a2d;
        border: 1px solid #c7e5cc;
    }
    .reply-action-wrapper .reply-btn, .reply-action-wrapper .status-badge {
        position: absolute;
        margin: -24px -32px;
    }
    .status-badge--win {
        background: #e9f6ea;
        color: #1f7a2d;
        border-color: #b8dfbc;
        margin: 0px 10px;
    }
    .reply-action-wrapper {
        display: flex;
        justify-content: flex-end;
    }
    .form-group.mb-4 {
        position: relative;
    }
    .reply-action-wrapper .reply-btn,
    .reply-action-wrapper .status-badge {
        margin-left: auto;
    }
    .icon-circle2 {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 22px;
        height: 22px;
        border: 2px solid black;
        border-radius: 17%;
        background-color: white;
        color: black;
        font-size: 16px;
        margin-right: 10px;
    }

    .icon-circle2 input[type="checkbox"] {
        appearance: none;
        width: 18px;
        height: 18px;
        /* border: 2px solid #666; */
        border-radius: 2px;
        position: relative;
        background: #fff;
        cursor: default;
    }

        .icon-circle2 input[type="checkbox"]:checked {
        background-color: #10dd30;
        }

        .icon-circle2 input[type="checkbox"]:checked::after {
        content: "";
        position: absolute;
        top: 3px;
        left: 7px;
        width: 4px;
        height: 9px;
        border: solid #fff;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
        }

        .icon-circle2 input[type="checkbox"]:disabled {
        cursor: not-allowed;
        }

    @media screen and (max-width:767px){
        @media (max-width: 768px) {
            .user_que_ans {
                    padding: 8px;
            }
        }
        .status-badge--win {
            position: relative;
        }
        .reply-action-wrapper .reply-btn, .reply-action-wrapper .status-badge {
            position: relative;
            padding: 1px 20px 0px 20px;
            font-size: 12px;
        }
        .filter_sub_blue_header h1 {
            color: #757575 !important;
        }
        .seller_heading span {
            display: block;
        }

    }

    @media screen and (max-width: 1024px) {
        .status-badge--win {
            position: relative;
        }

        .reply-action-wrapper .reply-btn,
        .reply-action-wrapper .status-badge {
            position: relative;
            padding: 1px 20px 0px 20px;
            font-size: 12px;
        }

        .filter_sub_blue_header h1 {
            color: #757575 !important;
        }

        .seller_heading span {
            display: block;
        }
    }

</style>
@php
$defaultCurrency = Helper::defaultCurrencyData();
if(session()->has('currency_code')){
	$currencySymbol = session()->get('currency_code')['currsymbol'];
	$currencycode = session()->get('currency_code')['currcode'];
}else{
	$currencySymbol = $defaultCurrency[0]->symbol;
	$currencycode = $defaultCurrency[0]->code;
}
//dd($shoppingcarddata);
@endphp

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyModalLabel">Reply to shoppingcard offer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="replyForm">
                    <div class="form-group">

                        <input type="hidden" id="offer_id" name="offer_id" >
                        <input type="hidden" id="reply_for" name="reply_for" >
                    </div>
                    <div class="form-group">
                        <label for="reply_to">Reply To</label>
                        <input type="text" id="reply_to" name="reply_to" class="form-control reply_to" readonly>
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="vendor_id" name="vendor_id">
                    </div>
                    {{--
                    <div class="form-group">
                        <label for="action_status">Action Status</label>
                        <select id="action_status" name="action_status" class="form-control">
                            <option value="store">Store</option>
                            <option value="online">Online</option>
                        </select>
                    </div>
                    --}}
                    <div class="form-group">
                        <label for="reply_description">Feedback</label>
                        <textarea id="reply_description" name="reply_description" class="form-control" rows="4" placeholder="Enter your reply"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary send_offer_reply">Send Offer Reply</button>
            </div>
        </div>
    </div>
</div>


<main>
<div class="page-wrapper">
    <div class="container-fluid">

        <div class="row">
            <x-client.account_menu />

            <div class="col-lg-9 user_answer_questionnaire">
                <br>
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h3 class="text-themecolor">Shopping Card Offer</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="row top-info-offer">

                            <div class="col-md-6">
                                <div class="white-box front-offer-info analytics-info">
                                    <h3 class="box-title">Ongoing Offer</h3>
                                    <div class="report-main">
                                        <h2>{{ Helper::numberformat(($ongoingCount > 0) ? $ongoingCount : 0) }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="white-box front-offer-info analytics-info">
                                    <h3 class="box-title">Expired Offer</h3>
                                    <div class="report-main">
                                        <h2>{{ Helper::numberformat(($expiredCount > 0) ? $expiredCount : 0) }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-12 d-flex flex-wrap gap-2 align-items-center">
                                        <form action="{{ route('account.shopping.valid.offer') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center">
                                            <input type="hidden" name="company" value="{{ encrypt($company_id ?? '') }}" >
                                            <div class="form-group mb-0">
                                                <input type="text" name="searchtext" value="{{ $searchtext ?? '' }}" class="form-control" placeholder="Search shopping card offer">
                                            </div>
                                            <div class="form-group mb-0">
                                                <input type="date" name="fromdate" value="{{ $fromDate ?? '' }}" class="form-control" placeholder="From date">
                                            </div>
                                            <div class="form-group mb-0">
                                                <input type="date" name="todate" value="{{ $toDate ?? '' }}" class="form-control" placeholder="To date">
                                            </div>
                                            <div class="form-group mb-0">
                                                <button type="submit" class="btn btn-primary">Filter</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Vendor</th>
                                                <th>Discount</th>
                                                <th>Active Date</th>
                                                <th>Expire Date</th>

                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($shoppingcarddata as $index => $row)
                                            @php
                                              $getDirectoryContact =  Helper::getDirectoryContact($row->directory_link);
                                            @endphp

                                                <tr>
                                                    <td>{{ $shoppingcarddata->firstItem() + $index }}</td>
                                                    <td>{{ $row->title }}</td>
                                                    <td class="notranslate">
                                                        {{ optional($getDirectoryContact)->name ?? $row->directory_link }}
                                                    </td>
                                                    @if ($row->discount_selectedvalue == 'percentagediscount')
                                                        <td>{{ $row->discount_firstvalue }}%</td>
                                                    @else
                                                        <td>{{ $currencySymbol }}{{ $row->discount_firstvalue }}</td>
                                                    @endif

                                                    <td>{{ $row->active_date }}</td>
                                                    <td>{{ $row->expire_date }}</td>
                                                    <td>
                                                        @if($row->expire_date < date('Y-m-d'))
                                                            <span class="badge bg-danger" style="color: #fff;">Expired</span>
                                                        @elseif($row->active_date > date('Y-m-d'))
                                                            <span class="badge bg-info" style="color: #fff;">Upcoming</span>
                                                        @else
                                                            <span class="badge bg-success" style="color: #fff;">Ongoing</span>
                                                        @endif
                                                        @php
                                                            $prize_data = Helper::getOfferPrizeDataId($row->id, $row->directory_link, Auth::id());
                                                            if($prize_data){
                                                        @endphp
                                                                <span class="status-badge status-badge--win">
                                                                    <p>Winning Code: <b>{{ $prize_data->voucher_code ?? '' }}</b>
                                                                    <br>
                                                                    Discount: <b>
                                                                        @if ($prize_data->discount_type == 'percentage')
                                                                            {{ $prize_data->prize_amount }} %
                                                                        @else
                                                                            {{ $currencySymbol }}{{ $prize_data->prize_amount }}
                                                                        @endif
                                                                        </b>
                                                                    <br>
                                                                    Status:
                                                                        <b>
                                                                        @if ($prize_data->status == 1)
                                                                            Used
                                                                        @else
                                                                            Redeem at a <a href="{{ url('account/userprizes#shoppingcard-prizes') }}" target="_blank">Physical Store</a>
                                                                        @endif
                                                                        </b>
                                                                    </p>
                                                                </span>
                                                        @php
                                                            }else{
                                                        @endphp
                                                        <button type="button"
                                                            class="reply-btn"
                                                            data-reply-to="{{ optional($getDirectoryContact)->email ?? $row->directory_link }}"
                                                            data-offer-id="{{ $row->id }}"
                                                            data-vendor-id="{{ $row->directory_link }}"
                                                            data-reply-for="{{ $row->title }}"
                                                            aria-label="Reply for shoppingcard offer {{ $row->title }}">
                                                            Reply
                                                        </button>
                                                        @php } @endphp
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No valid shopping card offers found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    {{ $shoppingcarddata->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
</main>

<script>

$(document).ready(function() {

    $(document).on('click', '#replyModal .close, #replyModal .btn-secondary', function() {
        $('#replyModal').modal('hide');
    });

    $(document).on('click', '.reply-btn', function() {

        //var confirmation = 'Are you sure you want to proceed? This action cannot be undone.';
        //if (!confirm(confirmation)) {
            //return false;
        //}

        var replyTo = $(this).data('reply-to') || '';
        var replyFor = $(this).data('reply-for') || '';
        var offerId = $(this).data('offer-id') || '';
        var vendorId = $(this).data('vendor-id') || '';

        $('#reply_to').val(replyTo);
        $('.reply_to').val(replyTo);
        $('#reply_for').val(replyFor);
        $('.reply_for').text(replyFor);
        $('#offer_id').val(offerId);
        $('#vendor_id').val(vendorId);

        $('#replyModalLabel').html('<b>Reply for : </b> ' + replyFor);
        $('#reply_description').val('');
        $('#replyModal').modal('show');
    });

    $(document).on('click', '.send_offer_reply', function(e) {
        e.preventDefault();

        var button = $(this);
        var offerId = $('#offer_id').val();
        var description = $('#reply_description').val().trim();
        //var actionStatus = $('#action_status').val() || 'store';
        if (!offerId) {
            alert('Please select an offer first.');
            return;
        }
        if (!description) {
            alert('Please enter a description.');
            return;
        }
        button.prop('disabled', true).text('Sending...');
        $.ajax({
            type: 'POST',
            url: '{{ route('account.offer.reply') }}',
            data: {
                _token: '{{ csrf_token() }}',
                offer_id: offerId,
                vendor_id: $('#vendor_id').val(),
                description: description,
                prize_amount: 0,
                voucher_code: '',
                type: 'offer_reply',
                status: 'pending'
            },
            success: function(response) {
                button.prop('disabled', false).text('Send Offer Reply');
                if (window.toastr) {
                    toastr.success(response.message || 'Offer reply saved successfully.');
                    location.reload();
                } else {
                    alert(response.message || 'Offer reply saved successfully.');
                    location.reload();
                }
                $('#replyModal').modal('hide');
                $('#reply_description').val('');
            },
            error: function(xhr) {
                button.prop('disabled', false).text('Send Offer Reply');
                var message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Failed to save your reply.';
                if (window.toastr) {
                    toastr.error(message);
                } else {
                    alert(message);
                }
            }
        });
    });

});
</script>

@endsection
