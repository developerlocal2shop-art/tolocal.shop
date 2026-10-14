@extends('layouts.client.company')
@section('title','Answer The Question')
@section('content')
@php $user = auth()->user(); @endphp
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


</style>
<!-- /sub_header -->

<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Answer The Question</h1>
        <x-client.company_account_menu />
        
    </div>
    <!-- /container -->
</div>

<!-- Main page -->
<main>
    @if($is_competitionWinners == 0)
            <span style="font-size: 17px;color: #201e1e;font-weight: 500; margin: 0px 90px;"> 
                Evaluation in progress
            </span>
        @endif
    <div class="container margin_60_35">
        
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        @if(Session::has('error'))
                        <div class="alert alert-danger">
                            {{ Session::get('error') }}
                            @php
                            Session::forget('error');
                            @endphp
                        </div>
                        @endif
                        @if(Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                            @php
                            Session::forget('success');
                            @endphp
                        </div>
                        @endif
                    </div>
                    <div class="col-lg-11">
                    @if($data)
                        <form action="{{ route('advertisement.vendor.answers.store', $data->id??'') }}" class="profile_data" method="POST">
                            @csrf
                               <input type="hidden" name="directory_id" value="{{ $data->directory_id??'' }}">
                                @if(count($data->questions) > 0)
                                 <h4>
                                    {{ Str::words($data->heading, 6, '...') }}
                                    <span style="font-size: 15px; color:#c39922;">
                                        @if(empty($data->directory_id) || $data->directory_id == 0)
                                            Admin
                                        @else
                                        @php
                                           $getDirectoryIdSlug = Helper::getDirectoryIdSlug($data->directory_id);
                                        @endphp
                                            Seller - {{ $getDirectoryIdSlug->title??'' }}
                                        @endif
                                    </span>
                                </h4><hr>
                                @foreach ($data->questions as $in => $que)
                                @php    
                                $sn = $in + 1;
                                  $getAnswerQueId = Helper::getAnswerByQueId($que->id);
                                    $statuses = [
                                        1 => ['label' => 'Correct Answer', 'class' => 'correct'],
                                        2 => ['label' => 'Wrong Answer', 'class' => 'wrong'],
                                        0 => ['label' => 'Pending', 'class' => 'pending'],
                                    ];
                                
                                @endphp
                                
                                <div class="form-group mb-3">
                                        <label for="question_{{ $que->id }}">
                                          Q{{ $sn }}: {{ $que->question_text }}
                                        </label>
                                        <input type="hidden" name="questions[{{ $sn }}][question_id]" value="{{ $que->id }}">
                                        <textarea name="questions[{{ $sn }}][answer_text]" class="form-control @if ($getAnswerQueId->answer??'') id_answered @endif " rows="2" >{{ old("questions.{$que->id}.answer_text",$getAnswerQueId->answer??'') }}</textarea>
                                        @error("questions.$sn.answer_text")
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        @if ($getAnswerQueId->answer??'') 

                                        
                                        @foreach ($statuses as $key => $status)
                                            <div class="answer-btn {{ $status['class'] }}">
                                                <span class="icon-circle2">
                                                    <input type="checkbox" disabled {{ $getAnswerQueId->is_correct === $key ? 'checked' : '' }}>
                                                </span>
                                                {{ $status['label'] }}
                                            </div>
                                        @endforeach

                                        @endif
                                </div>
                                
                                @endforeach
                                @endif
                           

                            <button type="submit" class="btn btn-primary answers_btn">Submit Answers</button>
                        </form>
                        <br>
                    @endif
                    </div>

                    
                </div>
                
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</main>

<script>
    $(document).ready(function() {

        if ($('.id_answered').val()) {
            $('.answers_btn').hide();
            $('.id_answered').prop('disabled', true);
        } else {
        }

        $("textarea[name^='questions']").on("input", function () {
            let currentValue = $(this).val().trim();
            if (currentValue !== "") {
                // Disable all other textareas
                $("textarea[name^='questions']")
                    .not(this)
                    .prop("disabled", true)
                    .addClass("disabled-field");
            } else {
                // No answer => enable all textareas
                $("textarea[name^='questions']")
                    .prop("disabled", false)
                    .removeClass("disabled-field");
            }
        });

    });
</script>

@endsection
