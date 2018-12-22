<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 21.12.2018
 *
 */
Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function() {
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/home', 'HomeController@index')->name('home');
});

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login')->middleware('guest');
Route::post('/login', 'Auth\LoginController@login');
