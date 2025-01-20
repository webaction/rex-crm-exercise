<?php

namespace Modules\Core\Contacts\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Modules\Core\Contacts\Events\ContactCalled;
use Modules\Core\Contacts\Jobs\MakeCallJob;
use Modules\Core\Contacts\Models\Contact;

class CallService
{
    public function __construct()
    {
        // Initialise the third-party client from the Integrations Modules
    }

    /**
     * Initiate a call to the given phone number.
     *
     * @param Contact $contact
     * @return array
     */
    public function makeCall(Contact $contact): array
    {
        $phoneNumber = $contact->phone;
        try {
            if ($phoneNumber === '+61123456789') {
                // Simulate a successful call
                // with an example event fired

                $callResult = [
                    'status' => 'success',
                    'message' => 'Call initiated successfully.',
                    'call_id' => 'CALL123456'
                ];
                event(new ContactCalled($contact, $callResult));

                return $callResult;
            } elseif ($phoneNumber === '+64987654321') {
                // Simulate a busy line
                return [
                    'status' => 'busy',
                    'message' => 'The line is busy.',
                ];
            } else {
                // Simulate a failure
                return [
                    'status' => 'failed',
                    'message' => 'Failed to initiate call due to network error.',
                ];
            }
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('CallService Error: ' . $e->getMessage());

            return [
                'status' => 'error',
                'message' => 'An error occurred while trying to initiate the call.'
            ];
        }
    }

    /**
     * @param Contact $contact
     * @return void
     */
    public function makeCallAsync(Contact $contact): void
    {
        dispatch(new MakeCallJob($contact));
    }
}
