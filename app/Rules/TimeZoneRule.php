<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TimeZoneRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, \DateTimeZone::listIdentifiers());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The timezone does not exist.';
    }
}
