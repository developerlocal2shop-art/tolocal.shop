<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Mail\OfferReplyMail;
use App\Mail\PrizeCodeVendorMail;
use App\Mail\ShoppingWinnerStatusMail;
use App\Models\Advertisement;
use App\Models\AdvertisementAnswers;
use App\Models\Affiliate;
use App\Models\CompetitionWinners;
use App\Models\CustomerClubs;
use App\Models\Frontendsetting;
use App\Models\Instruction;
use App\Models\OfferWinner;
use App\Models\ShoppingCard;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManagerStatic as Image;

class AccountController extends Controller
{

    public function profile(){

        if (Auth::check()){

            $data = array();
            $user_id = Auth::user()->id;
            $myPrizesCount = CompetitionWinners::with('advertisement')->where('customer_id', auth()->id())->count();
            $customerClubSellerIds = CustomerClubs::where('customer_id', Auth::id())->pluck('seller_id')
                ->filter(function ($id) {
                    return !empty($id) && $id != 0;
                })->unique()->values();
            $advertisement = Advertisement::where('start_date', '<=', now())->where(DB::raw('DATE_ADD(start_date, INTERVAL 5 DAY)'), '>=', now())
                ->whereIn('directory_id', $customerClubSellerIds)
                ->with('questions')
                ->get();

            $affiliate = Affiliate::where('user_id',auth()->id())->first();

            if(isset($_GET['unsubscribe']) == 'true' ){
                CustomerClubs::where('customer_id', $user_id)->where('seller_id', $_GET['sellerid'])->update(['opt_out' => '0']);
                $subscribe_status = "You've been successfully unsubscribed from the customer club list.";
            }else{
                $subscribe_status = '';
            }
            $data['userData']  = DB::table('users')->select('id','name','last_name','email','password','role_id')->where('id',$user_id)->first();



            return view('account.profile', compact('data', 'affiliate', 'subscribe_status', 'myPrizesCount', 'advertisement', 'affiliate'));

        }
    }

    public function questionnaire(){

        if (Auth::check()){
            $setting = Frontendsetting::first();
            $expiryDays = $setting->default_competition_expiry;
            $now = now();

            $advertisement = Advertisement::where('end_date', '>=', $now)
                ->with(['questions', 'competitionVendors'])
                ->where('deleted', '0')
                ->orderBy('id', 'desc')
                ->get();
            $countPrizes = CompetitionWinners::where('customer_id', auth()->id())->count();
            return view('account.user_advertisement', ['data' => $advertisement, 'expiryDays' => $expiryDays, 'countPrizes' => $countPrizes]);
        }else{
            return redirect()->route('pages');
        }
    }

    public function userprizes(){
        if (Auth::check()){
            $myCompetitionPrizes = CompetitionWinners::with('advertisement')->with('answer')
                        ->where('customer_id', auth()->id())->orderBy('created_at', 'desc')->get();

            $myShoppingPrizes = OfferWinner::where('customer_id', auth()->id())->orderBy('created_at', 'desc')->get();

            return view('account.userprizes', compact('myCompetitionPrizes', 'myShoppingPrizes'));
        }else{
            return redirect()->route('pages');
        }
    }

