<?php

namespace Modules\Core\Contacts\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Modules\Core\Contacts\Data\ContactData;
use Modules\Core\Contacts\Models\Contact;
use Modules\Core\Contacts\Notifications\ContactCreated;

class ContactService
{
    /**
     * @throws \Exception
     */
    public function createContact(ContactData $data): Contact
    {
        try {
            return DB::transaction(function () use ($data) {
                $contact = Contact::create($data->toArray());

                $this->syncAddresses($contact, $data->addresses, $data->tenant_id);
                $this->syncChannels($contact, $data->channels, $data->tenant_id);

                $contact->load('channels', 'addresses');

                Notification::send($contact, new ContactCreated($contact, $data->tenant_id));
                Event::dispatch('contact.created', $contact);

                return $contact;
            });
        } catch (ValidationException $e) {
            // Handle validation exceptions, typically for HTTP API usage
            Log::warning('Validation failed while creating contact: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            // Log error for both CLI and HTTP environments
            Log::error('Failed to create contact: ' . $e->getMessage(), [
                'data' => $data->toArray(),
                'exception' => $e
            ]);

            // Re-throw exception with a generic message for external consumers
            throw new \Exception('An error occurred while creating the contact. Please try again later.');
        }
    }

    public function getPaginatedContacts(int $tenantId, int $perPage = 10)
    {
        $contacts = Contact::byTenant($tenantId)->cursorPaginate();

        return $this->transformContacts($contacts->getCollection(), $contacts);
    }

    public function getAllContacts(int $tenantId)
    {
        $contacts = Contact::byTenant($tenantId)->get();

        return $this->transformContacts($contacts);
    }

    public function searchContacts($request) {
        // Implement search logic here
    }

    private function syncAddresses(Contact $contact, $addresses, int $tenantId): void
    {
        if ($addresses) {
            foreach ($addresses as $address) {
                $contact->addresses()->updateOrCreate(
                    ['id' => $address->id ?? null], // Match by ID if it exists
                    array_merge($address->toArray(), [
                        'tenant_id' => $tenantId,
                    ])
                );
            }
        }
    }

    private function syncChannels(Contact $contact, $channels, int $tenantId): void
    {
        if ($channels) {
            foreach ($channels as $channel) {
                $contact->channels()->updateOrCreate(
                    ['id' => $channel->id ?? null], // Match by ID if it exists
                    array_merge($channel->toArray(), [
                        'tenant_id' => $tenantId,
                    ])
                );
            }
        }
    }

    private function transformContacts($contacts, $paginatedResult = null)
    {
        $transformed = $contacts->map(function ($contact) {
            return ContactData::from($contact);
        });

        // If it's a paginated result, replace the collection with the transformed one
        if ($paginatedResult) {
            $paginatedResult->setCollection($transformed);
            return $paginatedResult;
        }

        return $transformed;
    }
}
