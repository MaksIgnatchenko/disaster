<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Http\Requests;

use App\Enums\TempUnitEnum;
use App\Enums\WindSpeedUnitEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'deviceId' => 'required|string|min:1|max:255',
            'pushToken' => 'string|min:1|max:255',
            'receipt' => 'string|min:1|max:65000',
            'tempUnit' => [Rule::in(TempUnitEnum::toArray()), 'min:1', 'max:5'],
            'windSpeedUnit' => [Rule::in(WindSpeedUnitEnum::toArray()), 'min:1', 'max:255'],
            'minTemp' => 'integer|min:-100|max:100',
            'maxTemp' => 'integer|min:-100|max:100',
            'locations.*' => 'string|min:1|max:255',
        ];
    }
}
