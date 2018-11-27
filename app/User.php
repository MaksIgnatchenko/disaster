<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 23.11.2018
 *
 */

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'deviceId',
        'pushToken',
        'receipt',
        'tempUnit',
        'windSpeedUnit',
        'minTemp',
        'maxTemp',
        'locations',
    ];

    public function setLocationsAttribute(array $values) : void
    {
        foreach ($values as $value) {
            $location = Location::where('lat', $value['lat'])
                ->where('long', $value['long'])
                ->first();
            if (!$location) {
                $location = app()[Location::class];
                $location->fill($value);
                $location->save();
            }
            $this->locations()->attach($location->id);
        }
    }

    public function getLocationsAttribute()
    {
        
    }

    /**
     * @return HasMany
     */
    public function locations() : HasMany
    {
        return $this->hasMany('App\Location');
    }
}
