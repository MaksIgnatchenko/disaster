<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Enums;

class TempUnitEnum
{
    public const CELSIUS = 'c';
    public const FAHRENHEIT = 'f';

    public static function toArray() : array
    {
        return [
            self::CELSIUS,
            self::FAHRENHEIT,
        ];
    }
}
