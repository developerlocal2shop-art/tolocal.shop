@extends('layouts.client.company')
@section('title','Holiday Template | Edit Normal Holiday Template')
@section('content')

<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Edit Normal Holiday Template</h1>
        <x-client.company_account_menu />
    </div>
</div>
<div class="container margin_60_35">
    <div class="row">
        <x-client.holiday_template_menu />
        <div class="col-lg-9">
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-sm-4 col-xs-12">
                        <h4 class="page-title">Edit Normal Holiday Template</h4>
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
                    <form action="{{ route('company.normal.template.update', $templateDetails->id) }}" method="POST" class="">
                        {{ csrf_field() }}
                        <div class="col-md-12 cus-title">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Template Name</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="template_name" value="{{ old('$template_name', $templateDetails->name) }}" placeholder="Enter Template Name" class="form-control p-0 border-0" autocomplete=off>
                                </div>
                                <span class="text-danger">@error('template_name'){{$message}} @enderror</span>
                            </div>
                        </div>

                        <div class="tab-pane" id="schedule" role="tabpanel">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="border-top-0">Day</th>
                                        <th class="border-top-0">Closed</th>
                                        <th class="border-top-0">Open</th>
                                        <th class="border-top-0">Closed</th>
                                        <th class="border-top-0">Add Hours</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                                            $timeValue = array("00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00",
                                        "08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
                                        "17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00");
                                            ?>
                                    <?php   foreach($scheduleData as $value=>$dayDetail){ ?>
                                    <?php $j=0; foreach($dayDetail as $dayData){ ?>
                                    <tr class=" <?= $value;?> <?= $value.'_row_'.$j;?>">
                                        <td> <?= $j==0?$value.':':''; ?></td>
                                        <td>
                                            <?php if($j==0){?>
                                            <input class="form-check-input" type="checkbox" name="<?php echo $value; ?>_is_closed" value="1" <?php if($dayData['is_closed']){echo "checked";}?>>
                                            <?php  } ?>
                                        </td>
                                        <td>
                                            <select class="form-control" name="<?= $value;?>_hours_min[]">
                                                <?php foreach($timeValue as $rowtime){ ?>
                                                <option value="<?php echo $rowtime ?>" <?php if($rowtime==$dayData['open_time']){echo "selected" ;}?>>
                                                    <?php echo $rowtime ?>
                                                </option>
                                                <?php } ?>

                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="<?= $value;?>_hours_max[]">
                                                <?php foreach($timeValue as $rowtime){ ?>
                                                <option value="<?php echo $rowtime ?>" <?php if($rowtime==$dayData['close_time']){echo "selected" ;}?>>
                                                    <?php echo $rowtime ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <?php if($j==0){?><button type="button" class="btn btn-info" id="" onclick="addDayBlock('<?= $value;?>')">+ Hours</button>
                                            <?php }else{?>
                                            <button class="btn btn-danger" type="button" onclick="removeDayBlock('<?= $value;?>',<?= $j;?>)">- Hours</button>
                                            <?php } ?>
                                        <td>
                                    </tr>
                                    <?php $j++; } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
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
    </div>

</div>


<script type="text/javascript">
    function addTimeBlockPublic() {
        var numItems = $('.row_public').length;
        var insertBlock = parseInt(numItems - 1);
        // alert(insertBlock);
        $('<tr class="row_public time_slot_row_' + numItems + '"> <td> </td> <td></td> <td> <select class="form-control" name="time_hours_min[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select> </td> <td> <select class="form-control" name="time_hours_max[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select></td> <td> <button class="btn btn-danger" type="button" onclick=removeTimeBlock(`time_slot_row_' + numItems + '`)>- Hours</button> </td> </tr>').insertAfter('.time_slot_row_' + insertBlock);
    }

    function removeTimeBlock(blockClass) {
        $('.' + blockClass).remove();
    }

    function addDayBlock(blockClass) {
        var numItems = $('.' + blockClass).length;
        var insertBlock = parseInt(numItems - 1);
        // alert(insertBlock);
        $('<tr class="' + blockClass + ' ' + blockClass + '_row_' + numItems + '"> <td> </td> <td></td> <td> <select class="form-control" name="' + blockClass + '_hours_min[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select> </td> <td> <select class="form-control" name="' + blockClass + '_hours_max[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select></td> <td> <button class="btn btn-danger" type="button" onclick=removeDayBlock(`' + blockClass + '`,`' + numItems + '`)>- Hours</button> </td> </tr>').insertAfter('.' + blockClass + '_row_' + insertBlock);
    }

    function removeDayBlock(blockClass, numItems) {
        $('.' + blockClass + '_row_' + numItems).remove();
    }


    $(document).on('click', ".deleterow", function() {
        if (confirm('Are you sure you want delete?')) {
            var valid = $(this).attr('data-id');
            var tblrow = "#tblholi_" + valid;
            console.log('valid: ', tblrow);
            holidayurl = '/company/public/delete-holiday-template-record';
            $.ajax({
                url: baseUrl + holidayurl
                , type: "POST"
                , data: {
                    id: valid
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    // console.log('imageid',result);
                    if (result == true) {
                        $(tblrow).remove();
                    }

                }
            });
        }

    });

    $(document).ready(function() {
        // Warning
        window.addEventListener('beforeunload', function(e) {
            e.preventDefault();
            e.returnValue = '';
            return 'Changes you made may not be saved.';
        });

        // $('body').on('click', function(e) {
        //     var target, href;
        //     target = $(e.target);
        //     if (e.target.tagName === 'A' || target.parents('a').length > 0) {
        //         e.preventDefault();
        //         if (window.confirm("Do you want to leave the function?")) {
        //             if (e.target.tagName === 'A') {
        //                 href = target.attr('href');
        //             } else {
        //                 href = target.parents('a').first().attr('href');
        //             }
        //             window.location.href = href;
        //         }
        //     }
        // });
        // Form Submit
        $(document).on("submit", "form", function(event) {
            // disable warning
            $(window).off('beforeunload');
        });
    });

</script>
<style>
    .btn-danger {
        color: #fff !important;
    }

</style>
@endsection
