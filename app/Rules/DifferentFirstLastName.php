<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DifferentFirstLastName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $lastName = request('register_last_name');
        return strtolower($value) !== strtolower($lastName);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The first name and last name must be different.';
    }
}
