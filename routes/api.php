<?php

use EmailService\Http\Controllers\EmailService;

Route::group(['prefix' => 'v1', 'middleware' => 'email.auth'], function () {
    Route::get('/email', function () {
        return response()->json(['status_code' => 200, 'message' => 'Welcome to mapps email service']);
    });
    Route::post('/send', [EmailService::class, 'send']);
    Route::post('/upload', 'Cloudinary@uploads');
});
