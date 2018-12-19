<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 06.12.2018
 *
 */

namespace App\Helpers;

class DisasterCategories
{
	/**
	 * List of all available disaster category codes
	 *
	 * @return array
	 */
	public static function getAvailableCategories(): array
	{
		return [
		    'AV',
            'BH',
			'CC',
            'CW',
            'CB',
            'EQ',
            'ST',
            'FR',
            'FF',
            'FL',
            'WF',
            'GW',
            'HT',
            'LS',
            'MG',
            'NC',
            'SS',
            'SE',
            'EC',
            'TO',
            'TS',
            'VA',
            'VE',
		];
	}
}