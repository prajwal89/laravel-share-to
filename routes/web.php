<?php

use Illuminate\Support\Facades\Route;


Route::get(config('laravel-share-to.trackingEndpoint'), 'Prajwal89\LaravelShareTo\ShareToController@trackAndRedirect');
