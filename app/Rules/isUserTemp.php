<?php

namespace App\Rules;

use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Validation\Rule;

class isUserTemp implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->request = $request;
       // $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        //if (User::where('temp', 1)->where('email', '=', request()->get('register_email'))->exists()) {
        if (User::where('temp', 1)->where('email', request()->get('register_email'))->first()) {
            //die('1');
            return true;
        }elseif(User::where('temp', 0)->where('email', request()->get('register_email'))->first()){
            //die('2');
            return false;
        }elseif(!(User::where('temp', 0)->where('email', request()->get('register_email'))->first())){
           // die('3');
            return true;
        }elseif(!(User::where('email', request()->get('register_email'))->first())){
            //die('4');
            return true;
        }


    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Email has already been taken.';
    }
}
