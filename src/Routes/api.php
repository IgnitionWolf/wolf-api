<?php

use Illuminate\Support\Facades\Route;

Route::post('auth/login', 'AuthenticateController@login');
Route::post('auth/login/social', 'AuthenticateController@social');
Route::post('auth/register', 'AuthenticateController@register');
Route::get('auth/check', 'AuthenticateController@check');
