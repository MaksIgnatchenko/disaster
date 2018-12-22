<?php

return [
    /**
     * Time to life of API parse result (minutes).
     */
    'cache_api_result_ttl' => 2880,

    /**
     * Frequency of api analysis and sending push notifications (times per day)
     */
    'parse_api_intensity' => 1,

	'morning_push_time' => '08:00',

	'evening_push_time' => '20:00',

    'itunes_shared_secret' => env('ITUNES_SHARED_SECRET'),
];
