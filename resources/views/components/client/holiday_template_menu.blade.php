<?php $activeURL = explode("/",$_SERVER["REQUEST_URI"]); ?>
<div class="col-lg-3">
    <div class="row">	
        @if(Helper::getFeatureUses('schedule-all') == true)
        <ul class="list-group">
            @if(Helper::getFeatureUses('schedule-normal') == true)
            <li class="list-group-item @if(in_array('normal', $activeURL)) activemenu @endif">
                <a href="{{ url('company/normal/list-template') }}">Normal Holiday Template</a>
            </li>
            @endif
            <li class="list-group-item @if(in_array('public', $activeURL)) activemenu @endif">
                <a href="{{ url('company/public/list-template') }}">Public Holiday Template</a>
            </li>
            <li class="list-group-item @if(in_array('exception', $activeURL)) activemenu @endif">
                <a href="{{ url('company/exception/list-template') }}">Exception Holiday Template</a>
            </li>
        </ul>
       @endif             
    </div>
</div>	