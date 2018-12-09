<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Location extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'place',
        'country',
    ];

    /**
     * @param array $locationsData
     * @return Collection
     */
    public function batchFirstOrCreate(array $locationsData = null) : Collection
    {
        $locations = new Collection();
        foreach($locationsData as $locationData) {
            $locations->push($this->firstOrCreate($locationData));
        }
        return $locations;
    }
}
