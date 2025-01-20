<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Contacts\Http\Controllers\ContactController;

// In a production application tenantId would be handled in the OAuth middleware
//  and wouldn't need to be passed in by the client in the URL
//
// Note: also since this is a real estate application,
//  I would pick another variable name as not to mix with a tenancy module
Route::group(['prefix' => 'api/tenants/{tenantId}'], function () {
    Route::apiResource('contacts', ContactController::class);
});
