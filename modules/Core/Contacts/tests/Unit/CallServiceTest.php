<?php

namespace Tests\Unit;

use Tests\TestCase;
use Modules\Core\Contacts\Services\CallService;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Test;
use Mockery;

class CallServiceTest extends TestCase
{
    /**
     * Test successful call initiation.
     */
    #[Test]
    public function it_returns_success_on_successful_call()
    {
        $service = new CallService();

        $result = $service->makeCall('+61123456789');

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Call initiated successfully.', $result['message']);
        $this->assertEquals('CALL123456', $result['call_id']);
    }

    /**
     * Test call when the line is busy.
     */
    #[Test]
    public function it_handles_busy_line()
    {
        $service = new CallService();

        $result = $service->makeCall('+64987654321');

        $this->assertEquals('busy', $result['status']);
        $this->assertEquals('The line is busy.', $result['message']);
        $this->assertArrayNotHasKey('call_id', $result);
    }

    /**
     * Test call failure due to network error.
     */
    #[Test]
    public function it_handles_call_failure()
    {
        $service = new CallService();

        $result = $service->makeCall('+65000000000'); // Any other number

        $this->assertEquals('failed', $result['status']);
        $this->assertEquals('Failed to initiate call due to network error.', $result['message']);
        $this->assertArrayNotHasKey('call_id', $result);
    }

    /**
     * Test exception handling.
     */
    #[Test]
    public function it_handles_exceptions_gracefully()
    {
        // Mock the CallService to throw an exception
        $serviceMock = Mockery::mock(CallService::class)->makePartial();
        $serviceMock->shouldReceive('makeCall')
            ->with('+61123456789')
            ->andThrow(new \Exception('Test Exception'));

        // Spy on the Log facade
        Log::shouldReceive('error')
            ->once()
            ->with('CallService Error: Test Exception');

        $result = $serviceMock->makeCall('+61123456789');

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('An error occurred while trying to initiate the call.', $result['message']);
    }
}
