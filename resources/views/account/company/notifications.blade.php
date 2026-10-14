@extends('layouts.client.company')

@section('content')

<section class="profile-page1 vendor_notification_list">
    <div class="container">
        <div class="row">
			<div class="col-lg-12 title-sec">
                <h3><a href="{{ url('notifications') }}"><i class="fas fa-arrow-left"></i> Notifications</a></h3>
                <p>Notifications</p>
            </div>

        <ul>
            @foreach ($notifications as $notification)
            @if($notification->read_at == null)
            <li>
                <a href="{{ route('accept',[$notification->id,$notification->data['sender_id'],$notification->data['ticket_id']]) }}">
                    {{-- <p>{{ $val->users_map_usergroup->name }} invited your Group </p> Accept --}}
                    <div class="msg-content">
                        <h4>Admin</h4>
                        <p>
                            <strong>Admin</strong>
                            {!! $notification->data['message'] !!}
                        </p>

                    <p>
                            @if($notification->created_at->diffInSeconds(Carbon\Carbon::now()) < 60)
                            {{$notification->created_at->diffInSeconds(Carbon\Carbon::now())}} Seconds Ago
                            @endif
                        </p>
                    <p>
                            @if($notification->created_at->diffInMinutes(Carbon\Carbon::now()) < 60  && $notification->created_at->diffInMinutes(Carbon\Carbon::now()) > 1)
                            {{$notification->created_at->diffInMinutes(Carbon\Carbon::now())}} Minutes Ago
                            @endif
                        </p>
                        <p>
                            @if($notification->created_at->diffInHours(Carbon\Carbon::now()) >= 1 && $notification->created_at->diffInHours(Carbon\Carbon::now()) < 24)
                            {{$notification->created_at->diffInHours(Carbon\Carbon::now())}} Hours Ago
                            @endif
                        </p>

                       <p>
                            @if($notification->created_at->diffInHours(Carbon\Carbon::now()) > 24 )  {{$notification->created_at->diffInDays(Carbon\Carbon::now())}} Days Ago
                            @endif
                       </p>
                       <h6>Ok</h6>
                    </div>
                </a>

            </li>
            @endif
            @endforeach
        </ul>

        </div>
    </div>
</section>


@endsection

@push('scripts')

@endpush
