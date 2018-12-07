<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Disaster extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cid',
        'event_date',
        'category_code',
        'category',
        'country',
        'area_range_definition',
        'description',
    ];
}
