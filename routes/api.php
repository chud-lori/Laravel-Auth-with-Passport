<?php

use Illuminate\Http\Request;


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', 'Api\UserController@register');
Route::post('login', 'Api\UserController@login');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('logout', 'Api\UserController@logout');
    Route::get('user/details', 'Api\UserController@details');
});