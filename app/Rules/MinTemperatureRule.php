<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MinTemperatureRule implements Rule
{
    /**
     * @var int
     */
    private $requestMaxTemp;

    /**
     * TemperatureRule constructor.
     * @param int $requestMaxTemp
     */
    public function __construct(int $requestMaxTemp = null)
    {
        $this->requestMaxTemp = $requestMaxTemp;
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
        $maxTemp = $this->user->settings->minTemp ?? $this->requestMaxTemp;
        return $maxTemp > $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The min temperature must be greater than max temperature.';
    }
}
