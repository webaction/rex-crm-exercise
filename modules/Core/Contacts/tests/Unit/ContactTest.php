<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Contacts\src\Models\Contact;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_scope_contacts_by_tenant()
    {
        // Given we have contacts for two different tenants
        Contact::factory()->create(['tenant_id' => 1, 'email' => 'tenant1@example.com']);
        Contact::factory()->create(['tenant_id' => 2, 'email' => 'tenant2@example.com']);

        // When we call the scope for tenant_id=1
        $tenant1Contacts = Contact::byTenant(1)->get();

        // Then we should get only the contacts for tenant 1
        $this->assertCount(1, $tenant1Contacts);
        $this->assertEquals('tenant1@example.com', $tenant1Contacts->first()->email);
    }
}
