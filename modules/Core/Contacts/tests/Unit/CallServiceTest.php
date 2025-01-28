<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Mockery;
use Modules\Core\Contacts\src\Events\ContactCalled;
use Modules\Core\Contacts\src\Models\Contact;
use Modules\Core\Contacts\src\Services\CallService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CallServiceTest extends TestCase
{
    /**
     * Clean up Mockery after each test.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test successful call initiation and event dispatching.
     */
    #[Test]
    public function it_returns_success_on_successful_call_and_fires_event()
    {
        // Fake all events to prevent actual event dispatching
        Event::fake();

        // Create a contact with phone '+61123456789' which simulates a successful call
        $contact = Contact::factory()->create([
            'phone' => '+61123456789',
        ]);

        $service = new CallService();

        $result = $service->makeCall($contact);

        // Assert the returned array contains the expected success data
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Call initiated successfully.', $result['message']);
        $this->assertEquals('CALL123456', $result['call_id']);

        // Assert that the ContactCalled event was dispatched with correct parameters
        Event::assertDispatched(ContactCalled::class, function ($event) use ($contact, $result) {
            return $event->contact->id === $contact->id &&
                $event->callResult['status'] === $result['status'] &&
                $event->callResult['call_id'] === $result['call_id'];
        });
    }

    /**
     * Test call initiation when the line is busy.
     */
    #[Test]
    public function it_returns_busy_status_on_busy_line()
    {
        // Fake all events
        Event::fake();

        // Create a contact with phone '+64987654321' which simulates a busy line
        $contact = Contact::factory()->create([
            'phone' => '+64987654321',
        ]);

        $service = new CallService();

        $result = $service->makeCall($contact);

        // Assert the returned array contains the expected busy data
        $this->assertEquals('busy', $result['status']);
        $this->assertEquals('The line is busy.', $result['message']);

        // Assert that no ContactCalled event was dispatched
        Event::assertNotDispatched(ContactCalled::class);
    }

    /**
     * Test call initiation failure due to network error.
     */
    #[Test]
    public function it_returns_failed_status_on_call_failure()
    {
        // Fake all events
        Event::fake();

        // Create a contact with an unrecognized phone number to simulate failure
        $contact = Contact::factory()->create([
            'phone' => '+65000000000',
        ]);

        $service = new CallService();

        $result = $service->makeCall($contact);

        // Assert the returned array contains the expected failure data
        $this->assertEquals('failed', $result['status']);
        $this->assertEquals('Failed to initiate call due to network error.', $result['message']);

        // Assert that no ContactCalled event was dispatched
        Event::assertNotDispatched(ContactCalled::class);
    }

    /**
     * Test exception handling within the makeCall method.
     */
    #[Test]
    public function it_handles_exceptions_gracefully()
    {
        // Create a contact with phone '+61123456789' to simulate a successful call
        $contact = Contact::factory()->create([
            'tenant_id' => 4,
            'phone' => '+61123456789',
        ]);

        // Mock the Event facade to throw an exception when ContactCalled is dispatched
        Event::shouldReceive('dispatch')
            ->once()
            ->with(Mockery::on(function ($event) use ($contact) {
                return $event instanceof ContactCalled &&
                    $event->contact->id === $contact->id;
            }))
            ->andThrow(new \Exception('Service unavailable'));

        // Expect the Log facade to receive an error log
        Log::shouldReceive('error')
            ->once()
            ->with('CallService Error: Service unavailable');

        $service = new CallService();

        $result = $service->makeCall($contact);

        // Assert the returned array contains the expected error data
        $this->assertEquals('error', $result['status']);
        $this->assertEquals('An error occurred while trying to initiate the call.', $result['message']);
    }
}
