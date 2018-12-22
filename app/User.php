<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 23.11.2018
 *
 */

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

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
        'expirationDate',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'receipt',
        'pushToken',
        'deviceId',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsToMany
     */
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany('App\Location');
    }

    /**
     * @return HasOne
     */
    public function settings(): HasOne
    {
        return $this->hasOne('App\Settings');
    }

    /**
     * @param $query
     * @return Builder
     */
    public function scopeActive($query): Builder
    {
        return $query
            ->whereDate('expirationDate', '>=', Carbon::today()->toDateString())
            ->whereNotNull('expirationDate');
    }
}
