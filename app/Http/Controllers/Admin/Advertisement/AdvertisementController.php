<?php

namespace App\Http\Controllers\Admin\Advertisement;

use App\Exports\CompetitionAnswerExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Advertisement;
use App\Helpers\Helper;
use App\Mail\PrizeCodeMail;
use App\Mail\PrizeCodeVendorMail;
use App\Models\AdvertisementAnswers;
use App\Models\AdvertisementQuestions;
use App\Models\CompetitionWinners;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\ImageManagerStatic as Image;

class AdvertisementController extends Controller
{
    public function index(Request $request)
    {

        $searchtext = $request->searchtext ?? '';
        $user_type   = $request->user_type ?? '';

        $advertisement = Advertisement::query()
            ->where('deleted', '0')
            ->when($searchtext, function ($query) use ($searchtext) {
                $query->where(function ($q) use ($searchtext) {
                    // Search in heading OR related questions
                    $q->where('heading', 'like', "%{$searchtext}%")->orwhere('mid_text', 'like', "%{$searchtext}%")
                    ->orWhereHas('questions', function ($sub) use ($searchtext) {
                        $sub->where('question_text', 'like', "%{$searchtext}%");
                    });
                });
            })
            // Eager load related questions (only matching ones if you want)
            ->with(['questions' => function ($q) use ($searchtext) {
                if ($searchtext) {
                    $q->where('question_text', 'like', "%{$searchtext}%");
                }
            }])
            ->when($user_type !== '', function ($query) use ($user_type) {
                if ($user_type === '0') {
                    // no filter
                } elseif ($user_type === 'admin') {
                    $query->whereNull('directory_id');
                } else {
                    $query->whereNotNull('directory_id');
                }
            })
            ->orderByDesc('id')
            ->where(function ($q) {
                $q->where('directory_id', 0)
                ->orWhereNull('directory_id')
                ->orWhere('directory_id', '');
            })
            ->paginate($searchtext ? 50 : 20);

        return view('admin.advertise.index', compact('advertisement','searchtext','user_type'));
    }

    public function competitionParticipants($id)
    {
        $advertisementDetails = Advertisement::with('questions')->findOrFail($id);
        $isWinner = AdvertisementAnswers::where('is_correct', 1)->where('advertisement_id', $id)->count();

        return view('admin.advertise.competition_participants')->with(compact(['advertisementDetails','isWinner']));
    }

    public function exportAnswer($id)
    {
        $fileName = 'competition_answer_' . $id . '.xlsx';
        return Excel::download(new CompetitionAnswerExport($id), $fileName);
    }

    public function create()
    {
        return view('admin.advertise.create');
    }

    public function getAdvertisementQuestionsId(Request $request)
    {
        //dd($request->all());
        if(!empty($request->queid)){
            $AdvertisementQuestion = AdvertisementQuestions::with('answers')->where('company_id', $request->directoryid)->where('id', $request->queid)->first();


            if($AdvertisementQuestion){

                if(count($AdvertisementQuestion->answers) > 0){
                    $html = '<h4 class="user_question" style="width: 100%;"> Question - '.$AdvertisementQuestion->question_text.'</h4><hr><ul >';
                foreach($AdvertisementQuestion->answers as $answers_val){
                    $getUser = Helper::getUserById($answers_val->customer_id);
                    $formattedDate = \Carbon\Carbon::parse($answers_val->exp_date)->format('d-m-Y');
                    $html .= "<li>
                                <div class='user-name'>{$getUser->name} <span class='user-email'>({$getUser->email})</span></div><hr>
                                <div class='user-answer'><b>
                                " . ($answers_val->vendor_id == 0 ? "Seller Answer" : "User Answer") . "

                                - </b> {$answers_val->answer}</div>
                                <input type='hidden' name='customer_id' id='customer_id_{$answers_val->id}' value='{$answers_val->customer_id}'>
                                <input type='hidden' name='vendor_id' id='vendor_id_{$answers_val->id}' value='{$answers_val->vendor_id}'>
                                <div class='user-answer'>
                                    Seller Status -
                                    <select name='is_correct' id='is_correct_{$answers_val->id}' ".($answers_val->is_correct == 1 ? "disabled" : "")." style='width: 60%; float: right; height: 35px;' class='is_correct_select form-control' data-id='{$answers_val->id}'>
                                        <option value='0'" . ($answers_val->is_correct == 0 ? " selected" : "") . ">Pending</option>
                                        <option value='1'" . ($answers_val->is_correct == 1 ? " selected" : "") . ">Right</option>
                                        <option value='2'" . ($answers_val->is_correct == 2 ? " selected" : "") . ">Wrong</option>
                                    </select>
                                </div>
                                <div class='user-answer' id='prize_desc_div_{$answers_val->id}'".($answers_val->is_correct == 1 ? " " : "style='display:none;'")." >
                                    Description - &nbsp;<input type='text' value='{$answers_val->prize_description}' ".($answers_val->is_correct == 1 ? "readonly" : "")." placeholder='Prize description' style='width: 64%; float: right; height: 35px;' name='prize_description' class='prize_description form-control' id='prize_description_{$answers_val->id}' >
                                </div>
                                <div class='user-answer' id='prize_expiry_div_{$answers_val->id}' ".($answers_val->is_correct == 1 ? " " : "style='display:none;'").">
                                    Prize Expiry - &nbsp;<input type='text' value='{$formattedDate}' readonly placeholder='expiry date' style='width: 64%; float: right; height: 35px;' name='prize_expiry' class='prize_expiry form-control' id='prize_expiry_{$answers_val->id}' >
                                </div>
                                <div class='user-answer' id='prize_code_div_{$answers_val->id}'  ".($answers_val->is_correct == 1 ? " " : "style='display:none;'").">
                                    Prize Code -  &nbsp;&nbsp;<input type='text' ".($answers_val->is_correct == 1 ? "readonly" : "")." value='{$answers_val->prize_code}' data-code_div_id='{$answers_val->id}' placeholder='prize code' style='width: 64%; float: right; height: 35px; ' name='prize_code' class='prize_code form-control' id='prize_code_{$answers_val->id}' >
                                </div>

                                </div>
                            </li>

                            ";


                }
                }
                $html = $html.'</ul>';
                return $html;
            }
        }
    }


