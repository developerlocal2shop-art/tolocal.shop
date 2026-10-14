<?php
namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use App\Mail\Contactus;
use Illuminate\Http\Request;
use DB;
use App\Models\Systemsetting;
use App\Models\Mapsetting;
use App\Models\Contact;
use Exception;
use Illuminate\Support\Facades\Mail;
use Stripe\OAuth;
use Stripe\Stripe;
use Stripe\StripeClient;
use App\Helpers\Helper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{

    public function index(){
        Helper::countwebsite();
    	$contact = Systemsetting::all();
    	$map = Mapsetting::all();

        return view('client.contact',compact('contact', 'map'))->with('recaptchaSiteKey', config('services.recaptcha.site_key'));

    }

    public function submitContact(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
            'name_contact' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Z][a-zA-Z\s]*$/'
            ],
            'lastname_contact' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Z][a-zA-Z\s]*$/'
            ],
            'email_contact' => ['required', 'email', 'max:100'],
            // India + Sweden phone validation
            'phone_contact' => [
                'required',
                'regex:/^(\+91[\-\s]?)?[6-9]\d{9}$|^(\+46[\-\s]?)?7\d{8}$/'
            ],
            'message_contact' => ['required', 'string', 'max:1000'],
            'g-recaptcha-response' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect('contact')->withErrors($validator)->withInput();
        }

            $siteKey = config('services.recaptcha.site_key');
            $secretKey = config('services.recaptcha.secret_key');
            if (empty($siteKey) || empty($secretKey)) {
                Log::error('Contact form reCAPTCHA is not configured.');
                return redirect('contact')
                    ->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification is currently unavailable. Please try again later.'])
                    ->withInput();
            }

            try {
                $captchaResponse = Http::asForm()
                    ->timeout(5)
                    ->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret' => $secretKey,
                        'response' => $request->input('g-recaptcha-response'),
                        'remoteip' => $request->ip(),
                    ]);
            } catch (ConnectionException $exception) {
                Log::warning('Contact form reCAPTCHA verification request failed.', [
                    'exception' => $exception->getMessage(),
                ]);
                return redirect('contact')
                    ->withErrors(['g-recaptcha-response' => 'Unable to verify reCAPTCHA right now. Please try again.'])
                    ->withInput();
            }

            if (!$captchaResponse->successful() || !$captchaResponse->json('success')) {
                return redirect('contact')
                    ->withErrors(['g-recaptcha-response' => 'Please complete the reCAPTCHA verification.'])
                    ->withInput();
            }

            $alreadySubmitted = Contact::where('email', $request->email_contact)
                ->whereDate('created_at', Carbon::today())
                ->exists();

            if ($alreadySubmitted) {
                return redirect('contact')
                    ->with('error', 'You have already submitted a contact request today. Please try again tomorrow. if any futher questions, pls use mail');
            }

            $contact = new Contact();
            $contact->first_name = $request->input('name_contact');
            $contact->last_name  = $request->input('lastname_contact');
            $contact->phone      = $request->input('phone_contact');
            $contact->email      = $request->input('email_contact');
            $contact->message    = $request->input('message_contact');
            $contact->save();

            //Mail::to(env('APP_EMAIL'))->send(new Contactus($request->all()));

            return redirect('contact')->with('msg', 'Submitted Successfully!');

        } catch (\Exception $e) {

            return redirect('contact')
                ->with('error', 'Something went wrong. Try again!');
        }
    }


}