    public function shoppingValidOffer(Request $request)
    {

        // $to = 'skcreativeweb@gmail.com';
        // $subject = 'Test Email from ToLocal.shop';
        // $message = "Hello,
        //             This is a test plain-text email sent from Laravel using SendGrid.
        //             This email does not use any HTML or Blade template.
        //             Regards,ToLocal.shop";

        // Mail::raw($message, function ($mail) use ($to, $subject) {
        //     $mail->to($to)
        //         ->subject($subject);
        // });

        if (!Auth::check()) {
            return redirect()->route('pages');
        }

        $today = date('Y-m-d');
        $fromDate = $request->input('fromdate');
        $toDate = $request->input('todate');
        $searchtext = $request->input('searchtext', '');

        if($request->company){
            $company_id = decrypt($request->company);
        }else{
            $company_id = '';
        }

        //dd($company_id);
        $query = $request->all();

        if (!empty($company_id)) {
            $shoppingcardQuery = ShoppingCard::where('directory_link', $company_id)->where('deleted', '0')->where('expire_date', '>=', $today);
            $vendorIds = [$company_id];
        }else{
            $vendorIds = CustomerClubs::where('customer_id', Auth::id())->pluck('seller_id')
                ->filter(function ($id) {
                    return !empty($id) && $id != 0;
                })->unique()->values()->toArray();

            $shoppingcardQuery = ShoppingCard::where('deleted', '0')->where('expire_date', '>=', $today);
            if (!empty($vendorIds)) {
                $shoppingcardQuery->whereIn('directory_link', $vendorIds);
            }
        }


        if ($searchtext !== '') {
            $shoppingcardQuery->where(function ($query) use ($searchtext) {
                $query->where('title', 'like', "%{$searchtext}%")
                    ->orWhere('description', 'like', "%{$searchtext}%");
            });
        }
        if ($fromDate && $toDate) {
            $shoppingcardQuery->whereBetween('expire_date', [$fromDate, $toDate]);
        } else {
            if ($fromDate) {
                $shoppingcardQuery->where('expire_date', '>=', $fromDate);
            }
            if ($toDate) {
                $shoppingcardQuery->where('expire_date', '<=', $toDate);
            }
        }
        $shoppingcarddata = $shoppingcardQuery->orderBy('id', 'desc')->paginate(20);



        $baseQuery = ShoppingCard::where('deleted', '0');
        if (!empty($vendorIds)) {
            $baseQuery->whereIn('directory_link', $vendorIds);
        }

        $totalCount = (clone $baseQuery)->where('expire_date', '>=', $today)->count();
        $ongoingCount = (clone $baseQuery)->where('active_date', '<=', $today)->where('expire_date', '>=', $today)->count();
        $expiredCount = (clone $baseQuery)->where('expire_date', '<', $today)->count();
        $upcomingCount = (clone $baseQuery)->where('active_date', '>', $today)->count();

        return view('account.shopping_valid_offer', compact(
            'shoppingcarddata',
            'searchtext',
            'company_id',
            'fromDate',
            'toDate',
            'query',
            'totalCount',
            'ongoingCount',
            'expiredCount',
            'upcomingCount'
        ));
    }

    public function sendOfferReply(Request $request)
    {

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login first.'], 401);
        }

        $validated = $request->validate([
            'offer_id' => 'required|integer',
            'vendor_id' => 'nullable|integer',
            'reply_description' => 'nullable|string',
            'prize_amount' => 'nullable|numeric',
            'voucher_code' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            //'action_status' => 'nullable|in:store,online',
        ]);

        $shoppingCard = ShoppingCard::find($validated['offer_id']);
        if (!$shoppingCard) {
            return response()->json(['success' => false, 'message' => 'Shopping offer not found.'], 404);
        }

        $prizeAmount = $shoppingCard->discount_firstvalue ?? 0;
        if($shoppingCard->discount_selectedvalue == 'percentagediscount'){
            $discount_type = 'percentage';
        }else{
            $discount_type = 'fixed';
        }

        do {
            $voucherCode = Helper::generatePrizeCode();
        } while (
            DB::table('offers_winner')->where('voucher_code', $voucherCode)->exists()
        );