    public function addAnswerStatusPrizebyAdminId(Request $request)
    {

        //dd($request->all());

        $user = User::findOrFail($request->customer_id);

        $advertisement = Advertisement::findOrFail($request->advertisement_id);
        $expDate = $request->exp_date ?? date("Y-m-d");
        //dd($answer);

        $answer = AdvertisementAnswers::find($request->id);
        if (!$answer) {
            return response()->json(['error' => 'Something went wrong. Please try again.']);
        }

        //dd($answer);

        // Prepare prize data for mail
        $prizeData = [
            'user_email'        => $user->email,
            'exp_date'          => $expDate,
            'discount'          => $advertisement->price,
            'directory_id'      => 0,
            'prizecode'         => $request->prize_code,
            'prize_description' => $request->prize_description,
        ];
        Mail::to($user->email)->send(new PrizeCodeVendorMail($prizeData));

        // Save competition winner
        CompetitionWinners::create([
            'advertisement_id'  => $request->advertisement_id,
            'question_id'  => $request->que_id,
            'customer_id'       => $request->customer_id,
            'vendor_id'         => 0,
            'prize_description' => $request->prize_description,
            'voucher_code'      => $request->prize_code,
            'prize_amount'      => $request->discount,
            'type'              => 'admin',
        ]);

        // Update advertisement answer
            $answer->update([
                'is_correct'        => $request->is_correct,
                'exp_date'          => $expDate ?? null,
                'prize_description' => $request->prize_description,
                'prize_code'        => $request->prize_code,
            ]);

            Mail::to($user->email)->send(new PrizeCodeVendorMail($prizeData));

            return response()->json(['success' => 'Prize added successfully.']);

    }

    public function chooseCompetitionWinner(Request $request)
    {
        //dd($request->all());
        $expDate = $request->exp_date ?? date("Y-m-d");

        foreach($request->answerIds as $answerId){

            do {
                $prizeCode = Helper::generatePrizeCode();
            } while (
                AdvertisementAnswers::where('prize_code', $prizeCode)->exists() ||
                CompetitionWinners::where('voucher_code', $prizeCode)->exists()
            );

            $answer = AdvertisementAnswers::find($answerId);
            if (!$answer) {  continue;  }
            $user = User::findOrFail($answer->customer_id);

            $answer->update([
                'is_correct'        => 1,
                'exp_date'          => $expDate ?? null,
                'prize_description' => $request->prize_description,
                'prize_code'        => $prizeCode,
            ]);

            // Save competition winner
            CompetitionWinners::create([
                'advertisement_id'  => $request->competition_id,
                'question_id'       => $answer->question_id,
                'customer_id'       => $answer->customer_id,
                'vendor_id'         => $answer->vendor_id,
                'prize_description' => $request->prize_description,
                'voucher_code'      => $prizeCode,
                'prize_amount'      => $request->discount,
                'type'              => 'admin',
            ]);

             // Prepare prize data for mail
            $prizeData = [
                'user_email'        => $user->email,
                'exp_date'          => $expDate,
                'discount'          => $request->discount,
                'directory_id'      => $answer->vendor_id,
                'prizecode'         => $prizeCode,
                'prize_description' => $request->prize_description,
            ];
            Mail::to($user->email)->send(new PrizeCodeVendorMail($prizeData));

        }

        return response()->json(['success' => 'Prize added successfully.']);

    }

