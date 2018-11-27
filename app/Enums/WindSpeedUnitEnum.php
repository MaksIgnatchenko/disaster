<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Enums;

class WindSpeedUnitEnum
{
    public const KPH = 'kph';
    public const MPH = 'mph';
    public const MS = 'm/s';

    /**
     * @return array
     */
    public static function toArray() : array
    {
        return [
            self::KPH,
            self::MPH,
            self::MS,
        ];
    }
}
