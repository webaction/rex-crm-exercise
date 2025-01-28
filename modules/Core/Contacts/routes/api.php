<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Contacts\Http\Controllers\ContactController;
use Modules\Core\Contacts\Http\Controllers\ContactSearchController;

// In a production application tenantId would be handled in the OAuth middleware
//  and wouldn't need to be passed in by the client in the URL
//
// Note: also since this is a real estate application,
//  I would pick another variable name as not to mix with a tenancy module
Route::prefix('api/tenants/{tenant_id}')->middleware('api')->group(function () {
    /**
     * Using GET over POST for searches adheres to REST principles,
     *  as GET is intended for retrieving data without altering the server state.
     *
     * Idempotence, Caching, and Bookmarking are some of the benefits of using GET for searches.
     */
    Route::get('contacts/search', ContactSearchController::class);

    Route::apiResource('contacts', ContactController::class);

    Route::post('contacts/{contactId}/call', [ContactController::class, 'call'])
        ->name('contacts.call');
});
