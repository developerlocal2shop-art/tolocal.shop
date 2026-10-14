<?php

namespace App\Http\Controllers\Account;

use App\Exports\CompetitionAnswerExportVendor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Mail\PrizeCodeMail;
use App\Mail\PrizeCodeVendorMail;
use App\Models\Advertisement;
use App\Models\AdvertisementAnswers;
use App\Models\AdvertisementQuestions;
use App\Models\CompetitionVendors;
use App\Models\CompetitionWinners;
use App\Models\Diectory;
use App\Models\DiectoryLocation;
use App\Models\Frontendsetting;
use App\Models\User;
use App\Models\VendorCompetitionList;
use App\Models\VendorCompetitionStatus;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\ImageManagerStatic as Image;
class CompanyAdvertisementController extends Controller
{
    public function advertisementList(Request $request)
    {
        if (Auth::check()) {

           //$userData = User::with('permissions')->with('modules')->with('UseFeatures')->find(Auth::id());
           //dd($userData);

            if (Helper::getFeatureUses('advertisment') == false) {
                return abort(404);
            }
            $searchtext = $request->searchtext ?? '';
            $directoryId = Auth::user()->directory_id;

            $advertisement = Advertisement::query()
                ->whereRaw('FIND_IN_SET(?, adminassignvendor)', [$directoryId])
                ->where('deleted', '0')
                ->orderBy('id', 'desc')

                ->when($searchtext, function ($query) use ($searchtext) {
                    $query->where(function ($q) use ($searchtext) {
                        $q->where('heading', 'like', "%{$searchtext}%")
                        ->orWhere('mid_text', 'like', "%{$searchtext}%")
                        ->orWhereHas('questions', function ($sub) use ($searchtext) {
                            $sub->where('question_text', 'like', "%{$searchtext}%");
                        });
                    });
                })
                ->with(['questions' => function ($q) use ($searchtext) {
                    if ($searchtext) {
                        $q->where('question_text', 'like', "%{$searchtext}%");
                    }
                }])
                ->paginate(20);

                //dd($advertisement);

            return view('account.company.advertisement.index', compact('advertisement','searchtext'));
        } else {
            return abort(404);
        }
    }

    public function advertisementVendorQuestionnaire(Request $request){

        if (Auth::check()){
            $user_id = Auth::user()->id;

            $setting = Frontendsetting::first();
            $expiryDays = $setting->default_competition_expiry;
            $now = now();

            $directoryId = Auth::user()->directory_id;
            $advertisement = Advertisement::where('start_date', '<=', $now)
                ->whereRaw('DATE_ADD(start_date, INTERVAL ? DAY) >= ?', [$expiryDays, $now])
                //where('start_date', '<=', now())->where(DB::raw('DATE_ADD(start_date, INTERVAL 15 DAY)'), '>=', now())
                ->where(function($query) use ($directoryId) {
                    $query->WhereRaw("FIND_IN_SET(?, adminassignvendor)", [$directoryId]);
                })
                ->with('questions')
                ->find($request->advertisementid);

            $is_competitionWinners = CompetitionWinners::where('advertisement_id', $request->advertisementid)->where('customer_id', $user_id)->count();

            return view('account.company.advertisement.questionnaire', ['data' => $advertisement, 'is_competitionWinners' => $is_competitionWinners]);
        }else{
            return redirect()->route('pages');
        }
    }

    public function store(Request $request, $advertisementId){

        if (Auth::check()){

            $request->validate([
                'questions.*.question_id' => 'required|exists:advertisement_questions,id',
                'questions.*.answer_text' => 'required|string|max:1000',
            ]);

            foreach ($request->questions as $data) {
                AdvertisementAnswers::create([
                    'advertisement_id' => $advertisementId,
                    'customer_id'      => auth()->id(),
                    'question_id'      => $data['question_id'],
                    'answer'           => $data['answer_text'],
                    'vendor_id'        => 0,
                ]);
            }
            return redirect()->route('vendor.questionnaire')->with('success', 'Your answers have been submitted!');

        }else{
            return redirect()->route('pages');
        }
    }

