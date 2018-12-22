<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 06.12.2018
 *
 */

namespace App\Services;

use Carbon\Carbon;

class PushTimeDetector
{
    /**
     * @var string
     */
	private $userTimeZone;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
	private $morningPushTime;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
	private $eveningPushTime;

    /**
     * PushTimeDetector constructor.
     * @param string $userTimeZone
     */
	public function __construct(string $userTimeZone = 'Europe/London')
	{
		$this->userTimeZone = $userTimeZone;
		$this->morningPushTime = config('app_settings.morning_push_time');
		$this->eveningPushTime = config('app_settings.evening_push_time');
	}

	/**
	 * @return int
	 */
	public function getRemainingMinutes() : int
	{
		$morningPushTime = Carbon::createFromTimeString(
			Carbon::today()->toDateString() . ' ' . $this->morningPushTime
		);
		$eveningPushTime = Carbon::createFromTimeString(
			Carbon::today()->toDateString() . ' ' . $this->eveningPushTime
		);
		$userTime = Carbon::now($this->userTimeZone);
		$morningDiff = $userTime->diffInMinutes($morningPushTime, false);
		$eveningDiff = $userTime->diffInMinutes($eveningPushTime, false);
		$addMinutes = ($morningDiff < $eveningDiff ? $morningDiff : $eveningDiff);
		$addMinutes = ($addMinutes < 0 ? $eveningDiff : $addMinutes);
		return $addMinutes;
	}
}