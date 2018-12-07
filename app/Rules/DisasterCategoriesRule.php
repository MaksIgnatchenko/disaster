<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 06.12.2018
 *
 */

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DisasterCategoriesRule implements Rule
{
	/**
	 * @var array
	 */
	private $disasterCategories;

	/**
	 * DisasterCategoriesRule constructor.
	 * @param array $disasterCategories
	 */
    public function __construct(array $disasterCategories)
    {
        $this->disasterCategories = $disasterCategories;
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
		foreach ($value as $categoryCode) {
			if (!in_array($categoryCode, $this->disasterCategories)) {
				return false;
			}
		}
		return count($value) <= count($this->disasterCategories);
	}

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be array and contains only particular values';
    }
}