    public function companyaddadvertisement()
    {
        if (Auth::check()) {
            if (Helper::getFeatureUses('advertisment') == false) {
                return abort(404);
            }
            return view('account.company.advertisement.create');
        } else {
            return abort(404);
        }
    }

    public function vendorquestionnaire(){
        if (Auth::check()){
            $directoryId = Auth::user()->directory_id;
            $setting = Frontendsetting::first();
            $expiryDays = $setting->default_competition_expiry;
            $now = now();

            $advertisement = Advertisement::where('start_date', '<=', $now)
                ->whereRaw('DATE_ADD(start_date, INTERVAL ? DAY) >= ?', [$expiryDays, $now])
                ->where(function ($query) use ($directoryId) {
                    $query->whereRaw('FIND_IN_SET(?, adminassignvendor)', [$directoryId]);
                })
                ->with('questions')
                ->get();
            return view('account.company.advertisement.vendor_advertisement', ['data' => $advertisement]);
        }else{
            return redirect()->route('pages');
        }
    }

    public function updateCompetitionStatus(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'competition_id' => 'required|integer',
            'status' => 'required|in:Active,Deactive',
        ]);

        $companyId = Auth::user()->directory_id ?? 0;
        $competitionId = $request->competition_id;
        $status = $request->status;

        VendorCompetitionStatus::updateOrInsert(
            [
                'company_id' => $companyId,
                'competition_id' => $competitionId,
            ],
            [
                'status' => $status,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json(['success' => 'Status updated successfully.']);
    }

    public function updateAnswerStatusbyId(Request $request)
    {
        $advertisementAnswers = AdvertisementAnswers::find($request->id);
        $advertisementAnswers->is_correct = $request->is_correct;
        $is_success = $advertisementAnswers->save();
        if($is_success){
            return response()->json(['success'=>'Status change successfully.']);
        }else{
            return response()->json(['error'=>'Something went wrong. please try again']);
        }
    }

    // public function addAnswerStatusPrizebyId(Request $request)
    // {

    //     $getUser = User::find($request->customer_id);
    //     $get_advertisement = Advertisement::find($request->advertisement_id);
    //     $expiredate =  $request->exp_date;

    //     $prizeDataAll = [
    //                         'user_email' => $getUser->email,
    //                         'exp_date' => $expiredate,
    //                         'discount' => $get_advertisement->price,
    //                         'directory_id' => Auth::user()->directory_id,
    //                         'prizecode' => $request->prize_code,
    //                         'prize_description' => $request->prize_description,
    //                     ];

    //         $CompetitionWinners                       = new CompetitionWinners();
    //         $CompetitionWinners->advertisement_id     = $request->input('advertisement_id');
    //         $CompetitionWinners->customer_id          = $request->input('customer_id');
    //         $CompetitionWinners->vendor_id            = Auth::user()->directory_id;
    //         $CompetitionWinners->prize_description    = $request->input('prize_description');
    //         $CompetitionWinners->voucher_code         = $request->input('prize_code');
    //         $CompetitionWinners->save();

    //         $advertisementAnswers = AdvertisementAnswers::find($request->id);
    //         $advertisementAnswers->is_correct        = $request->is_correct;
    //         $advertisementAnswers->exp_date          = isset($expiredate) ? $expiredate : date("Y-m-d");
    //         $advertisementAnswers->prize_description = $request->prize_description;
    //         $advertisementAnswers->prize_code        = $request->prize_code;
    //         $is_success = $advertisementAnswers->save();

    //     Mail::to($getUser->email)->send(new PrizeCodeMail($prizeDataAll));

    //     if($is_success){
    //         return response()->json(['success'=>'Prize added successfully.']);
    //     }else{
    //         return response()->json(['error'=>'Something went wrong. please try again']);
    //     }
    // }

    public function getAutoinnerPrizebyId(Request $request){

        $getAutoinner = CompetitionWinners::with('advertisement')->with('answer')->with('user')->with('question')->where('type','auto')->where('vendor_id',Auth::user()->directory_id)->where('question_id',$request->questionId)->where('advertisement_id',$request->advertisement_id)->first();
        //dd($getAutoinner);
        return $getAutoinner;
    }

    public function deleteQueById(Request $request){

        $isAxistAns = AdvertisementAnswers::where('advertisement_id', $request->advertisement_id)
                         ->where('question_id', $request->questionId)
                         ->where('vendor_id', auth()->user()->directory_id ?? 0)
                         ->count();
        if($isAxistAns == 1){
            return response()->json(['success' => false, 'msg' => 'Answer exists! Cannot delete this.']);
        }else{
            $isDelete = AdvertisementQuestions::where('advertisement_id', $request->advertisement_id)
                            ->where('id', $request->questionId)
                            ->where('company_id', auth()->user()->directory_id ?? 0)
                            ->delete();
            if($isDelete){
                return response()->json(['success' => true, 'msg' => 'Successfully deleted!']);
            }else{
                return response()->json(['success' => false, 'msg' => 'Something went wrong']);
            }
        }
    }

    public function AdmingetAutoinnerPrizebyId(Request $request){

        $getAutoinner = CompetitionWinners::with('advertisement')->with('answer')->with('user')->with('question')->where('type','auto')->where('vendor_id',$request->companyid)->where('question_id',$request->questionId)->where('advertisement_id',$request->advertisement_id)->first();
        //dd($getAutoinner);
        return $getAutoinner;
    }

    public function addAnswerStatusPrizebyId(Request $request)
    {
       // dd($request->all());
        $user = User::findOrFail($request->customer_id);
        $advertisement = Advertisement::findOrFail($request->advertisement_id);
        $expDate = $request->exp_date ?? date("Y-m-d");

        // Prepare prize data for mail
        $prizeData = [
            'user_email'        => $user->email,
            'exp_date'          => $expDate,
            'discount'          => $advertisement->price,
            'directory_id'      => Auth::user()->directory_id,
            'prizecode'         => $request->prize_code,
            'prize_description' => $request->prize_description,
        ];

        // Save competition winner
        CompetitionWinners::create([
            'advertisement_id'  => $request->advertisement_id,
            'question_id'  => $request->que_id,
            'customer_id'       => $request->customer_id,
            'vendor_id'         => Auth::user()->directory_id,
            'prize_description' => $request->prize_description,
            'voucher_code'      => $request->prize_code,
            'type'              => 'vendor',
            'prize_amount'      => $request->discount
        ]);

        // Update advertisement answer
        $answer = AdvertisementAnswers::find($request->id);
        if ($answer) {
            $answer->update([
                'is_correct'        => $request->is_correct,
                'exp_date'          => $expDate,
                'prize_description' => $request->prize_description,
                'prize_code'        => $request->prize_code,
            ]);

            Mail::to($user->email)->send(new PrizeCodeMail($prizeData));

            return response()->json(['success' => 'Prize added successfully.']);
        }

        return response()->json(['error' => 'Something went wrong. Please try again.']);
    }

    public function selectAutowinner(Request $request, $id){

       try{
        $answers = AdvertisementAnswers::where('question_id', $id)->pluck('customer_id');
        if ($answers->isEmpty()) {
            return null; // no participants
        }

        $user = User::findOrFail($answers);
        //dd($user);
        $advertisement = Advertisement::findOrFail($request->advertisement_id);
        $expDate = $request->expiry_date ?? date("Y-m-d");
        $prize_code = strtoupper(uniqid('PRIZE-')); // auto prize code

        if($user){
            $user_email = $user[0]->email;
        }else{
            $user_email = 'peteri@rydholmsmode.se';
        }

        $prizeData = [
            'user_email'        => $user_email,
            'exp_date'          => $expDate,
            'discount'          => $advertisement->price,
            'directory_id'      => Auth::user()->directory_id,
            'prizecode'         => $prize_code,
            'prize_description' => 'Auto description',
        ];
        // Random winner
        $winnerId = $answers->random();

        // Save winner
        return CompetitionWinners::create([
            'advertisement_id'   => $request->advertisement_id,
            'customer_id'        => $winnerId,
            'vendor_id'          => Auth::user()->directory_id,
            'question_id'        => $id,
            //'type'               => 'auto',
            'prize_description'  => 'Auto description',
            'voucher_code'       => $prize_code,
        ]);
        //dd($id);
         Mail::to($user_email)->send(new PrizeCodeMail($prizeData));

         return response()->json(['message'=>'Status change successfully.']);
        } catch (Exception $e) {
            return response()->json(['message'=>'Something went wrong.']);
        }

    }

    public function getAdvertisementQuestionsId(Request $request)
    {

        if (!empty($request->queid)) {
            $AdvertisementQuestion = AdvertisementQuestions::with('answers')
                ->where('company_id', $request->directoryid)
                ->where('id', $request->queid)
                ->first();

            $hasCorrectAnswer = $AdvertisementQuestion && $AdvertisementQuestion->answers->contains('is_correct', 1);

            if (!$AdvertisementQuestion) {
                return "<p>No question found.</p>";
            }

            $html = "
                <p class='section-title' style='width: 100%;'>Manual</p>
                <hr>
                <h4 class='user_question' style='width: 100%;'>Question - {$AdvertisementQuestion->question_text}</h4>
                <hr>
                <ul>
            ";

            if ($AdvertisementQuestion->answers->count() > 0) {
                foreach ($AdvertisementQuestion->answers as $answers_val) {
                    $getUser = Helper::getUserById($answers_val->customer_id);
                    $formattedDate = \Carbon\Carbon::parse($answers_val->exp_date)->format('d-m-Y');

                    $html .= "
                        <li>
                            <div class='user-name'>
                                {$getUser->name} <span class='user-email'>({$getUser->email})</span>
                            </div>
                            <hr>
                            <div class='user-answer'>
                                <b>" . ($answers_val->vendor_id == 0 ? "Seller Answer" : "User Answer") . " -</b>
                                {$answers_val->answer}
                            </div>

                            <input type='hidden' id='customer_id_{$answers_val->id}' value='{$answers_val->customer_id}'>
                            <input type='hidden' id='vendor_id_{$answers_val->id}' value='{$answers_val->vendor_id}'>
                            <input type='hidden' id='question_id_{$answers_val->id}' value='{$AdvertisementQuestion->id}'>



                            <div class='user-answer' id='prize_expiry_div_{$answers_val->id}' " . ($answers_val->is_correct == 1 ? "" : "style='display:none;'") . ">
                                Prize Expiry - <input type='text'
                                    class='prize_expiry form-control'
                                    style='width:64%; float:right; height:35px;'
                                    value='{$formattedDate}' readonly>
                            </div>

                            <div class='user-answer' id='prize_code_div_{$answers_val->id}' " . ($answers_val->is_correct == 1 ? "" : "style='display:none;'") . ">
                                Prize Code - <input type='text'
                                    id='prize_code_{$answers_val->id}'
                                    name='prize_code'
                                    class='prize_code form-control'
                                    style='width:64%; float:right; height:35px;'
                                    placeholder='Prize code'
                                    value='{$answers_val->prize_code}'
                                    data-code_div_id='{$answers_val->id}'
                                    " . ($answers_val->is_correct == 1 ? "readonly" : "") . ">
                            </div>
                        </li>
                    ";
                }
            }

            $html .= "</ul>";

            // if (!$hasCorrectAnswer) {
            //     $html .= "
            //         <div style='width: 100%;'>
            //             <p class='section-title' style='color: #222020;border-bottom: 1px solid #ccc;'>Auto</p>
            //             <a class='btn btn-success select-winner user_select_winner'
            //             style='color:#fff;'
            //             data-question='{$AdvertisementQuestion->id}'>
            //                 Auto Winner
            //             </a>
            //         </div>
            //     ";
            // }

            return $html;
        }


    }

    public function vendorprizes(){
        if (Auth::check()){
            $myPrizes = CompetitionWinners::with('advertisement')->where('customer_id', auth()->id())->get();
            //dd($myPrizes);
            return view('account.company.advertisement.vendorprizes', ['data' => $myPrizes]);
        }else{
            return redirect()->route('pages');
        }
    }

    public function companyaddadvertisementsubmit(Request $request)
    {
        $this->validate($request, [
            'heading' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $imgfile = $request->file('image');
        $startdate  = $request->input('start_date');
        $expiredate = $request->input('end_date');
        if ($imgfile) {
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            $imagepath = 'files/advertisement/' . $imageName;
            $image_resize = Image::make($imgfile->getRealPath());
            $image_resize->save(public_path('files/advertisement/' . $imageName));
            $advertisement_insert                       = new Advertisement;
            $advertisement_insert->heading              = $request->input('heading');
            $advertisement_insert->image_name           = $imageName;
            $advertisement_insert->link                 = $request->input('link');
            $advertisement_insert->mid_text             = $request->input('midtext');
            $advertisement_insert->bottom_text          = $request->input('bottomtext');
            $advertisement_insert->price                = $request->input('price');
            $advertisement_insert->description          = $request->input('description');
            $advertisement_insert->start_date           = isset($startdate) ? $startdate : date("Y-m-d");
            $advertisement_insert->end_date             = isset($expiredate) ? $expiredate : date("Y-m-d");
            $advertisement_insert->directory_id         = $request->input('directoryId');
            $advertisement_insert->save();
           $advertisement_id = $advertisement_insert->id;
        }

        if ($request->has('questions')) {
            foreach ($request->questions as $qText) {
                if (!empty(trim($qText))) {
                    AdvertisementQuestions::create([
                        'advertisement_id' => $advertisement_id,
                        'company_id' => auth()->user()->directory_id ?? 0,
                        'question_text' => $qText,
                    ]);
                }
            }
        }

        return redirect('company/advertisement')->with('msg', 'Advertisement Added Successfully!');
    }

    public function companyadvertisementedit($id = 0)
    {
        if (Auth::check()) {
            if (Helper::getFeatureUses('advertisment') == false) {
                return abort(404);
            }
            $user = auth()->user();
            if ($user->hasPermission('edit') == false) {
                abort(404);
            }
            $advertisementDetails = Advertisement::with([
                'questions' => function ($query) {
                    $query->where('company_id', auth()->user()->directory_id ?? 0);
                }
            ])->findOrFail($id);

            return view('account.company.advertisement.edit')->with(compact(['advertisementDetails']));
        } else {
            return abort(404);
        }
    }

    public function exportAnswer($id)
    {
        $fileName = 'competition_answer_' . $id . '.xlsx';
        return Excel::download(new CompetitionAnswerExportVendor($id), $fileName);
    }

    public function updateQuestionWinnerData(Request $request)
    {

        AdvertisementAnswers::where('id', $request->answer_id)->update([
            'prize_code'       => $request->prize_code,
            'exp_date'         => $request->exp_date,
            'is_correct'       => 1,
            'prize_description'=> $request->prize_description,
        ]);
        $user = User::findOrFail($request->customer_id);

        // Save competition winner
            CompetitionWinners::create([
                'advertisement_id'  => $request->competition_id,
                'question_id'       => $request->question_id,
                'customer_id'       => $request->customer_id,
                'vendor_id'         => $request->vendor_id,
                'prize_description' => $request->prize_description,
                'voucher_code'      => $request->prize_code,
            ]);

        if ($user && $user->email) {
             // Prepare prize data for mail
            $prizeData = [
                'user_email'        => $user->email,
                'exp_date'          => $request->exp_date,
                'discount'          => $request->discount,
                'directory_id'      => $request->vendor_id,
                'prizecode'         => $request->prize_code,
                'prize_description' => $request->prize_description,
            ];
            Mail::to($user->email)->send(new PrizeCodeVendorMail($prizeData));
        }

        return response()->json([
            'status' => 1,
            'message' => 'Updated successfully'
        ]);
    }

    public function getSelectedAnswersId(Request $request){

        $answers_data = AdvertisementAnswers::with('question')->with('customer')->with('advertisement')->whereIn('id', $request->answer_ids)->get();

        return response()->json([
            'status'      => true,
            'answer_data' => $answers_data,
        ]);

    }

    public function competitionParticipants($id)
    {
        $advertisementDetails = Advertisement::with('questions')->findOrFail($id);
        $isWinner = AdvertisementAnswers::where('is_correct', 1)->where('vendor_id', auth()->user()->directory_id)->where('advertisement_id', $id)->count();
        return view('account.company.advertisement.competition_participants')->with(compact(['advertisementDetails','isWinner']));
    }



    // public function chooseCompetitionWinner(Request $request)
    // {

    //     $expDate = $request->exp_date ?? date("Y-m-d");
    //     CompetitionWinners::where('advertisement_id', $request->competition_id)->delete();

    //     foreach($request->answerIds as $answerId){

    //         $answer = AdvertisementAnswers::find($answerId);
    //         if (!$answer) {  continue;  }
    //         $user = User::findOrFail($answer->customer_id);

    //         $answer->update([
    //             'is_correct'        => 1,
    //             'exp_date'          => $expDate ?? null,
    //             'prize_description' => $request->prize_description,
    //             'prize_code'        => $request->prize_code,
    //         ]);

    //         // Save competition winner
    //         CompetitionWinners::create([
    //             'advertisement_id'  => $request->competition_id,
    //             'question_id'       => $answer->question_id,
    //             'customer_id'       => $answer->customer_id,
    //             'vendor_id'         => $answer->vendor_id,
    //             'prize_description' => $request->prize_description,
    //             'voucher_code'      => $request->prize_code,
    //         ]);

    //          // Prepare prize data for mail
    //         $prizeData = [
    //             'user_email'        => $user->email,
    //             'exp_date'          => $expDate,
    //             'discount'          => $request->discount,
    //             'directory_id'      => $answer->vendor_id,
    //             'prizecode'         => $request->prize_code,
    //             'prize_description' => $request->prize_description,
    //         ];
    //         Mail::to($user->email)->send(new PrizeCodeVendorMail($prizeData));

    //     }

    //     return response()->json(['success' => 'Prize added successfully.']);

    // }

    public function chooseCompetitionWinner(Request $request)
    {

        $expDate = $request->exp_date ?? date('Y-m-d');
        $mailData = [];
        DB::transaction(function () use ($request, $expDate, &$mailData) {
            foreach ($request->answerIds as $answerId) {
                $answer = AdvertisementAnswers::find($answerId);
                if (!$answer) {
                    continue;
                }
                $user = User::find($answer->customer_id);
                if (!$user) {
                    continue;
                }
                // Use entered prize code or generate a unique one
                if (!empty($request->prize_code)) {
                    $prizeCode = $request->prize_code;
                } else {
                    do {
                        $prizeCode = Helper::generatePrizeCode();
                    } while (
                        AdvertisementAnswers::where('prize_code', $prizeCode)->exists() ||
                        CompetitionWinners::where('voucher_code', $prizeCode)->exists()
                    );
                }

                // Update Answer
                $answer->update([
                    'is_correct'        => 1,
                    'exp_date'          => $expDate,
                    'prize_description' => $request->prize_description,
                    'prize_code'        => $prizeCode,
                ]);

                // Create or Update Winner
                CompetitionWinners::updateOrCreate(
                    [
                        'advertisement_id' => $request->competition_id,
                        'customer_id'      => $answer->customer_id,
                    ],
                    [
                        'question_id'       => $answer->question_id,
                        'vendor_id'         => $answer->vendor_id,
                        'prize_description' => $request->prize_description,
                        'voucher_code'      => $prizeCode,
                    ]
                );

                // Store email data for sending after commit
                $mailData[] = [
                    'email' => $user->email,
                    'data'  => [
                        'user_email'        => $user->email,
                        'exp_date'          => $expDate,
                        'discount'          => $request->discount,
                        'directory_id'      => $answer->vendor_id,
                        'prizecode'         => $prizeCode,
                        'prize_description' => $request->prize_description,
                    ]
                ];
            }
        });

        // Send emails after successful DB transaction
        foreach ($mailData as $mail) {
            Mail::to($mail['email'])->send(
                new PrizeCodeVendorMail($mail['data'])
            );
        }

        return response()->json([
            'success' => 'Competition winners saved successfully.'
        ]);
    }

    public function chooseAutoAlluserCompetitionWinner(Request $request)
    {


        $request->validate([
            'answerIds'      => 'required|array|min:1',
            'competition_id' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $advertisement = Advertisement::findOrFail($request->competition_id);

            // AdvertisementAnswers::where('advertisement_id', $request->competition_id)
            //     ->whereIn('id', $request->answerIds)
            //     ->update([
            //         'is_correct' => 0,
            //         'prize_code' => null,
            //         'exp_date' => null,
            //     ]);
            // CompetitionWinners::where('advertisement_id',$request->competition_id)->delete();

            $answers = AdvertisementAnswers::with(['question', 'customer'])
                ->whereIn('id', $request->answerIds)
                ->where('advertisement_id', $request->competition_id)
                ->where('vendor_id', auth()->user()->directory_id)
                ->get();


            if ($answers->isEmpty()) {
                return response()->json([
                    'error' => 'No valid answers selected.'
                ], 422);
            }

            $expiryDate       = now()->addDays(30)->format('Y-m-d');
            $prizeDescription = $advertisement->heading ?: 'Competition winner prize';

            $created = 0;
            $selectedCustomerIds = [];
            $questionGroups = $answers->groupBy('question_id');

            foreach ($questionGroups as $questionId => $questionAnswers) {

                $eligibleAnswers = $questionAnswers->filter(function ($answer) use ($selectedCustomerIds) {
                    return !in_array($answer->customer_id, $selectedCustomerIds);
                });

                if ($eligibleAnswers->isEmpty()) {
                    continue;
                }

                $winnerAnswer = $eligibleAnswers->random();
                $user = $winnerAnswer->customer;
                if (!$user) {
                    continue;
                }
                do {
                    $prizeCode = Helper::generatePrizeCode();
                } while (
                    AdvertisementAnswers::where('prize_code', $prizeCode)->exists() ||
                    CompetitionWinners::where('voucher_code', $prizeCode)->exists()
                );

                $winnerAnswer->update([
                    'is_correct'        => 1,
                    'exp_date'          => $expiryDate,
                    'prize_description' => $prizeDescription,
                    'prize_code'        => $prizeCode,
                ]);

                CompetitionWinners::create([
                    'advertisement_id'  => $winnerAnswer->advertisement_id,
                    'question_id'       => $winnerAnswer->question_id,
                    'customer_id'       => $winnerAnswer->customer_id,
                    'vendor_id'         => $winnerAnswer->vendor_id,
                    'prize_description' => $prizeDescription,
                    'voucher_code'      => $prizeCode,
                    'type'              => 'vendor',
                    'prize_amount'      => $advertisement->price??0.00
                ]);

                $selectedCustomerIds[] = $winnerAnswer->customer_id;

                $prizeData = [
                    'user_email'        => $user->email,
                    'exp_date'          => $expiryDate,
                    'discount'          => $advertisement->price,
                    'directory_id'      => $winnerAnswer->vendor_id,
                    'prizecode'         => $prizeCode,
                    'prize_description' => $prizeDescription,
                ];

                Mail::to($user->email)
                    ->send(new PrizeCodeVendorMail($prizeData));

                $created++;
            }

            DB::commit();

            return response()->json([
                'success' => 'Winners selected successfully.',
                'created' => $created,
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function companyadvertisementupdate(Request $request, $id)
    {
        $this->validate($request, [
            'heading' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'competition_discount_text' => 'required|string|max:255',
            'discount_type' => 'required|in:Fixed,Percentage',
            'competition_discount' => 'required|numeric|min:0',
        ]);
        $startdate  = $request->input('start_date');
        $expiredate = $request->input('end_date');
        $company_id = auth()->user()->directory_id ?? 0;

        $directoryLocation = DiectoryLocation::where('directory_id', $company_id)->first();


        VendorCompetitionList::updateOrCreate(
            [
                'competition_id' => $id,
                'company_id' => $company_id,
            ],
            [
                'title' => $request->competition_discount_text,
                'discount_type' => $request->discount_type,
                'discount_amount' => $request->competition_discount,
                'longitude' => $directoryLocation ? $directoryLocation->longitude : '57.1056',
                'latitude' => $directoryLocation ? $directoryLocation->latitude : '12.2508',
            ]
        );

        $updateArray = [
            'heading'               => $request->input('heading'),
            'link'                  => $request->input('link'),
            'mid_text'              => $request->input('midtext'),
            'bottom_text'           => $request->input('bottomtext'),
            'description'          => $request->input('description'),
            'start_date'           => isset($startdate) ? $startdate : date("Y-m-d"),
            'end_date'             => isset($expiredate) ? $expiredate : date("Y-m-d"),
            'price'                 => $request->input('price')
        ];
        $imgfile = $request->file('image');
        if ($imgfile) {
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            $imagepath = 'files/advertisement/' . $imageName;
            $image_resize = Image::make($imgfile->getRealPath());
            $image_resize->save(public_path('files/advertisement/' . $imageName));
            $updateArray['image_name']      = $imageName;
        }
        Advertisement::where('id', $id)->update($updateArray);

        if ($request->has('questions')) {
            $questions = array_filter(array_map('trim', $request->questions));
            $existing = AdvertisementQuestions::where('advertisement_id', $id)->where('company_id', $company_id)->pluck('id')->toArray();

            $get_company_data = Helper::getDirectoryIdSlug($company_id);

            CompetitionVendors::where('competition_id', $id)->where('directory_id', $company_id)->delete();
            CompetitionVendors::create([
                'competition_id' => $id,
                'directory_id' => $company_id,
                'vendor_name' => $get_company_data->title ?? '',
            ]);

            foreach ($questions as $index => $qText) {
                if (isset($existing[$index])) {
                    AdvertisementQuestions::where('id', $existing[$index])->update([
                        'question_text' => $qText,
                    ]);
                } else {
                    AdvertisementQuestions::create([
                        'advertisement_id' => $id,
                        'company_id' => $company_id,
                        'question_text' => $qText,
                    ]);
                }
            }
            // Delete any extra old questions
            if (count($existing) > count($questions)) {
                $idsToDelete = array_slice($existing, count($questions));
                AdvertisementQuestions::whereIn('id', $idsToDelete)->delete();
            }
        }
        return redirect('company/advertisement')->with('msg', 'Question has been updated successfully!');
    }

    public function destroy($id)
    {
        if (Helper::getFeatureUses('advertisment') == false) {
            return abort(404);
        }
        Advertisement::where('id', $id)->update(['deleted' => '1']);
        return redirect('company/advertisement')->with('msg', 'Advertisement deleted successfully');
    }
}
