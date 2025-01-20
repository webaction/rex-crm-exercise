<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Contacts\Models\Contact;

class ContactsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    #[Test]
    public function it_can_create_a_contact()
    {
        $payload = [
            'tenant_id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+61123456789',
        ];

        $response = $this->postJson('/api/tenants/1/contacts', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contacts', [
            'tenant_id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+61123456789',
        ]);
    }

    #[Test]
    public function it_can_list_contacts_for_a_tenant()
    {
        // Create some contacts
        Contact::factory()->create(['tenant_id' => 1, 'name' => 'User1']);
        Contact::factory()->create(['tenant_id' => 1, 'name' => 'User2']);
        Contact::factory()->create(['tenant_id' => 2, 'name' => 'AnotherTenantUser']);

        $response = $this->getJson('/api/tenants/1/contacts');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'User1'])
            ->assertJsonFragment(['name' => 'User2'])
            ->assertJsonMissing(['name' => 'AnotherTenantUser']); // belongs to tenant_id=2
    }

    #[Test]
    public function it_can_filter_contacts_by_phone()
    {
        Contact::factory()->create([
            'tenant_id' => 1,
            'phone' => '+64123456789',
            'email' => 'nz@example.com',
        ]);
        Contact::factory()->create([
            'tenant_id' => 1,
            'phone' => '+61123456789',
            'email' => 'au@example.com',
        ]);

        $response = $this->getJson('/api/tenants/1/contacts?phone=%2B64123456789');
        $response->assertStatus(200)
            ->assertJsonFragment(['email' => 'nz@example.com'])
            ->assertJsonMissing(['email' => 'au@example.com']);
    }

    #[Test]
    public function it_can_show_a_specific_contact_within_tenant_scope()
    {
        $contact = Contact::factory()->create([
            'tenant_id' => 1,
            'name' => 'TargetUser',
        ]);

        $response = $this->getJson("/api/tenants/1/contacts/{$contact->id}");
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'TargetUser']);
    }

    #[Test]
    public function it_returns_404_when_contact_not_in_tenant()
    {
        $contact = Contact::factory()->create([
            'tenant_id' => 2,
            'name' => 'OtherTenantContact',
        ]);

        // Trying to access the contact from tenant_id=1
        $response = $this->getJson("/api/tenants/1/contacts/{$contact->id}");
        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_update_a_contact()
    {
        $contact = Contact::factory()->create([
            'tenant_id' => 1,
            'name' => 'Old Name',
        ]);

        $payload = [
            'tenant_id' => 1,
            'name' => 'New Name',
            'email' => 'new@example.com',
            'phone' => '+64111111111',
        ];

        $response = $this->putJson("/api/tenants/1/contacts/{$contact->id}", $payload);
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'New Name']);

        // Confirm the DB record was updated
        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'name' => 'New Name',
        ]);
    }

    #[Test]
    public function it_can_delete_a_contact()
    {
        $contact = Contact::factory()->create([
            'tenant_id' => 1,
        ]);

        $response = $this->deleteJson("/api/tenants/1/contacts/{$contact->id}");
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Contact deleted successfully.']);

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }
}
