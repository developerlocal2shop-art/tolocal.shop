@extends('layouts.adminmaster')
@section('title','Competition')
@section('content')

<?php
if (isset($data['advertisementDetails'])) {
    $advertisementDetails = $data['advertisementDetails'];
}
?>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Competition Edit</h4>
            </div>
        </div>
    </div>
    <div class="container-fluid">

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
                        <h5 class="modal-title">User's Answer</h5>
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

        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif

        <div class="card">
            <div class="card-body">

                <input type="hidden" name="advertisement_id" id="advertisement_id" value="{{ $advertisementDetails->id }}">

                <form action="{{ route('admin.advertise.update', $advertisementDetails->id) }}" method="POST" class="" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">

                <div class="row">
                    <div class="col-md-12">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Heading
                                <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                </label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="heading" id="heading" value="{{ old('heading', $advertisementDetails->heading) }}" placeholder="Enter Heading" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('heading'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                           @php
                                $adminassignvendor = explode(',', $advertisementDetails->adminassignvendor);
                                $allDirectory = Helper::getAllDirectory();
                            @endphp

                            @if($advertisementDetails->directory_id == 0 || $advertisementDetails->directory_id == '')
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Assign Company</label>
                                <div class="col-md-12 border-bottom p-0 productselectdir">
                                    <select class="form-control" name="company_id[]" id="directoryId" multiple style="width: 100%;">
                                        <option value="0">None</option>
                                        @foreach($allDirectory as $directoryrow)
                                            <option value="{{ $directoryrow->id }}"
                                                {{ in_array($directoryrow->id, $adminassignvendor) ? 'selected' : '' }}>
                                                {{ $directoryrow->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('company_id'){{ $message }}@enderror</span>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Discount type</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="discount_type" id="discount_type" class="form-control border-0">
                                        <option value="Fixed" {{ old('discount_type', $advertisementDetails->discount_type) == 'Fixed' ? 'selected' : '' }}>Fixed</option>
                                        <option value="Percentage" {{ old('discount_type', $advertisementDetails->discount_type) == 'Percentage' ? 'selected' : '' }}>Percentage</option>
                                    </select>
                                </div>
                                <span class="text-danger">@error('discount_type'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Discount </label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="number" name="price" id="price" value="{{ old('price', $advertisementDetails->price) }}" placeholder="Enter Price" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('price'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Discount Text</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="discount_text" id="discount_text" value="{{ old('discount_text', $advertisementDetails->discount_text) }}" placeholder="Enter Discount Text" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('discount_text'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Title</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="midtext" id="midtext" value="{{ old('midtext', $advertisementDetails->mid_text) }}" placeholder="Enter Title " class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('midtext'){{$message}} @enderror</span>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Link</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="link" id="link" value="{{ old('link', $advertisementDetails->link) }}" placeholder="Enter Link" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('link'){{$message}} @enderror</span>
                            </div>
                        </div> --}}

                    </div>
                    <div class="row">


                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0"> Text</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="bottomtext" id="bottomtext" value="{{ old('bottomtext', $advertisementDetails->bottom_text) }}" placeholder="Enter Bottom Text" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('bottomtext'){{$message}} @enderror</span>
                            </div>
                        </div>

                         <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Description</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="description" id="description" value="{{ old('description', $advertisementDetails->description) }}" placeholder="Enter description" class="form-control border-0">
                                </div>
                                <span class="text-danger">
                                    @error('description')
                                    {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Active Date </label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="date" id="active-date" name="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($advertisementDetails->start_date)->format('Y-m-d')) }}" class="form-control  border-0" autocomplete=off>
                                </div>

                                @error('start_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Expiry Date</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="date" id="expiry-date" name="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($advertisementDetails->end_date)->format('Y-m-d')) }}"  class="form-control  border-0" autocomplete=off>
                                </div>
                                <span class="text-danger">@error('end_date'){{$message}} @enderror</span>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Choose image (800 X 800)px</label>
                                <div class="imageuploadify">
                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here </span>

                                    <input type="file" class="form-control" name="image" onchange="advertisementloadFile(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 thumbnailimgPreview mb-4" id="thumbnailimgPreview">
                                    <?php if($advertisementDetails->image_name!=""){ ?>
                                    <img src="{{ env('IMAGE_PATH').'files/advertisement/'.$advertisementDetails->image_name }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                    <?php }else{ ?>
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                    <?php } ?>
                                </div>

                            </div>

                        </div>


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

        <!-- Column -->


    </div>
    <footer class="footer text-center">
        <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
    </footer>
</div>
</div>
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">

    $('.alert_user_answer_btn').click(function() {
            toastr.success("The prize has been distributed automatically. Please click 'View Winner' to see the details.");
    });
    $('.alert_user_answer_btn2').click(function() {
        toastr.success("The prize has been distributed manually. Please click 'Manual Winner' to see the details.");
    });



    $(document).on('click', '.delete_question', function(){
        var questionId = $(this).data('qid');
        var advertisement_id = $('#advertisement_id').val();

        if (confirm("Are you sure you want to delete this?")) {
        $.ajax({
            url: baseUrl + '/admindeleteQueById',
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

    $(document).on('click', '.select-winner', function () {
        let questionId = $(this).data('question');
        let result = confirm("Are you sure?");
        if (result) {
                var advertisement_id = $('#advertisement_id').val();
                var expiry_date = $('#expiry-date').val();
            $.ajax({
                url: '/admin/selectAutowinner/' + questionId,
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


    $(document).ready(function() {

        if ($("#user_answer_data > div").length > 2) {
            $("#user_answer .modal-body").css({
                "max-height": "400px",
                "overflow-y": "auto"
            });
        }

        $("#directoryId, #pro-tags").select2({
            placeholder: "Select Option "
            , allowClear: true
        });

        $(".modal_header_close_btn").click(function() {
            $('#user_answer').modal('hide');
            $('#view_winner_user').modal('hide');
        });

         $(document).on('click', '.view_winner', function () {
            var questionId = $(this).data('question');
            var companyid = $(this).data('companyid');
            var advertisement_id = $('#advertisement_id').val();
            //alert(advertisement_id);
            $.ajax({
                    url: baseUrl + '/admingetAutoinnerPrizebyId',
                    method: 'POST',
                    data: {
                        questionId: questionId,
                        companyid: companyid,
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

                    }
                });
        });

        $(document).on('change', '.is_correct_select', function () {
            var selectedValue = $(this).val();
            var answerId = $(this).data('id');
            var exp_date = $('#expiry-date').val();

           var vendor_id = $('#vendor_id_' + answerId).val();

            if (selectedValue == 1 && vendor_id == 0) {
                $('#prize_code_div_' + answerId).show();
                $('#prize_expiry_div_' + answerId).show();
                $('#prize_desc_div_' + answerId).show();
                $('#prize_expiry_' + answerId).val(exp_date);
                toastr.error('Please enter Prize code and description.');

            } else if (vendor_id == 0) {
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
            var prize_description = $('#prize_description_'+ answerId).val();
            var que_id = $('#question_id_'+ answerId).val();

            if(prize_code == ''){
                toastr.error('Prize code is required');
                return false;
            }
            if(prize_description == ''){
                toastr.error('Prize description is required');
                return false;
            }
            if (!confirm('Are you sure you want to continue?')) {
                return false;
            }

                $.ajax({
                    url: baseUrl + '/admin/addAnswerstatusprizebyId',
                    method: 'POST',
                    data: {
                        id: answerId,
                        que_id: que_id,
                        is_correct: answer_status,
                        exp_date: exp_date,
                        prize_code: prize_code,
                        advertisement_id: advertisement_id,
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

        });


        // $(".user_answer_btn").click(function() {
        //     var directoryid = $(this).data('directoryid');
        //     var queid = $(this).data('queid');

        //     var exp_date = $('#expiry-date').val();
        //     $('.prize_expiry').val(exp_date);

        //     var token = "{{ csrf_token() }}";
        //         $.ajax({
        //             url: baseUrl + '/admin/get-advertisementquestionsid'
        //             , type: "post"
        //             , data: {
        //                 queid: queid, directoryid: directoryid, _token: token
        //             }
        //             , success: function(responce) {
        //                 console.log('responce - ',responce);
        //                 $('#user_answer_data').html(responce);

        //             }
        //         });

        //     $('#user_answer').modal('show');
        // });

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

</script>
<script type="text/javascript">
    var advertisementloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-thumbnail-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

</script>


<style>

   #user_answer .modal-body {
        max-height: 400px;
        overflow-y: auto;
    }
    #user_answer_data {
        list-style: none;
        padding: 0;
        margin: 0;
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
        width: 48%;
        list-style: none;
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
        margin: 5px 15px;
    }

</style>

@endsection
