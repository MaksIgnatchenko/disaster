<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Http\Requests;

class StoreUserRequest extends UserRequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        $otherRules = [
            'deviceId' => 'required|string|min:1|max:255|unique:users,deviceId'
        ];
        return array_merge($otherRules, parent::rules());
    }
}
