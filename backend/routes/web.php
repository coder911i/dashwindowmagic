<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'app' => 'Watering CRM API',
        'version' => '2.0.0',
        'status' => 'operational'
    ]);
});
