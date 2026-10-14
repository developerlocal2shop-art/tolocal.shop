<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Mail\RequestTicket;
use App\Models\Admin;
use DB;
use App\Models\Booking;
use App\Models\TicketRequest;
use App\Models\User;
use App\Models\Country;
use App\Models\Instruction;
use App\Notifications\SentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Dotunj\LaraTwilio\Facades\LaraTwilio;
use App\Mail\BookingMailUser;
use App\Mail\AdminCopyBookingMail;
use App\Models\ReplyRequest;

class CompanyBookingControllers extends Controller
{
    public function index(Request $request)
    {
        if (Helper::getFeatureUses('booking') == false) {
            return abort(404);
        }
        if (Auth::user()->profile_status == 0) {
            return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
        } else {
            if ($request->input('search')) {
                $query = $request->all();
                $searchQuery = $request->input('searchtext');
                $data['searchQuery'] = $request->input('searchtext');
                $data['table_name'] = 'appointment_booking';
                $data['columns'] = ['name', 'email'];
                $bookingdata = Helper::companymasterSearch($data);
                $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
                $user = User::find(auth()->user()->id);
                return view('account.company.booking.index', ['bookingdata' => $bookingdata, 'bookingsearch' => $searchtext, 'query' => $query, 'userData' => $user]);
            } else {
                $bookingdata = Booking::query()->where('directory_id', Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
                $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
                $user = User::find(auth()->user()->id);
                return view('account.company.booking.index', ['bookingdata' => $bookingdata, 'bookingsearch' => $searchtext, 'userData' => $user]);
            }
        }
    }

    public function requestNewticket()
    {
        if (Auth::check()) {
            $directory_id = Auth::user()->directory_id;
            $instruction  = Instruction::select('message')->where('section_name', 'openticket')->where('status','Active')->first(); /* get instructionData */
            return view('account.company.open-ticket.index', compact('instruction'));
        } else {
            return abort(404);
        }
    }

    public function accept($notificationId, $uid, $tid)
    {
        $userUnreadNotification = auth()->guard('web')->user()
            ->unreadNotifications
            ->where('id', $notificationId)
            ->first();
        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
        }
        return redirect()->back();
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->get();
        return view('account.company.notifications', compact('notifications'));
    }

    public function submitNewticket(Request $request)
    {
        $countryCode = session()->get('countrycode');
        $countryCodeData = session()->get('countrynamecode');
        try {
            $contact = new TicketRequest();
            $contact->directory_id    = Auth::user()->directory_id;
            $contact->title           = $request->input('title');
            $contact->email            = $request->input('email');
            $contact->phone            = $request->input('phone');
            $contact->query            = $request->input('query');
            $contact->save();
            $TicketId = '00#' . $contact->id;
            $requestedUser = Admin::find(1);
            $message = "Vendor sent a request for " . $request->input('title');
            $requestedUser->notify(new SentRequest($message, Auth::user()->id, Auth::user()->directory_id, $contact->id));
            $msg = "Vendor sent a request for " . $request->input('title');
            if (!empty($countryCodeData->phonecode)) {
                $sendSms = LaraTwilio::notify('+' . $countryCodeData->phonecode . '7678409612', $msg);
            } else {
                $sendSms = LaraTwilio::notify('+' . '91' . '7678409612', $msg);
            }
            //unread notifications  count for user to notify
            $unreadNotificationCount = User::find($requestedUser->id)->unreadNotifications->count();
            return redirect('company/request/newticket')->with('msg', 'Request Sent Successfully! Your ticket ID is <b>' . $TicketId . '</b>');
        } catch (Exception $e) {
            return redirect('contact')->with('error', 'Something went wrong. Try again!');
        }
    }

    public function edit($id = 0)
    {
        Booking::where('id', $id)->update([ 'notify' => '1' ]);

        if (Helper::getFeatureUses('booking') == false) {
            return abort(404);
        }
        $user = auth()->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        $data['bookingDetails']     = Booking::findOrFail($id);
        return view('account.company.booking.edit', ['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $phoneCode = $request->input('booking_phone_code'); 
        $bookingPhone = $request->input('booking_phone');
        $usermsg = $request->input('bookingComment');
        Booking::where('id', $id)
            ->update([
                'directory_id'              => $request->input('directory_id'),
                'name'                      => $request->input('booking_name'),
                'email'                     => $request->input('booking_email'),
                'adult'                     => $request->input('adult'),
                'child'                     => $request->input('child'),
                'message'                   => $request->input('message'),
                'status'                    => $request->input('status'),
                'booking_comment'           => $request->input('bookingComment'),
            ]);

            Mail::to(env('APP_EMAIL'))->send(new AdminCopyBookingMail($request->all()));
            Mail::to($request->input('booking_email'))->send(new BookingMailUser($request->all()));
                        
            $sendSms = LaraTwilio::notify('+' . $phoneCode . $bookingPhone, $usermsg);
           
        return redirect('company/booking')->with('msg', 'Booking has been updated');
    }
    public function destroy($id)
    {
        Booking::where('id', $id)->delete();
        return redirect('company/booking')->with('msg', 'Booking deleted successfully');
    }
    public function changeVisibilityDateTimeStatus(Request $request){
        $userId = auth()->user()->id;
        $user = User::find($userId);
        $user->visibility_booking_date_time = $request->status;
        $user->save();
        return response()->json(['success'=>'Status change successfully.']);
    }

    public function getRepliedData(Request $request){
        $ReplyRequestData = ReplyRequest::where('request_id',$request->dataid)->where('company_id',Auth::user()->directory_id)->first();
        if($ReplyRequestData){
            return response()->json(['success'=>'true', 'data' => $ReplyRequestData]);
        }else{
            return response()->json(['success'=>'false', 'data' => '']);
        }
        
    }

    public function deleteBookingChecked(Request $request)
    {
        $ids = $request->ids;
        
        Booking::whereIn('id', explode(",", $ids))->delete();
                
        return response()->json(['success'=>'Records deleted successfully.']);
    }
}
