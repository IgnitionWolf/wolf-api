<?php

use Illuminate\Support\Facades\Route;

Route::post('auth/login', 'AuthenticateController@login');
Route::post('auth/register', 'AuthenticateController@register');
