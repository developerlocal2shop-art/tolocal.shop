@extends('layouts.client.vendor')
@section('title', 'Edit Competition')
@section('content')
<?php
    if (isset($data['advertisementDetails'])) {
        $advertisementDetails = $data['advertisementDetails'];
    }
    $defaultCurrency = Helper::defaultCurrencyData();
    if(session()->has('currency_code')){
        $currencySymbol = session()->get('currency_code')['currsymbol'];
        $currencycode = session()->get('currency_code')['currcode'];
    }else{
        $currencySymbol = $defaultCurrency[0]->symbol;
        $currencycode = $defaultCurrency[0]->code;
    }
?>

<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Edit Competition</h1>
        <x-client.company_account_menu />
    </div>
</div>
<!-- Main page -->
<main>
    <div class="container margin_60_35">

        <div class="modal fade listing_detail_gallery" id="view_winner_user" role="dialog"  >
            <div class="modal-dialog modal-xl modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Winner's Details</h5>
                        <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">×</font></font></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 listgallerypreview2" >
                                <h2 class="user_question"> </h2>

                                <div class="row type_gallery" id="view_winner_user_data">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade listing_detail_gallery" id="user_answer" role="dialog"  >
            <div class="modal-dialog modal-xl modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Participant's Answer</h5>
                        <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">×</font></font></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 listgallerypreview2" >
                                <h2 class="user_question"> </h2>

                                <div class="row type_gallery" id="user_answer_data">

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @if (Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                    @php
                    Session::forget('error');
                    @endphp
                </div>
                @endif
            </div>
        </div>
        <!--Main Area-->
        <div class="card competition_form">
            <div class="card-body">
                <form action="{{ route('company.advertisement.update', $advertisementDetails->id ) }}" method="POST" class="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="directoryId" id="directoryId" value="{{ auth()->user()->directory_id }}">
                    <input type="hidden" name="createdby" class="form-control" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="advertisement_id" id="advertisement_id" value="{{ $advertisementDetails->id }}">
                    <div class="row">

                        <div class="col-md-12">
                             <h5>Admin Details</h5><hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Heading
                                            <a class="" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                            </label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="text" readonly name="heading" id="heading" value="{{ old('heading', $advertisementDetails->heading) }}" placeholder="Enter Heading" class="form-control p-0 border-0">
                                            </div>
                                            <span class="text-danger">
                                                @error('heading')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0"> Text</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="text" readonly name="bottomtext" id="bottomtext" value="{{ old('bottomtext', $advertisementDetails->bottom_text) }}" placeholder="Enter Bottom Text" class="form-control border-0">
                                            </div>
                                            <span class="text-danger">
                                                @error('bottomtext')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Title</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="text" readonly name="midtext" id="midtext" value="{{ old('midtext', $advertisementDetails->mid_text) }}" placeholder="Enter Title" class="form-control border-0">
                                            </div>
                                            <span class="text-danger">
                                                @error('midtext')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>


                                </div>
                                <div class="row">


                                    <div class="col-md-12">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Description</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="text" readonly name="description" id="description" value="{{ old('description', $advertisementDetails->description) }}" placeholder="Enter description" class="form-control border-0">
                                            </div>
                                            <span class="text-danger">
                                                @error('description')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Active Date </label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="date" readonly id="active-date" name="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($advertisementDetails->start_date)->format('Y-m-d')) }}" class="form-control  border-0" autocomplete=off>
                                            </div>

                                            @error('start_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Expiry Date</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="date" readonly id="expiry-date" name="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($advertisementDetails->end_date)->format('Y-m-d')) }}"  class="form-control  border-0" autocomplete=off>
                                            </div>
                                            <span class="text-danger">@error('end_date'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4">

                                            <div class="col-md-12 thumbnailimgPreview mb-4" id="thumbnailimgPreview">
                                                <?php if($advertisementDetails->image_name!=""){ ?>
                                                <img src="{{ env('IMAGE_PATH') . 'files/advertisement/' . $advertisementDetails->image_name }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                                <?php }else{ ?>
                                                <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                                <?php } ?>
                                            </div>

                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Discount Text </label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="text" readonly name="discount_text" id="discount_text" value="{{ old('discount_text', $advertisementDetails->discount_text) }}" placeholder="Enter Discount Text" class="form-control border-0">
                                            </div>
                                            <span class="text-danger">
                                                @error('discount_text')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Discount type</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="text" readonly name="discount_type" id="discount_type" value="{{ old('discount_type', $advertisementDetails->discount_type) }}" placeholder="Enter Discount Type" class="form-control border-0">
                                            </div>
                                            <span class="text-danger">
                                                @error('discount_type')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Discount (
                                                @if($advertisementDetails->discount_type == 'Fixed')
                                                    {{ $currencySymbol }}
                                                @else
                                                    %
                                                @endif
                                                )</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="text" readonly name="price" id="price" value="{{ old('price', $advertisementDetails->price) }}" placeholder="Enter Price" class="form-control border-0">
                                            </div>
                                            <span class="text-danger">
                                                @error('price')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>

                                </div>

                        </div>

                        <div class="col-md-12 questions_div">
                            <h5>Vendor Details</h5><hr>

                            @php
                                $competition_amount = Helper::getVendorCompetitionById(Auth::user()->directory_id, $advertisementDetails->id);

                                $competitionTitle = '';
                                if(!empty(optional($competition_amount)->title)){
                                    $competitionTitle = $competition_amount->title;
                                }

                                $competitiondiscount_type = '';
                                if(!empty(optional($competition_amount)->discount_type)){
                                    $competitiondiscount_type = $competition_amount->discount_type;
                                }

                                $competitiondiscount_amount = '';
                                if(!empty(optional($competition_amount)->discount_amount)){
                                    $competitiondiscount_amount = $competition_amount->discount_amount;
                                }

                            @endphp

                            <div class="form-group mb-4 row">

                                <div class="col-md-5">
                                    <label class="col-md-12">Discount Vendor Text</label>
                                    <div class="col-md-12">
                                        <input type="text" required id="competition_discount_text" name="competition_discount_text" value="{{ old('competition_discount_text', $competitionTitle) }}"  class="form-control  border-0" autocomplete=off style="border: 1px solid #ccc !important;">
                                    </div>
                                    <span class="text-danger">@error('competition_discount_text'){{$message}} @enderror</span>
                                </div>
                                <div class="col-md-2">
                                    <label class="col-md-12">Discount Type</label>
                                    <div class="col-md-12">
                                        <select name="discount_type" id="discount_type" class="form-control border-0" style="border: 1px solid #ccc !important;">
                                            <option value="" >Please Select </option>
                                            <option value="Fixed" {{ old('discount_type', $competitiondiscount_type) == 'Fixed' ? 'selected' : '' }}>Fixed</option>
                                            <option value="Percentage" {{ old('discount_type', $competitiondiscount_type) == 'Percentage' ? 'selected' : '' }}>Percentage</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <label class="col-md-12">Discount Amount ({{ $advertisementDetails->discount_type }})</label>
                                    <div class="col-md-12">
                                        <input type="number" required id="competition_discount" name="competition_discount" value="{{ old('competition_discount', $competitiondiscount_amount) }}"  class="form-control  border-0" autocomplete=off style="border: 1px solid #ccc !important;">
                                    </div>
                                    <span class="text-danger">@error('competition_discount'){{$message}} @enderror</span>
                                </div>
                            </div>
                            @php
                                $hasAnswers = false;
                                if(isset($advertisementDetails) && $advertisementDetails->questions->count()) {
                                    foreach($advertisementDetails->questions as $question) {
                                        if(Helper::getVenAnswerByQueId($question->id)) {
                                            $hasAnswers = true;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            <hr><br>
                                <h5>Questions <small>(Max 10) Add Question and contribute prizes for their customers</small></h5>

                                <div id="question-wrapper">
                                    @if(isset($advertisementDetails) && $advertisementDetails->questions->count())
                                        @foreach($advertisementDetails->questions as $i => $question)
                                        @php
                                            $getVenAnsByQueId = Helper::getVenAnswerByQueId($question->id);
                                            $getPrizeType = Helper::getPrizeType($question->id,$question->advertisement_id);
                                        @endphp
                                            <div class="form-group question-block">
                                                <label>Question {{ $i + 1 }}</label>
                                                {{-- Enter e-mail address --}}
                                                <div class="input-group">
                                                    <p style="background: #eee; padding: 2px 5px; width: 310px; border: 1px solid #a19a9a; margin: 0px 20px 1px 1px;">
                                                        <b> Translated version</b><br>
                                                        {{ $question->question_text }}
                                                    </p>
                                                    <textarea name="questions[]" class="form-control questions_f" rows="2">{{ $question->question_text }}</textarea>
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-danger delete_question" data-qid="{{ $question->id }}">❌</button>

                                                        @if($getVenAnsByQueId)
                                                                @if($getPrizeType && ($getPrizeType->type == 'auto'))
                                                                    <button type="button" class="btn btn-primary alert_user_answer_btn">
                                                                        <b>View Answers</b>
                                                                    </button>
                                                                @else
                                                                    <button type="button" data-queid="{{ $question->id }}" data-directoryid="{{ auth()->user()->directory_id }}"
                                                                            class="btn btn-primary user_answer_btn">
                                                                        <b>View Answers</b>
                                                                    </button>
                                                                @endif

                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="form-group question-block">
                                            <label>Question 1</label>
                                            <div class="input-group">
                                                <input type="text" name="questions[]" class="form-control questions_f">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger btn-remove-question">❌</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- @if(!$hasAnswers) --}}
                                <button type="button" id="add-question" style="float: right;" class="btn btn-sm btn-secondary mt-2">+ Add Question</button>
                                {{-- @endif --}}

                                @if($hasAnswers)
                                <a class="btn btn-success btn-xs participants_btn" href="<?php echo url("/company/competition-participants", ['id' => $advertisementDetails->id]); ?>" style="float: right;">
                                    View Participants
                                </a>
                                @endif
                        </div>

                    </div>
                    <div class="form-group mb-4">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">

    $(document).ready(function () {

        $('.questions_f').change(function() {
            var exp_date = $('#expiry-date').val();
            var currentDate = new Date().toISOString().split('T')[0];

            if (currentDate > exp_date) {
                alert('Cannot add questions. The competition has expired.');
                $('.questions_f').val('');
                return false;
            }
        });

        $(".modal_header_close_btn,.modal_header_close_btn2").click(function() {
            $('#user_answer').modal('hide');
            $('#view_winner_user').modal('hide');

        });

        $(document).on('click', '.delete_question', function(){
            var questionId = $(this).data('qid');
            var advertisement_id = $('#advertisement_id').val();

            if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                url: baseUrl + '/deleteQueById',
                method: 'POST',
                data: {
                    questionId: questionId,
                    advertisement_id: advertisement_id,

                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.msg);
                        location.reload();
                    } else {
                        toastr.error(response.msg);
                        //alert(response.msg); // Error message
                    }
                },
                error: function(xhr) {
                    toastr.error("Something went wrong, please try again.");
                }
            });
            }

        });

        $(document).on('click', '.view_winner', function () {
            var questionId = $(this).data('question');
            var advertisement_id = $('#advertisement_id').val();
            //alert(advertisement_id);
            $.ajax({
                    url: baseUrl + '/getAutoinnerPrizebyId',
                    method: 'POST',
                    data: {
                        questionId: questionId,
                        advertisement_id: advertisement_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#view_winner_user').modal('show');

                        $('#view_winner_user_data').html(`
                        <div class="view_winner_user_data">
                            <ul>
                                <li><b>Competition</b> : ${response.advertisement.heading}<hr></li>
                                <li><b>Question</b> : ${response.question.question_text}<hr></li>
                                <li><b>Answer </b> : ${response.answer.answer}<hr></li>
                                <li><b>User</b> : ${response.user.name} (${response.user.email})<hr></li>
                                <li><b>Voucher code</b> : ${response.voucher_code}<hr></li>
                                <li><b>Date </b> : ${response.created_at}<hr></li>
                                <li><b>Type </b> : ${response.type}</li>
                            </ul>
                        </div>
                        `);
                        console.log('response', response);
                        // if(response.success){
                        //     toastr.success(response.success);
                        // }else{
                        //     toastr.error(response.error);
                        // }
                    }
                });
        });

        $(document).on('change', '.is_correct_select', function () {
            var selectedValue = $(this).val();
            var answerId = $(this).data('id');
            var exp_date = $('#expiry-date').val();

            if (selectedValue == 1) {
                $('#prize_code_div_' + answerId).show();
                $('#prize_expiry_div_' + answerId).show();
                $('#prize_desc_div_' + answerId).show();
                $('#prize_expiry_' + answerId).val(exp_date);
                toastr.error('Please enter Prize code and description.');

            } else {
                $('#prize_code_div_' + answerId).hide();
                $('#prize_expiry_div_' + answerId).hide();
                $('#prize_desc_div_' + answerId).hide();

                $.ajax({
                    url: baseUrl + '/updateanswerstatusbyid',
                    method: 'POST',
                    data: {
                        id: answerId,
                        is_correct: selectedValue,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success){
                            toastr.success(response.success);
                        }else{
                            toastr.error(response.error);
                        }
                    }
                });
            }

        });

        $(document).on('change', '.prize_code', function () {

            var answerId = $(this).data('code_div_id');
            var answer_status = $('#is_correct_'+ answerId).val();
            var exp_date = $('#prize_expiry_'+ answerId).val();
            var prize_code = $(this).val();
            var advertisement_id = $('#advertisement_id').val();
            var customer_id = $('#customer_id_'+ answerId).val();
            var que_id = $('#question_id_'+ answerId).val();
            var prize_description = $('#prize_description_'+ answerId).val();


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
                    url: baseUrl + '/addAnswerstatusprizebyId',
                    method: 'POST',
                    data: {
                        id: answerId,
                        is_correct: answer_status,
                        exp_date: exp_date,
                        prize_code: prize_code,
                        advertisement_id: advertisement_id,
                        que_id: que_id,
                        customer_id: customer_id,
                        prize_description: prize_description,
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



        $(document).on('click', '.select-winner', function () {
            let questionId = $(this).data('question');
            let result = confirm("Are you sure?");
            if (result) {
                 var advertisement_id = $('#advertisement_id').val();
                 var expiry_date = $('#expiry-date').val();
                $.ajax({
                    url: '/selectAutowinner/' + questionId,
                    method: 'POST',
                    dataType: 'json',
                    data: {_token: "{{ csrf_token() }}", advertisement_id:advertisement_id, expiry_date:expiry_date},
                    success: function (res) {
                        //console.log('res- ', res);
                        toastr.success(res.message);
                        setTimeout(function() {
                            location.reload();
                        }, 3000);

                    }
                });
            }
        });

        $('.alert_user_answer_btn').click(function() {
            toastr.success("The prize has been distributed automatically. Please click 'View Winner' to see the details.");
        });
        $('.alert_user_answer_btn2').click(function() {
            toastr.success("The prize has been distributed manually. Please click 'Manual Winner' to see the details.");
        });


        //user_answer_btn
        $(".user_answer_btn").click(function() {
            var directoryid = $(this).data('directoryid');
            var queid = $(this).data('queid');

            //$('.user_select_winner').data('questionid', queid);
            $('.user_select_winner').attr('data-question', queid);

            var exp_date = $('#expiry-date').val();
            $('.prize_expiry').val(exp_date);

            var token = "{{ csrf_token() }}";
                $.ajax({
                    url: baseUrl + '/get-advertisementquestionsid'
                    , type: "post"
                    , data: {
                        queid: queid, directoryid: directoryid, _token: token
                    }
                    , success: function(responce) {
                        console.log('responce - ',responce);
                        $('#user_answer_data').html(responce);

                    }
                });

            $('#user_answer').modal('show');
        });



        let maxQuestions = 10;
        let questionWrapper = $('#question-wrapper');

        function updateLabels() {
            questionWrapper.find('.question-block').each(function (index) {
                $(this).find('label').text('Question ' + (index + 1));
            });
        }

        $('#add-question').on('click', function () {

            var exp_date = $('#expiry-date').val(); // 2026-09-30
            var currentDate = new Date().toISOString().split('T')[0];

            if (currentDate > exp_date) {
                alert('Cannot add questions. The competition has expired.');
                return false;
            }

            let count = questionWrapper.find('.question-block').length;
            if (count < maxQuestions) {
                let newQuestion = `
                    <div class="form-group question-block">
                        <label>Question ${count + 1}</label>
                        <div class="input-group">
                            <input type="text" name="questions[]" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-danger btn-remove-question">❌</button>
                            </div>
                        </div>
                    </div>
                `;
                questionWrapper.append(newQuestion);
                updateLabels();
            } else {
                alert('Maximum 10 questions allowed.');
            }

        });

        questionWrapper.on('click', '.btn-remove-question', function () {
            $(this).closest('.question-block').remove();
            updateLabels();
        });
        $('.ckeditor').ckeditor();
    });

    var advertisementloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-thumbnail-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

    $(document).ready(function() {
        // Warning
        $(window).on('beforeunload', function() {
            return "Any changes will be lost";
        });
        // Form Submit
        $(document).on("submit", "form", function(event) {
            // disable warning
            $(window).off('beforeunload');
        });
    });

</script>
<style>
    .modal-dialog {
        max-width: 1015px;
    }
    h4.user_question {
        font-size: 17px;
    }
    #user_answer_data {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .view_winner_user_data ul {
        color: #000;
        padding: 0px 15px;
    }
    div#view_winner_user_data p {
        color: #000;
    }
    #user_answer_data li {
        background: #f9f9f9;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 15px;
        margin-right: 5px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        float:left;
    }

    #user_answer_data li .user-name {
        font-weight: 600;
        color: #333;
    }

    #user_answer_data li .user-email {
        color: #555;
        font-size: 14px;
    }

    #user_answer_data li .user-answer {
        color: #222;
        font-size: 15px;
        margin: 15px 10px;
    }
    .modal {
        top: 137px;
    }
    ul#user_answer_data {
        color: #060606e6;
        margin: 5px;
        font-size: 15px;
    }
    .modal-content {
        background-color: #fff !important;
    }
    .imageuploadify {
        padding: 2px !important;
    }
    .select2-container-multi {
        width: 300px
    }
    img#preview-thumbnail-img {
        margin: 17px -15px;
    }
    .thumbnail {
        position: relative;
        display: inline-flex;
        width: 70px;
        height: 70px;
    }

    .thumbnail img {
        width: 70px;
        height: 70px;
    }

    .closebtn {
        display: block;
        position: absolute;
        width: 20px;
        height: 20px;
        top: -9px;
        right: -9px;
        /* background: url(http://icons.iconarchive.com/icons/kyo-tux/delikate/512/Close-icon.png); */
        background-size: 100% 100%;
        background-repeat: no-repeat;

    }

    .btn.btn-info {
        background: #2cabe3;
        border-color: #2cabe3;
        color: #fff;
        margin-top: 20px;
        min-width: 120px;
    }

    #tooltip {
        position: relative;
        cursor: pointer;
        padding-left: 7px;
        font-size: 17px;
        font-family: sans-serif;
    }

    #tooltipText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 6%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:230px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #tooltip:hover:after {
        top: 16px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }
    #tooltipText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }

    #tooltip:hover #tooltipText {
        visibility: visible;
        opacity: 1;
        top: 76%;
        z-index: 99;
    }
    div#thumbnailimgPreview {
        margin: -15px 5px;
    }
    .questions_div {
        background: #dbdbdb;
        padding: 0px 15px 15px 15px;
    }



    /* Card Styling */
    .competition_form {
        border: none;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        background: #fff;
    }

    .competition_form .card-body {
        padding: 25px 30px;
    }

    /* Labels */
    .competition_form label {
        font-weight: 600;
        font-size: 14px;
        color: #2c3e50;
        margin-bottom: 6px;
    }

    /* Inputs */
    .competition_form .form-control {
        border: none;
        border-bottom: 2px solid #e0e0e0;
        border-radius: 0;
        padding: 8px 2px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: transparent;
    }

    .competition_form .form-control:focus {
        border-bottom: 2px solid #007bff;
        box-shadow: none;
        background: #f9fbff;
    }

    /* Input Group Buttons */
    .input-group-append .btn {
        border-radius: 6px;
        margin-left: 5px;
        font-size: 13px;
        padding: 6px 10px;
    }

    /* Buttons */
    .btn-info {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
        border-radius: 8px;
        padding: 10px 25px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-info:hover {
        background: linear-gradient(135deg, #0056b3, #004099);
    }

    /* Add Question Button */
    #add-question {
        border-radius: 20px;
        padding: 0px 20px;
        font-size: 13px;
        margin: 0px 10px;
    }

    /* Question Section */
    .questions_div {
        background: #f7f9fc;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }

    .questions_div h5 {
        font-weight: 600;
        margin-bottom: 15px;
        color: #34495e;
    }

    /* Question Block */
    .question-block {
        background: #fff;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: 0.2s;
    }

    .question-block:hover {
        transform: translateY(-2px);
    }

    /* Delete Button */
    .btn-danger {
        background: #ff4d4f;
        border: none;
    }

    .btn-danger:hover {
        background: #d9363e;
    }

    /* Winner Buttons */
    .btn-primary {
        background: #1890ff;
        border: none;
    }

    .btn-success {
        background: #28a745;
        border: none;
    }

    /* Image Preview */
    .thumbnailimgPreview {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f3f6;
        border-radius: 10px;
        height: 90px;
    }

    .thumbnailimgPreview img {
        border-radius: 8px;
        object-fit: cover;
    }

    /* Section Divider */
    .questions_div hr {
        border-top: 1px solid #e0e0e0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .competition_form .card-body {
            padding: 15px;
        }

        .question-block {
            padding: 10px;
        }

        .input-group {
            flex-direction: column;
        }

        .input-group-append {
            margin-top: 8px;
        }
    }

</style>
@endsection
