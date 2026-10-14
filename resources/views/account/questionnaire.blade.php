@extends('layouts.client.blog')
@section('title','Member Account')
@section('content')
<style>
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
        margin: -44px -32px;
    }
    .status-badge--win {
        background: #e9f6ea;
        color: #1f7a2d;
        border-color: #b8dfbc;
        position: absolute;
        margin: -20px -20px;
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
<!-- /sub_header -->
<!-- Main page -->
@php

$defaultCurrency = Helper::defaultCurrencyData();
if(session()->has('currency_code')){
	$currencySymbol = session()->get('currency_code')['currsymbol'];
	$currencycode = session()->get('currency_code')['currcode'];
}else{
	$currencySymbol = $defaultCurrency[0]->symbol;
	$currencycode = $defaultCurrency[0]->code;
}

@endphp
<main>
    <div class="AJloader" id="AJloader"></div>
    <div class="container-fluid">
        <div class="row">
            <x-client.account_menu />

            <div class="col-lg-9 user_answer_questionnaire">

                <div class="col-lg-12">

                    <div class="sub_header_in sticky_header filter_sub_blue_header">
                        <div class="container">

                            <h1>Answer The Question</h1>
                            <span style="font-size: 14px;color: #fff;text-align: right;float: right;margin: -24px 0px;">
                                    Go back to front list to <a href="{{ url('competitions') }}" target="_blank" style="color: #242322;font-size: 16px;font-weight: 600;">view all competitions </a> and participate in them.
                            </span>

                            @if($is_competitionWinners == 0)
                                <span style="font-size: 17px;color: #201e1e;font-weight: 500;">
                                    Evaluation in progress
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-12">

                        @if(Session::has('error'))
                            <div class="alert alert-danger">
                                {{ Session::get('error') }}
                                @php Session::forget('error'); @endphp
                            </div>
                        @endif

                        @if(Session::has('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                                @php Session::forget('success'); @endphp
                            </div>
                        @endif

                    </div>

                    <div class="col-lg-11">


                        @if($data)

                            <form action="{{ route('advertisement.answers.store', $data->id) }}"
                                class="profile_data"
                                method="POST">

                                @csrf

                                @if(count($data->questions) > 0)

                                    <h4>
                                        {{ Str::words($data->heading, 6, '...') }}
                                        <span style="font-size: 15px; color:#c39922;">
                                            Owner - Admin  <span style="color:#201e1e;"><br>(Competition Valid Until: {{ \Carbon\Carbon::parse($data->end_date)->format('d F Y') }})</span>
                                        </span>
                                    </h4>
                                    @php
                                        $groupedQuestions = $data->questions->where('advertisement_id', $advertisementId)->groupBy('company_id');
                                        $questionNumber = 1;
                                    @endphp

                                    @foreach ($groupedQuestions as $companyId => $questions)
                                        @php
                                            $companyname = Helper::getDirectoryIdSlug($companyId);
                                            $getVendorCompetitionById = Helper::getVendorCompetitionById($companyId, $advertisementId);

                                            $IsVendorComptStatusActive = Helper::IsVendorCompetitionStatusActive($advertisementId, $companyId);

                                            $competitionDiscount = $data->price;
                                            $vendorDiscount = optional($getVendorCompetitionById)->discount_amount;
                                            if(empty($vendorDiscount)) {
                                                $discountAmount = $competitionDiscount;
                                            } else {
                                                $discountAmount = $vendorDiscount;
                                            }

                                            //dd($discountAmount);

                                        @endphp

                                         @if(optional($IsVendorComptStatusActive)->status === 'Active')

                                            <div class="seller_heading_box mb-3 mt-4">
                                                <h5 class="seller_heading">
                                                    Seller - <span class="notranslate">{{ $companyname->title ?? '' }}</span> <br><span style="color:#40be05; font-size: 13px;">
                                                        @if(!empty(optional($getVendorCompetitionById)->title))
                                                            ({{ $getVendorCompetitionById->title ?? '' }})
                                                        @endif
                                                    </span>
                                                </h5>
                                            </div>

                                        @foreach ($questions as $que)
                                            @php
                                                $answer = Helper::getAnswerByQueId($que->id);
                                                $statuses = [
                                                    1 => ['label' => 'Correct Answer', 'class' => 'correct'],
                                                    2 => ['label' => 'Wrong Answer', 'class' => 'wrong'],
                                                    0 => ['label' => 'Pending', 'class' => 'pending'],
                                                ];
                                            @endphp

                                            <div class="form-group mb-4">
                                                <label for="question_{{ $que->id }}">
                                                    Q{{ $questionNumber }}:
                                                    {{ $que->question_text }}
                                                </label>
                                                <input type="hidden" name="questions[{{ $questionNumber }}][question_id]"
                                                    value="{{ $que->id }}">
                                                <input type="hidden" name="directory_id[{{ $questionNumber }}]" value="{{ $que->company_id }}">
                                                <textarea name="questions[{{ $questionNumber }}][answer_text]"
                                                    class="form-control {{ $answer->answer ?? '' ? 'id_answered' : '' }}"  rows="2" >{{ $answer->answer ?? '' }}</textarea>

                                                @error("questions.$questionNumber.answer_text")
                                                    <small class="text-danger">
                                                        {{ $message }}
                                                    </small>
                                                @enderror

                                                <div class="reply-action-wrapper">
                                                    @if(optional($answer)->is_correct == 1)
                                                        @php
                                                            $prize_data = Helper::getPrizeDataId($que->id, $que->advertisement_id, Auth::id());
                                                            //dd($prize_data);
                                                            $prizeAmount = '0';
                                                            if((optional($prize_data)->prize_amount ?? 0) > 0){
                                                                $prizeAmount = $prize_data->prize_amount;
                                                            }
                                                        @endphp
                                                        @if($prize_data)
                                                        <span class="status-badge status-badge--win">
                                                            <p>
                                                                Winning Code: <b>{{ $prize_data->voucher_code??'N/A' }}</b>
                                                                @if($prizeAmount > 0)
                                                                <br>
                                                                Discount:
                                                                <b>
                                                                    @php
                                                                        $discountType = $getVendorCompetitionById->discount_type ?? $data->discount_type ?? null;
                                                                        $prizeAmount = $prize_data->prize_amount ?? 'N/A';
                                                                    @endphp

                                                                    @if($discountType === 'Percentage')
                                                                        {{ $prizeAmount }}%
                                                                    @else
                                                                        {{ $currencySymbol }}{{ $prizeAmount }}
                                                                    @endif
                                                                </b>
                                                                @endif
                                                                <br>
                                                                 Redeem at a <a href="{{ url('account/userprizes#competition-prizes') }}" target="_blank"> Physical Store</a>
                                                            </p>
                                                        </span>
                                                        @endif
                                                    @else
                                                        <button type="button"
                                                            class="reply-btn"
                                                            data-reply-to="{{ $companyname->title ?? 'Support' }}"
                                                            data-competition-id="{{ $data->id }}"
                                                            data-question-id="{{ $que->id }}"
                                                            data-discountamount ="{{ $discountAmount }}"
                                                            data-question="{{ $que->question_text }}"
                                                            data-answer="{{ optional($answer)->answer }}"
                                                            aria-label="Reply for question {{ $questionNumber }}">
                                                            Reply
                                                        </button>
                                                    @endif
                                                </div>

                                            </div>

                                            @php $questionNumber++; @endphp

                                        @endforeach

                                        @endif

                                    @endforeach


                                    <!-- Competition Result Status -->
                                    <div class="competition_results" style="display: none;">

                                        <div class="answer-btn correct">
                                            <span class="icon-circle2">
                                                <input type="checkbox" disabled>
                                            </span>
                                            Correct Answer
                                        </div>

                                        <div class="answer-btn wrong">
                                            <span class="icon-circle2">
                                                <input type="checkbox" disabled>
                                            </span>
                                            Wrong Answer
                                        </div>

                                        <div class="answer-btn pending">
                                            <span class="icon-circle2">
                                                <input type="checkbox" disabled checked>
                                            </span>
                                            Pending
                                        </div>

                                    </div>

                                    @if($questionNumber > 1)
                                         <button type="submit" class="btn btn-primary answers_btn">
                                            Submit Answers
                                        </button>
                                    @endif


                                @endif



                            </form>

                            <br>

                        @else
                            <div class="container">
                            <p>
                                There is no active competition for you at the moment. Please check back later.
                            </p>
                            </div>
                        @endif

                    </div>

                </div>
            </div>

        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</main>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyModalLabel">Reply to Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="replyForm">
                    <div class="form-group">
                        <label for="reply_title"><b>Answer :</b> <span class="answer_class"> </span></label>
                        <input type="hidden" id="answer_val" name="answer_val" >
                        <input type="hidden" id="competition_id" name="competition_id" >
                        <input type="hidden" id="question_id" name="question_id" >
                        <input type="hidden" id="discount_amount" name="discount_amount" >
                    </div>
                    <div class="form-group">
                        <label for="reply_to">Reply To</label>
                        <input type="text" id="reply_to" name="reply_to" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="reply_description">Feedback</label>
                        <textarea id="reply_description" name="reply_description" class="form-control" rows="4" placeholder="Enter your reply"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send Reply</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {

        function checkAnswersFilled() {
            var allFilled = true;
            var $textareas = $("textarea[name^='questions']");

            if ($textareas.length === 0) {
                allFilled = false;
            } else {
                $textareas.each(function() {
                    if ($(this).val().trim() === "") {
                        allFilled = false;
                        return false; // break out of each
                    }
                });
            }

            if (allFilled) {
                $('.answers_btn').hide();
                $textareas.prop('disabled', true).addClass('disabled-field');
            } else {
                //$('.answers_btn').show();
                $textareas.prop('disabled', false).removeClass('disabled-field');
            }
        }

        function updateReplyButtons() {
            $("textarea[name^='questions']").each(function() {
                var hasAnswer = $(this).val().trim() !== "";
                $(this).closest('.form-group').find('.reply-btn').toggle(hasAnswer);
            });
        }

        // Initial check on page load
        checkAnswersFilled();
        updateReplyButtons();

        $(document).on('input change', "textarea[name^='questions']", function() {
            var hasAnswer = $(this).val().trim() !== "";
            //$(this).closest('.form-group').find('.reply-btn').toggle(hasAnswer);
            //checkAnswersFilled();
        });

        $(document).on('click', '.reply-btn', function() {

            var replyTo = $(this).data('reply-to') || 'Support';
            var competitionId = $(this).data('competition-id') || '';
            var questionId = $(this).data('question-id') || '';
            var questionNumber = $(this).data('question') || '';
            var answer = $(this).data('answer') || '';
            var discountAmount = $(this).data('discountamount') || '';

            $('#reply_to').val(replyTo);
            $('#competition_id').val(competitionId);
            $('#question_id').val(questionId);
            $('#discount_amount').val(discountAmount);

            $('#replyModalLabel').html('<b>Reply for Question: </b> ' + questionNumber);
            $('#answer_val').val(answer);
            $('.answer_class').text(answer);

            $('#reply_description').val('');

            $('#replyModal').modal('show');
        });

        $(document).on('click', '#replyModal .close, #replyModal .btn-secondary', function() {
            $('#replyModal').modal('hide');
        });

        $(document).on('click', '#replyModal .btn-primary', function() {
            $('#AJloader').show();
            var payload = {
                competition_id: $('#competition_id').val(),
                question_id: $('#question_id').val(),
                reply_description: $('#reply_description').val(),
                discount_amount: $('#discount_amount').val(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '{{ route('account.questionnaire.reply') }}',
                method: 'POST',
                data: payload,
                dataType: 'json',
                success: function(response) {
                    $('#AJloader').hide();
                    if (response.status === 'success') {
                        $('#replyModal').modal('hide');
                        toastr.success(response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 3000);

                    } else {
                        alert(response.message || 'Unable to submit reply.');
                    }
                },
                error: function(xhr) {
                    var message = 'Unable to submit reply.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    alert(message);
                }
            });
        });

    });
</script>

@endsection
