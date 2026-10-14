@extends('layouts.adminmaster')
@section('title','Holiday Template | Edit Holiday Template')
@section('content')

<style>
    .btn-danger {
        color: #fff !important;
    }
</style>    

<?php //echo "<pre>"; print_r($scheduleHolidayData);die; ?>
        <div class="page-wrapper">
            
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Edit Holiday Template</h4>
                    </div>
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            <div class="container-fluid">
                
                <div class="row">
                    <!-- Column -->
                    
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.holidaytemplate.dataupdate', $scheduleHolidayData[0]->id) }}" method="POST" class="">
                                {{ csrf_field() }}
                                    
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
                                                </tr>
                                            </thead>
                                            <tbody>       
                                                @foreach($scheduleHolidayData as $holidayrow)   
                                                <?php $opentimeholiday =   json_decode($holidayrow->custom_time); ?>                          
                                                <tr id="tblholi_<?php echo $holidayrow->id; ?>">
                                                    <td>
                                                        <div class="col-md-12 border-bottom ">
                                                            <input type="hidden" name="templateid" value="{{ $holidayrow->template_id }}">
                                                            <input type="date" name="custom_date" value="{{ $holidayrow->custom_date }}" class="form-control  border-0" autocomplete=off>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="col-md-12 border-bottom ">
                                                            <input type="text" name="note" value="{{ $holidayrow->note }}" placeholder="Add holiday title" class="form-control border-0" autocomplete=off>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="day">
                                                            <?php foreach($days as $rowval){ ?>
                                                            <option value="<?php echo $rowval ?>" {{ $rowval == $holidayrow->day ? 'selected' : '' }}>
                                                            <?php echo $rowval ?>
                                                            </option>
                                                            <?php } ?>
        
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <?php foreach($opentimeholiday as $extratime){?>
                                                            <select class="form-control" name="time_hours_min[]">
                                                                <?php foreach($timeValue as $rowtime){?>
                                                                    <option value="{{ $rowtime }}" {{ $rowtime == $extratime->open_time ? 'selected' : '' }}> {{ $rowtime }}</option>
                                                                <?php } ?>
                                                            </select>
                                                        <?php } ?>
                                                        
                                                    </td>
                                                    <td>
                                                        <?php foreach($opentimeholiday as $extratime){?>
                                                            <select class="form-control" name="time_hours_max[]">
                                                                <?php foreach($timeValue as $rowtime){?>
                                                                    <option value="{{ $rowtime }}" {{ $rowtime == $extratime->close_time ? 'selected' : '' }}> {{ $rowtime }}</option>
                                                                <?php } ?>
                                                            </select>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <select name="closed" class="form-control border-0"> 
                                                            <option value="No" {{ 'No' == $holidayrow->closed ? 'selected' : '' }}>No</option>
                                                            <option value="Yes" {{ 'Yes' == $holidayrow->closed ? 'selected' : '' }}>Yes</option>
                                                        </select>
                                                    </td>
                                                    
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table> 
                                    <?php } ?>
                                     
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
    


    
@endsection
