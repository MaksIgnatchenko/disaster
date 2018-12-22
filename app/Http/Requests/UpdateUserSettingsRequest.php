<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 21.12.2018
 *
 */

namespace App\Http\Requests;

use App\Enums\TempUnitEnum;
use App\Enums\WindSpeedUnitEnum;
use App\Helpers\DisasterCategories;
use App\Rules\DisasterCategoriesRule;
use App\Rules\LocationFieldsRule;
use App\Rules\MinTemperatureRule;
use App\Rules\TimeZoneRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tempUnit' => [Rule::in(TempUnitEnum::toArray())],
            'windSpeedUnit' => [Rule::in(WindSpeedUnitEnum::toArray())],
            'minTemp' => ['required_with:maxTemp', 'integer', 'min:-100', 'max:100', new MinTemperatureRule($this->maxTemp)],
            'maxTemp' => 'required_with:minTemp|integer|min:-100|max:100',
            'timezone' => [new TimeZoneRule()],
            'locations.*.place' => 'required|string|min:1|max:255',
            'locations.*.country' => 'required|string|min:1|max:255',
            'locations' => [new LocationFieldsRule()],
            'disasterCategories' => ['array', new DisasterCategoriesRule(DisasterCategories::getAvailableCategories())],
            'disasterCategories.*' => 'distinct',
        ];
    }
}
