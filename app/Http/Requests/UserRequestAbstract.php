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
            'tempUnit' => [Rule::in(TempUnitEnum::toArray()), 'min:1', 'max:5'],
            'windSpeedUnit' => [Rule::in(WindSpeedUnitEnum::toArray()), 'min:1', 'max:255'],
            'minTemp' => 'integer|min:-100|max:100',
            'maxTemp' => 'integer|min:-100|max:100',
            'timezone' => [new TimeZoneRule()],
            'locations.*.lat' => 'required|numeric|between:-90,90',
            'locations.*.long' => 'required|numeric|between:-180,180',
            'locations.*.place' => 'string|min:1|max:255',
            'locations.*.country' => 'string|min:1|max:255',
			'disasterCategories' => ['array', new DisasterCategoriesRule(DisasterCategories::getAvailableCategories())],
			'disasterCategories.*' => 'distinct',
        ];
    }
}