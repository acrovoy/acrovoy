<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/successful-payment', function () {
    return view('successful-payment');
});


