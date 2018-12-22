<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Http\Requests;

use App\Enums\TempUnitEnum;
use App\Enums\WindSpeedUnitEnum;
use App\Helpers\DisasterCategories;
use App\Rules\DisasterCategoriesRule;
use App\Rules\TimeZoneRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequestAbstract extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'pushToken' => 'string|min:1|max:255',
            'receipt' => 'string|min:1|max:65000',
        ];
    }
}
