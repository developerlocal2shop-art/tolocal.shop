@extends('layouts.adminmaster')
@section('title','Exceptional Holiday Template | Edit Exceptional Holiday Template')
@section('content')

        
        <div class="page-wrapper">
            
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Edit Exceptional Holiday Template</h4>
                    </div>
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            <div class="container-fluid">
                
                <div class="row">
                    <!-- Column -->
                    
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.exceptionalholidaytemplate.update', $templateDetails->id) }}" method="POST" class="">
                                {{ csrf_field() }}
                                    <div class="col-md-4 cus-title">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Template Name</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="text" name="template_name" value="{{ old('$template_name', $templateDetails->name) }}" placeholder="Enter Template Name" class="form-control p-0 border-0" autocomplete=off>
                                            </div>
                                            <span class="text-danger">@error('template_name'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <?php $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                                            $timeValue = array("00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00",
                                        "08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
                                        "17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00");
                                    ?>

                                    <?php if($scheduleHolidayDatacount!=0){ ?>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="border-top-0">Date</th>
                                                    <th class="border-top-0">Holiday</th>
                                                    <th class="border-top-0">Day</th>
                                                    <th class="border-top-0">Opening</th>
                                                    <th class="border-top-0">Closing</th>
                                                    <th class="border-top-0">Closed</th>
                                                    <th class="border-top-0">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>       
                                                @foreach($scheduleHolidayData as $holidayrow)   
                                                <?php $opentimeholiday =   json_decode($holidayrow->custom_time); ?>                          
                                                <tr id="tblholi_<?php echo $holidayrow->id; ?>">
                                                    <td>
                                                        {{ $holidayrow->custom_date }}
                                                    </td>
                                                    <td>
                                                        {{ $holidayrow->note }}
                                                    </td>
                                                    <td>
                                                        {{ $holidayrow->day }}
                                                    </td>
                                                    <td>
                                                        <?php foreach($opentimeholiday as $extratime){?>
                                                            <p><?php echo "Open Time: ".$extratime->open_time; ?></p>
                                                        <?php } ?>
                                                        
                                                    </td>
                                                    <td>
                                                        <?php foreach($opentimeholiday as $extratime){?>
                                                                <p><?php echo "Close Time: ".$extratime->close_time; ?></p>
                                                            <?php } ?>
                                                    </td>
                                                    <td> 
                                                        {{ $holidayrow->closed }}
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger deleterow" data-id="{{ $holidayrow->id }}">Delete</button>
                                                        
                                                    </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table> 
                                    <?php } ?>

                                    <table class="table text-nowrap table-info">
                                        <thead>
                                            <tr>
                                                <th class="border-top-0">Date</th>
                                                <th class="border-top-0">Holiday</th>
                                                <th class="border-top-0">Day</th>
                                                <th class="border-top-0">Opening</th>
                                                <th class="border-top-0">Closing</th>
                                                <th class="border-top-0">Closed</th>
                                                <th class="border-top-0">Add Hours</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                          
                                           <tr class="row_public time_slot_row_0">
                                               <td>
                                                <div class="form-group mb-4">
                                                    <div class="col-md-12 border-bottom ">
                                                        <input type="date" name="custom_date"  class="form-control  border-0" autocomplete=off>
                                                    </div>                                                    
                                                </div>
                                            </td>
                                                <td>
                                                    <div class="form-group mb-4">
                                                        <div class="col-md-12 border-bottom ">
                                                            <input type="text" name="note" placeholder="Holiday note" class="form-control border-0" autocomplete=off>
                                                        </div>                                                    
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="day">
                                                        <?php foreach($days as $rowval){ ?>
                                                        <option value="<?php echo $rowval ?>" >
                                                        <?php echo $rowval ?>
                                                        </option>
                                                        <?php } ?>
    
                                                    </select>
                                                </td>
                                                <td> 
                                                    <select class="form-control" name="time_hours_min[]">
                                                    <?php foreach($timeValue as $rowtime){ ?>
                                                    <option value="<?php echo $rowtime ?>" >
                                                    <?php echo $rowtime ?>
                                                    </option>
                                                    <?php } ?>

                                                    </select>
                                                 </td>
                                                <td> 
                                                    <select class="form-control" name="time_hours_max[]">
                                                    <?php foreach($timeValue as $rowtime){ ?>
                                                    <option value="<?php echo $rowtime ?>" >
                                                    <?php echo $rowtime ?>
                                                    </option>
                                                    <?php } ?>
                                                    </select>
                                                </td>
                                                <td> 
                                                    <div class="form-group mb-4">
                                                        <div class="col-md-12 border-bottom ">
                                                            <select name="closed" class="form-control border-0"> 
                                                                <option value="No">No</option>
                                                                <option value="Yes">Yes</option>
                                                            </select>
                                                        </div>                                                    
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" id="" onclick="addTimeBlockPublic()">+ Hours</button>
                                                </td>
                                           </tr>
                                           
                                        </tbody>
                                    </table>    
                                                                        
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
                
            </div>
            
            <footer class="footer text-center"> 
                <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
            </footer>
            
        </div>
        
    </div>
    
<script type="text/javascript">
    function addTimeBlockPublic(){
        var numItems = $('.row_public').length;
        var insertBlock=parseInt(numItems-1);
    // alert(insertBlock);
    $('<tr class="row_public time_slot_row_'+numItems+'"> <td> </td> <td></td> <td> <select class="form-control" name="time_hours_min[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select> </td> <td> <select class="form-control" name="time_hours_max[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select></td> <td> <button class="btn btn-danger" type="button" onclick=removeTimeBlock(`time_slot_row_'+numItems+'`)>- Hours</button> </td> </tr>').insertAfter('.time_slot_row_'+insertBlock);
    }

    function removeTimeBlock(blockClass){
    $('.'+blockClass).remove();
    }
    function addDayBlock(blockClass){
        var numItems = $('.'+blockClass).length;
        var insertBlock=parseInt(numItems-1);
    // alert(insertBlock);
    $('<tr class="'+blockClass+' '+blockClass+'_row_'+numItems+'"> <td> </td> <td></td> <td> <select class="form-control" name="'+blockClass+'_hours_min[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select> </td> <td> <select class="form-control" name="'+blockClass+'_hours_max[]"> <option value="00:00">00:00</option> <option value="00:30">00:30</option> <option value="01:00">01:00</option> <option value="01:30">01:30</option> <option value="02:00">02:00</option> <option value="02:30">02:30</option> <option value="03:00">03:00</option> <option value="03:30">03:30</option> <option value="04:00">04:00</option> <option value="04:30">04:30</option> <option value="05:00">05:00</option> <option value="05:30">05:30</option> <option value="06:00">06:00</option> <option value="06:30">06:30</option> <option value="07:00">07:00</option> <option value="07:30">07:30</option> <option value="08:00">08:00</option> <option value="08:30">08:30</option> <option value="09:00">09:00</option> <option value="09:30">09:30</option> <option value="10:00">10:00</option> <option value="10:30">10:30</option> <option value="11:00">11:00</option> <option value="11:30">11:30</option> <option value="12:00">12:00</option> <option value="12:30">12:30</option> <option value="13:00">13:00</option> <option value="13:30">13:30</option> <option value="14:00">14:00</option> <option value="14:30">14:30</option> <option value="15:00">15:00</option> <option value="15:30">15:30</option> <option value="16:00">16:00</option> <option value="16:30">16:30</option> <option value="17:00">17:00</option> <option value="17:30">17:30</option> <option value="18:00">18:00</option> <option value="18:30">18:30</option> <option value="19:00">19:00</option> <option value="19:30">19:30</option> <option value="20:00">20:00</option> <option value="20:30">20:30</option> <option value="21:00">21:00</option> <option value="21:30">21:30</option> <option value="22:00">22:00</option> <option value="22:30">22:30</option> <option value="23:00">23:00</option> <option value="23:30">23:30</option> </select></td> <td> <button class="btn btn-danger" type="button" onclick=removeDayBlock(`'+blockClass+'`,`'+numItems+'`)>- Hours</button> </td> </tr>').insertAfter('.'+blockClass+'_row_'+insertBlock);
    }

    function removeDayBlock(blockClass,numItems){
    $('.'+blockClass+'_row_'+numItems).remove();
    }
 
</script>
<script>
    $(document).on('click', ".deleterow", function() {
        if (confirm('Are you sure you want delete?')) {
            var valid = $(this).attr('data-id');
            var tblrow = "#tblholi_"+valid;
            console.log('valid: ',tblrow);
            holidayurl = '/admin/delete-exe-holiday-template-record';
            $.ajax({
                url: baseUrl + holidayurl,
                type: "POST",
                data: {
                    id: valid,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    // console.log('imageid',result);
                    if (result == true) {
                        $(tblrow).remove();
                    }

                }
            });
        //console.log('Thing was saved to the database.');
        }
            
        });
</script>
    
@endsection
