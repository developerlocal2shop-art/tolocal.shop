<?php

namespace App\Http\Controllers\Admin\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use DB;
use App\Models\Booking;
use App\Models\TicketRequest;
use App\Models\User;
use App\Models\Country;
use App\Notifications\AcceptRequest;
use Illuminate\Support\Facades\Auth;
use Dotunj\LaraTwilio\Facades\LaraTwilio;
use Illuminate\Support\Facades\Session;
use App\Models\Contact;

class BookingControllers extends Controller
{
    public function index(Request $request){
        if($request->input('search')){
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $data['searchQuery']=$request->input('searchtext');
            $data['table_name']='appointment_booking';
            $data['columns']=['name','email'];
            $bookingdata = Helper::masterSearch($data);
            $searchtext = isset($searchQuery)?$request->input('searchtext'):"";
            return view('admin.booking.index',['bookingdata'=>$bookingdata, 'bookingsearch'=>$searchtext,'query'=>$query]);
        }else{
             $bookingdata = Booking::query()->orderBy('id', 'desc')->paginate(20);
             $searchtext = isset($searchQuery)?$request->input('searchtext'):"";
             return view('admin.booking.index',['bookingdata'=>$bookingdata, 'bookingsearch'=>$searchtext]);
        }
    }

    public function ticketRequestList(Request $request){
        if($request->input('search')){
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $data['searchQuery']=$request->input('searchtext');
            $data['table_name']='appointment_booking';
            $data['columns']=['name','email'];
            $bookingdata = Helper::masterSearch($data);
            $searchtext = isset($searchQuery)?$request->input('searchtext'):"";
            return view('admin.ticketrequest.index',['bookingdata'=>$bookingdata, 'bookingsearch'=>$searchtext,'query'=>$query]);

        }else{
             $bookingdata = TicketRequest::with('vendor')->orderBy('id', 'desc')->paginate(20);
             $searchtext = isset($searchQuery)?$request->input('searchtext'):"";
             return view('admin.ticketrequest.index',['bookingdata'=>$bookingdata, 'bookingsearch'=>$searchtext]);
        }
    }

    public function ticketview($id=0){
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('view') == false){
            abort(404);
        }
        $bookingDetails    = TicketRequest::with('vendor')->findOrFail($id);
       return view('admin.ticketrequest.edit', compact('bookingDetails'));
    }

    public function notifications()
    {
        $notifications = auth()->guard('webadmin')->user()->notifications()->get();
        return view('admin.ticketrequest.notifications', compact('notifications'));
    }

    public function accept($notificationId,$uid,$tid){

        $countryCode= session()->get('countrycode');
        $countryCodeData = session()->get('countrynamecode');
        $statusupdated = TicketRequest::where('id',$tid)->update(['status'=>'Active']);
        $TicketRequestD = TicketRequest::where('id',$tid)->first();
            //notify user for accepted connection request
            $requestedUser = User::find($uid);
            //ceate message
            $message = "Accepted your request for <b>".$TicketRequestD->title."</b> and your query was <b>".$TicketRequestD->query."</b>";
            //notify
            $requestedUser->notify(new AcceptRequest($message,$uid,1,$tid));
            //unread notifications  count for user to notify
            $unreadNotificationCount = User::find($requestedUser->id)->unreadNotifications->count();
            //create the event and notify realtime
            //event(new AcceptRequestNotification($message,$requestedUser,Auth::user()->id,$unreadNotificationCount));
            //$notificationId = request('notification_id');
        $userUnreadNotification = auth()->guard('webadmin')->user()
                                    ->unreadNotifications
                                    ->where('id', $notificationId)
                                    ->first();
        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
        }
        $mess = "Request accepted.";
        if($statusupdated){
            $msg = "Your Ticket Request accepted.";
            if(!empty($countryCodeData->phonecode)){
                $sendSms = LaraTwilio::notify('+'.$countryCodeData->phonecode.$requestedUser->phone, $msg);
            }else{
                $sendSms = LaraTwilio::notify('+'.'91'.$requestedUser->phone, $msg);
            }
            return redirect()->route('admin.notifications')->with('message', $mess);
        }else{
            return redirect()->route('admin.notifications')->with('error', 'Sorry try again.');
        }
    }
    public function changeTicketStatus(Request $request){
        $review = TicketRequest::find($request->id);
        $review->status = $request->status;
        $result = $review->save();
        return response()->json(['success'=>'Status change successfully.']);
    }
    public function ticketdestroy($id)
    {
        TicketRequest::where('id', $id)->delete();
        return redirect('admin/ticket/request/list')->with('msg','Deleted successfully');
    }
    public function edit($id=0){
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false){
            abort(404);
        }
        $data['bookingDetails']     = Booking::findOrFail($id);
       return view('admin.booking.edit', ['data'=>$data]);
    }
    public function update(Request $request,$id){
        Booking::where('id', $id)
            ->update([
                'name'                      => $request->input('booking_name'),
                'email'                     => $request->input('booking_email'),
                'adult'                     => $request->input('adult'),
                'child'                     => $request->input('child'),
                'message'                   => $request->input('message'),
                'status'                    => $request->input('status'),
                'booking_comment'           => $request->input('bookingComment'),
            ]);
        return redirect('admin/booking')->with('msg', 'Booking has been updated');
    }
    public function destroy($id)
    {
        Booking::where('id', $id)->delete();
        return redirect('admin/booking')->with('msg','Booking deleted successfully');
    }

    public function contactus(Request $request){
        if($request->input('search')){
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $data['searchQuery']=$request->input('searchtext');
            $data['table_name']='contact';
            $data['columns']=['first_name','last_name','email','message'];
            $contactdata = Helper::masterSearch($data);
            $searchtext = isset($searchQuery)?$request->input('searchtext'):"";
            return view('admin.contact.index',['contactdata'=>$contactdata, 'contactsearch'=>$searchtext,'query'=>$query]);
        }else{
             $contactdata = Contact::query()->orderBy('id', 'desc')->paginate(20);
             $searchtext = isset($searchQuery)?$request->input('searchtext'):"";
             return view('admin.contact.index',['contactdata'=>$contactdata, 'contactsearch'=>$searchtext]);
        }
    }
    public function checkedDeleteBooking(Request $request)
    {
        $ids = $request->ids;
        
        Booking::whereIn('id', explode(",", $ids))->delete();
                
        return response()->json(['success'=>'Records deleted successfully.']);
    }
}
