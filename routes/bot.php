<?php

/*
|--------------------------------------------------------------------------
| Bot Routes
|--------------------------------------------------------------------------
|
| Here is where you can register bot routes for your application. These
| routes are loaded by the RouteServiceProvider.
|
*/

Route::post('/', 'BotManController@chat');

Route::get('/chat', function () {
    return view('chat');
});
