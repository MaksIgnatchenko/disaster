<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App;

use App\Enums\TempUnitEnum;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{


    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tempUnit',
        'windSpeedUnit',
        'minTemp',
        'maxTemp',
        'timezone',
		'disasterCategories',
    ];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'disasterCategories' => 'array',
	];

    /**
     * @param int $minTempKPH
     * @return int|null
     */
	public function isExceededMinTemp(int $minTempKPH) : ?int
    {
        if ($this->minTemp && $this->tempUnit) {
            return ($minTempKPH <= $this->getMinTemp()) ? $this->getMinTemp() : null;
        }
        return null;
    }

    /**
     * @return int
     */
    private function getMinTemp() : int
    {
        switch ($this->tempUnit) {
            case TempUnitEnum::CELSIUS :
                return $this->minTemp;
            case TempUnitEnum::FAHRENHEIT :
                return ($this->minTemp - 32) / 1.8;
        }

    }

    public function isExceededWindSpeed(int $windSpeedMaxKPH)
    {

    }
}
