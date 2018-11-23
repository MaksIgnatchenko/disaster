<?php

use Illuminate\Http\Request;
use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('weather', function() {
    $client = new Client();
    $baseUrl = "http://query.yahooapis.com/v1/public/yql";
    $yqlQuery = 'select wind, item.condition from weather.forecast where woeid in (select woeid from geo.places where text="chicago, il")';
    $yqlQueryUrl = $baseUrl . "?q=" . urlencode($yqlQuery) . "&format=json";
    $request = $client->get($yqlQueryUrl);
    $response = json_decode($request->getBody()->getContents());
    dd($response);


//    $BASE_URL = "http://query.yahooapis.com/v1/public/yql";
//    $yql_query = 'select wind, item.condition from weather.forecast where woeid in (select woeid from geo.places where text="chicago, il")';
//    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
//    // Make call with cURL
//    $session = curl_init($yql_query_url);
//    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
//    $json = curl_exec($session);
//    // Convert JSON to PHP object
//    $phpObj =  json_decode($json);
//    var_dump($phpObj);
});
