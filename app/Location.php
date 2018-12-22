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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'pivot',
        'created_at',
        'updated_at',
    ];

    /**
     * @param array $locationsData
     * @return Collection
     */
    public function batchFirstOrCreate(array $locationsData) : Collection
    {
        $locations = new Collection();
        foreach($locationsData as $locationData) {
            $locations->push($this->firstOrCreate($locationData));
        }
        return $locations;
    }

}