        $insertId = DB::table('offers_winner')->insertGetId([
            'offer_id' => $validated['offer_id'],
            'customer_id' => Auth::id(),
            'vendor_id' => $validated['vendor_id'] ?? null,
            'description' => trim($validated['reply_description']),
            'discount_type' => $discount_type,
            'prize_amount' => $prizeAmount,
            'voucher_code' => $voucherCode,
            'type' => 'shoppingcard',
            'status' => '1',
            'action_status' => 'store',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $customer = Auth::user();
        $vendorUser = User::where('directory_id', $validated['vendor_id'])->first();

        $mailData = [
            'customer_name' => trim(($customer->name ?? '') . ' ' . ($customer->last_name ?? '')) ?: ($customer->name ?? 'Customer'),
            'customer_email' => $customer->email ?? null,
            'vendor_name' => $vendorUser ? trim(($vendorUser->name ?? '') . ' ' . ($vendorUser->last_name ?? '')) ?: ($vendorUser->name ?? 'Vendor') : 'Vendor',
            'vendor_email' => $vendorUser->email ?? null,
            'offer_title' => $shoppingCard->title ?? 'Shopping card offer',
            'description' => trim($validated['reply_description']),
            'prize_type' => $shoppingCard->discount_selectedvalue ?? 0,
            'prize_amount' => $shoppingCard->discount_firstvalue ?? 0,
            'voucher_code' => $voucherCode,
            'status' => 'Pending',
        ];

        if (!empty($customer->email)) {
            Mail::to($customer->email)->send(new OfferReplyMail($mailData, 'customer'));
        }
        if ($vendorUser && !empty($vendorUser->email)) {
            Mail::to($vendorUser->email)->send(new OfferReplyMail($mailData, 'vendor'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Offer replied successfully.',
            'id' => $insertId,
        ]);
    }

    public function changeWinnerStatus(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        $validated = $request->validate([
            'id' => 'required|integer|exists:competition_winners,id',
            'status' => 'required|in:0,1',
        ]);
        $prize = CompetitionWinners::where('id', $validated['id'])
            ->where('customer_id', Auth::id())
            ->first();
        $company_competition_prize = Helper::getDirectoryIdSlug($prize->vendor_id);
        if (!$prize) {
            return response()->json(['success' => false, 'message' => 'Prize not found'], 404);
        }
        if ($prize->status == 0 && $validated['status'] == 1) {
            return response()->json(['success' => false, 'message' => 'Used prizes cannot be reverted'], 422);
        }
        if ($prize->status == $validated['status']) {
            return response()->json(['success' => true, 'message' => 'Status already set', 'status' => $prize->status]);
        }
        $prize->prize_amount = $company_competition_prize->competition_discount;
        $prize->type   = 'user';
        $prize->status = $validated['status'];
        $prize->save();
        return response()->json(['success' => true, 'message' => 'Status updated', 'status' => $prize->status]);
    }

    public function changeShoppingWinnerStatus(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        $validated = $request->validate([
            'id' => 'required',
            'status' => 'required|in:0,1',
        ]);
        $prize = OfferWinner::where('id', $validated['id'])->where('customer_id', Auth::id())->first();

        $company_competition_prize = Helper::getDirectoryIdSlug($prize->vendor_id);
        if (!$prize) {
            return response()->json(['success' => false, 'message' => 'Prize not found'], 404);
        }
        if ($prize->status == 0 && $validated['status'] == 1) {
            return response()->json(['success' => false, 'message' => 'Used prizes cannot be reverted'], 422);
        }
        if ($prize->status == $validated['status']) {
            return response()->json(['success' => true, 'message' => 'Status already set', 'status' => $prize->status]);
        }
        //$prize->prize_amount = $company_competition_prize->competition_discount;
        $prize->status = $validated['status'];
        if ($validated['status'] == 0) {
            $prize->redeem_date = now()->toDateTimeString();
        }
        $prize->save();

        $customer = $prize->customer;
        if ($customer && !empty($customer->email)) {
            $customerName = trim(($customer->name ?? '') . ' ' . ($customer->last_name ?? ''));
            $mailData = [
                'customer_name' => $customerName !== '' ? $customerName : 'Customer',
                'offer_title' => $prize->offer->title ?? 'Shopping card offer',
                'voucher_code' => $prize->voucher_code,
                'status' => $prize->status == 0 ? 'Used' : 'Available',
            ];

            Mail::to($customer->email)->send(new ShoppingWinnerStatusMail($mailData));
        }

        return response()->json(['success' => true, 'message' => 'Status updated', 'status' => $prize->status]);
    }

    public function advertisementQuestionnaire(Request $request){
        if (Auth::check()){
            $user_id = Auth::user()->id;
            $now = now();

            $advertisementId = $request->advertisementid;
            $advertisement = Advertisement::where('end_date', '>=', $now)
                ->with(['questions', 'competitionVendors'])
                ->where('id', $advertisementId)
                ->first();

            //dd($advertisement);

            $is_competitionWinners = CompetitionWinners::where('advertisement_id', $request->advertisementid)->where('customer_id', $user_id)->count();

            return view('account.questionnaire', ['data' => $advertisement, 'advertisementId' => $advertisementId, 'is_competitionWinners' => $is_competitionWinners]);
        }else{
            return redirect()->route('pages');
        }
    }

    public function store(Request $request, $advertisementId){

        if (Auth::check()){

            // $request->validate([
            //     'questions' => 'required|array|min:1',
            //     'questions.*.question_id' => 'required|exists:advertisement_questions,id',
            //     'questions.*.answer_text' => [
            //         'required',
            //         'string',
            //         'max:1000'
            //     ],
            // ], [
            //     'questions.*.answer_text.required' => 'Please answer this question.',
            // ]);

            $request->validate([
                'questions' => 'required|array|min:1',
                'questions.*.question_id' => 'required|exists:advertisement_questions,id',
                'questions.*.answer_text' => [
                    'nullable','string','max:1000'
                ],
            ]);

            foreach ($request->questions as $key => $data) {
                if (!empty($data['answer_text'])) {
                    AdvertisementAnswers::updateOrCreate(
                        [
                            'advertisement_id' => $advertisementId,
                            'customer_id'      => auth()->id(),
                            'question_id'      => $data['question_id'],
                        ],
                        [
                            'answer'    => $data['answer_text'],
                            'vendor_id' => $request->directory_id[$key] ?? null,
                        ]
                    );
                }
            }
            return redirect(url('account/advertisementquestionnaire?advertisementid=' . $advertisementId))->with('success', 'Your answers have been submitted!');

        }else{
            return redirect()->route('pages');
        }
    }

    public function questionnaireReply(Request $request)
    {
        //dd($request->all());
        if (!Auth::check()) {
            return response()->json(['status'  => 'error', 'message' => 'Unauthorized'], 401);
        }
        $request->validate([
            'competition_id'   => 'required|integer',
            'question_id'      => 'required|integer',
            //'reply_description'=> 'required|string|max:2000',
        ]);
        // try {

            $competition = Advertisement::findOrFail($request->competition_id);
            $answer = AdvertisementAnswers::where('advertisement_id', $request->competition_id)
                        ->where('question_id', $request->question_id)
                        ->where('customer_id', Auth::id())
                        ->first();

            if (!$answer) { return response()->json(['status'  => 'error','message' => 'Answer not found.'], 404); }
            do {
                $prizeCode = Helper::generatePrizeCode();
            } while (
                AdvertisementAnswers::where('prize_code', $prizeCode)->exists() ||
                CompetitionWinners::where('voucher_code', $prizeCode)->exists()
            );

            $auto_response_msg = 'you will get exclusive discout by using prize code on physical shop';

            // Update answer
            $answer->update([
                'is_correct'        => 1,
                'prize_code'        => $prizeCode,
                'exp_date'          => $competition->end_date,
                'reply_description' => $request->reply_description??'',
                'auto_reply_response' => $auto_response_msg,
                'auto_reply_status' => '1',
            ]);

            // Save winner
            CompetitionWinners::create([
                'advertisement_id'  => $answer->advertisement_id,
                'question_id'       => $answer->question_id,
                'customer_id'       => $answer->customer_id,
                'vendor_id'         => $answer->vendor_id,
                'prize_description' => $answer->prize_description,
                'voucher_code'      => $prizeCode,
                'prize_amount'      => $request->discount_amount,
                'type'              => 'user'
            ]);

            $user = User::findOrFail($answer->customer_id);

            // Send email
            $prizeData = [
                'user_email'        => $user->email,
                'exp_date'          => $competition->end_date,
                'discount'          => $competition->price,
                'directory_id'      => $answer->vendor_id,
                'prizecode'         => $prizeCode,
                'prize_description' => $auto_response_msg,
            ];
            Mail::to($user->email)->send(new PrizeCodeVendorMail($prizeData));

            return response()->json([
                'status'         => 'success',
                'message'        => 'Reply submitted successfully.',
                'competition_id' => $request->competition_id,
                'question_id'    => $request->question_id,
            ]);

        // } catch (\Exception $e) {

        //     return response()->json([
        //         'status'  => 'error',
        //         'message' => $e->getMessage()
        //     ], 500);
        // }
    }

    // public function questionnaireReply(Request $request)
    // {

    //     dd(Auth::id());

    //     if (!Auth::check()) {
    //         return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
    //     }

    //     $request->validate([
    //         'competition_id' => 'required|integer',
    //         'question_id' => 'required|integer',
    //         'reply_description' => 'required|string|max:2000',
    //     ]);

    //     $competition_data = Advertisement::find($request->competition_id);
    //     do {
    //         $prizeCode = Helper::generatePrizeCode();
    //     } while (
    //         AdvertisementAnswers::where('prize_code', $prizeCode)->exists() ||
    //         CompetitionWinners::where('voucher_code', $prizeCode)->exists()
    //     );
    //     dd($competition_data);

    //     AdvertisementAnswers::where('advertisement_id', $request->competition_id)
    //             ->where('question_id', $request->question_id)
    //             ->where('customer_id', Auth::id())
    //             ->update([
    //                 'is_correct' => 1,
    //                 'prize_code' => $prizeCode,
    //                 'exp_date'   => $competition_data->end_date,
    //             ]);

    //     /* Save winner */
    //     CompetitionWinners::create([
    //         'advertisement_id'  => $winnerAnswer->advertisement_id,
    //         'question_id'       => $winnerAnswer->question_id,
    //         'customer_id'       => $winnerAnswer->customer_id,
    //         'vendor_id'         => $winnerAnswer->vendor_id,
    //         'prize_description' => $prizeDescription,
    //         'voucher_code'      => $prizeCode,
    //     ]);

    //     /* Send prize email */
    //     $prizeData = [
    //         'user_email'        => $user->email,
    //         'exp_date'          => $expiryDate,
    //         'discount'          => $advertisement->price,
    //         'directory_id'      => $winnerAnswer->vendor_id,
    //         'prizecode'         => $prizeCode,
    //         'prize_description' => $prizeDescription,
    //     ];
    //     Mail::to($user->email)->send(new PrizeCodeVendorMail($prizeData));

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Reply submitted successfully.',
    //         'competition_id' => $request->competition_id,
    //         'question_id' => $request->question_id,
    //     ]);
    // }

    public function uploadProfilePic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
        }

        $user = Auth::user();

        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');

            $ext = $file->getClientOriginalExtension();
            $imageName = Helper::getSeoUrl(time() . $file->getClientOriginalName()) . '.' . $ext;
            $imagePath = "profile_pictures/{$imageName}";

            // Convert without resizing (set quality to 80 for better balance)
            $imageStream = Image::make($file)->encode($ext, 80);
            Storage::disk('images')->put($imagePath, $imageStream);
            // Update user profile
            $user->profile_pic = $imagePath;
            $user->save();

            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Profile photo updated!',
                'path' => asset('storage/' . $imagePath) // Corrected variable
            ]);
        }


        return response()->json(['success' => false, 'message' => 'File upload failed!']);
    }

    public function submitProfile(Request $request){
        $user_id = Auth::user()->id;

        $this->validate($request, [
            'name'          => 'required',
            'last_name'     => 'required',
            'email'         => 'required',
            'phone'         => 'required|numeric|digits_between:9,10',
        ]);
        $datauserData  = DB::table('users')->select('*')->where('email',$request->email)->first();
		if($request->email!=$request->preemail){
            $datauserData  = DB::table('users')->select('*')->where('email',$request->email)->first();
            $datauserCountData  = DB::table('users')->select('*')->where('email',$request->email)->count();
            if($datauserCountData!=0){
                return back()->with('error', '" '.$request->email.' " mail id already exist!');
            }else{
                DB::table('users')->where('id', $user_id)
                ->update([
                    'name'          => $request->name,
                    'last_name'     => $request->last_name ,
                    'email'         => $request->email,
                    'is_verified'   => $request->is_verified??'',
                ]);
                DB::table('directory_contact')->where('directory_id', Auth::user()->directory_id)
                ->update([
                    'email'         => $request->email,
                ]);
                return back()->with('profilemsg', 'Profile updated successfully!');
            }
        }else{
            DB::table('users')->where('id', $user_id)
            ->update([
                'name'          => $request->name,
                'last_name'     => $request->last_name ,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'is_verified'   => $request->is_verified??'',
            ]);
            return back()->with('profilemsg', 'Profile updated successfully!');
        }
    }

    public function reset_password(){
        if (Auth::check()){
            $user_id = Auth::user()->id;
            $data['userData']  = DB::table('users')->select('id','name','email','password','role_id')->where('id',$user_id)->first();
            return view('account.password', ['data' => $data]);
        }else{
            return redirect()->route('pages');
        }
    }

    public function submitPassword(Request $request){
        $user_id = Auth::user()->id;
        $password = $request->input('password');
        $new_password = $request->input('new_password');
        $confirm_password = $request->input('confirm_password');

        $this->validate($request, [
            'password'          => 'required',
            'new_password'      => 'required|string|min:6',
            'confirm_password'  => 'required|same:new_password'
        ]);
        $user  = DB::table('users')->select('id','password')->where('id',$user_id)->first();
        if (!Hash::check($password, $user->password)) {
            return back()->with('error', 'The specified password does not match the database password');
        }else{
            DB::table('users')->where('id', $user_id)->update(['password' => Hash::make($request->new_password)]);
            return redirect('account/reset-password')->with('success', 'Your password has been changed!');
        }
    }

}
