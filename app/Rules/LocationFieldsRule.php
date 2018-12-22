<?php

namespace App\Rules;

use App\Location;
use Illuminate\Contracts\Validation\Rule;

class LocationFieldsRule implements Rule
{
    /**
     * @var array
     */
    private $locationFields;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->locationFields = app()[Location::class]->getFillable();
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
        foreach ($value as $location) {
            foreach ($location as $key => $value) {
               if (!in_array($key, $this->locationFields, true)) {
                   return false;
               }
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'One of location contains not allowed field';
    }
}