    public function submit(Request $request)
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
            // $advertisement_insert->link                 = $request->input('link');
            $advertisement_insert->mid_text             = $request->input('midtext');
            $advertisement_insert->description          = $request->input('description');
            $advertisement_insert->adminassignvendor    = implode(',',$request->input('company_id'));
            $advertisement_insert->start_date           = isset($startdate) ? $startdate : date("Y-m-d");
            $advertisement_insert->end_date             = isset($expiredate) ? $expiredate : date("Y-m-d");
            $advertisement_insert->bottom_text          = $request->input('bottomtext');
            $advertisement_insert->directory_id         = $request->input('directory_id')??0;
            $advertisement_insert->discount_type        = $request->input('discount_type');
            $advertisement_insert->price                = $request->input('price');
            $advertisement_insert->discount_text        = $request->input('discount_text');
            $advertisement_insert->save();
            $advertisement_id = $advertisement_insert->id;

        }
        return redirect('admin/advertisement')->with('msg', 'Advertisement Added Successfully!');
    }

    public function deleteQueById(Request $request){

        $isAxistAns = AdvertisementAnswers::where('advertisement_id', $request->advertisement_id)
                         ->where('question_id', $request->questionId)
                         //->where('vendor_id', auth()->user()->directory_id ?? 0)
                         ->count();
        if($isAxistAns == 1){
            return response()->json(['success' => false, 'msg' => 'Answer exists! Cannot delete this.']);
        }else{
            $isDelete = AdvertisementQuestions::where('advertisement_id', $request->advertisement_id)
                            ->where('id', $request->questionId)
                            //->where('company_id', auth()->user()->directory_id ?? 0)
                            ->delete();
            if($isDelete){
                return response()->json(['success' => true, 'msg' => 'Successfully deleted!']);
            }else{
                return response()->json(['success' => false, 'msg' => 'Something went wrong']);
            }
        }
    }

     public function selectAutowinner(Request $request, $id){

       try{
        $answers = AdvertisementAnswers::where('question_id', $id)->pluck('customer_id');
        if ($answers->isEmpty()) {
            return null; // no participants
        }

        $user = User::findOrFail($answers);
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
            'directory_id'      => 0,
            'prizecode'         => $prize_code,
            'prize_description' => 'Auto description',
        ];
        // Random winner
        $winnerId = $answers->random();

        $is_success = AdvertisementAnswers::where('question_id', $id)
                                        ->where('advertisement_id', $request->advertisement_id)
                                        ->where('customer_id', $winnerId)
                                        ->update(['is_correct' => 1]);

        // Save winner
        return CompetitionWinners::create([
            'advertisement_id'   => $request->advertisement_id,
            'customer_id'        => $winnerId,
            'vendor_id'          => 0,
            'question_id'        => $id,
            'type'               => 'auto',
            'prize_description'  => 'Auto description',
            'voucher_code'       => $prize_code,
        ]);




         Mail::to($user_email)->send(new PrizeCodeMail($prizeData));

         return response()->json(['message'=>'Status change successfully.']);
        } catch (Exception $e) {
            return response()->json(['message'=>'Something went wrong.']);
        }

    }


    public function edit($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        $advertisementDetails = Advertisement::with('questions')->findOrFail($id);
        return view('admin.advertise.edit')->with(compact(['advertisementDetails']));
    }

    public function getSelectedAnswersId(Request $request){

        $answers_data = AdvertisementAnswers::with('question')->with('customer')->with('advertisement')->whereIn('id', $request->answer_ids)->get();
        return response()->json([
            'status'      => true,
            'answer_data' => $answers_data,
        ]);

    }

    public function update(Request $request, $id)
    {

        //dd($request->all());

        $this->validate($request, [
            'heading' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $startdate  = $request->input('start_date');
        $expiredate = $request->input('end_date');
        if($request->input('company_id')){
            $company_id = implode(',',$request->input('company_id'));
        }else{
            $company_id = '';
        }

        $updateArray = [
            'heading'              => $request->input('heading'),
            // 'link'                 => $request->input('link'),
            'mid_text'             => $request->input('midtext'),
            'bottom_text'          => $request->input('bottomtext'),
            'discount_type'        => $request->input('discount_type'),
            'description'          => $request->input('description'),
            'adminassignvendor'    => $company_id,
            // 'is_home'              => $request->input('is_home')??0,
            'start_date'           => isset($startdate) ? $startdate : date("Y-m-d"),
            'end_date'             => isset($expiredate) ? $expiredate : date("Y-m-d"),
            'price'                => $request->input('price'),
            'discount_text'        => $request->input('discount_text')
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

        return redirect('admin/advertisement-edit/' . $id)->with('msg', 'Competition has been updated');
    }
    public function destroy($id)
    {
        Advertisement::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/advertisement')->with('msg', 'Competition deleted successfully');
    }
}
