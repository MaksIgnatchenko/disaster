<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 23.11.2018
 *
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
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
    ];

    /**
     * @return BelongsToMany
     */
    public function locations() : BelongsToMany
    {
        return $this->belongsToMany('App\Location');
    }

    /**
     * @return HasOne
     */
    public function settings() : HasOne
    {
        return $this->hasOne('App\Settings');
    }
}
