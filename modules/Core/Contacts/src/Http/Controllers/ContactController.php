<?php

namespace Modules\Core\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Contacts\Actions\CreateContactAction;
use Modules\Core\Contacts\Data\ContactData;
use Modules\Core\Contacts\Http\Requests\StoreContactRequest;
use Modules\Core\Contacts\Models\Contact;
use Modules\Core\Contacts\Services\CallService;
use Modules\Core\Contacts\Services\ContactService;

class ContactController extends Controller
{
    protected CallService $callService;
    protected ContactService $contactService;

    public function __construct(CallService $callService, ContactService $contactService)
    {
        $this->callService = $callService;
        $this->contactService = $contactService;
    }

    /**
     * Display a listing of the resource.
     *
     * GET /api/tenants/{tenantId}/contacts
     */
    public function index(int $tenantId): JsonResponse
    {
        return response()->json($this->contactService->getPaginatedContacts($tenantId));
    }

    /**
     * Store a newly created resource in storage.
     *
     * POST /api/tenants/{tenantId}/contacts
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        try {
            return response()->json($this->contactService->createContact(ContactData::from($request->validated())), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * GET /api/tenants/{tenantId}/contacts/{contactId}
     */
    public function show(int $tenantId, int $contactId): JsonResponse
    {
        // Fetch via the tenant scope
        $contact = Contact::byTenant($tenantId)->findOrFail($contactId);

        return response()->json($contact);
    }

    /**
     * Update the specified resource in storage.
     *
     * PUT /api/tenants/{tenantId}/contacts/{contactId}
     * or PATCH /api/tenants/{tenantId}/contacts/{contactId}
     */
    public function update(StoreContactRequest $request, int $tenantId, int $contactId): JsonResponse
    {
        $contact = Contact::byTenant($tenantId)->findOrFail($contactId);

        // Validate & update
        $validated = $request->validated();
        $validated['tenant_id'] = $tenantId; // Ensure tenant remains consistent
        $contact->update($validated);

        return response()->json($contact);
    }

    /**
     * Remove the specified resource from storage.
     *
     * DELETE /api/tenants/{tenantId}/contacts/{contactId}
     */
    public function destroy(int $tenantId, int $contactId): JsonResponse
    {
        $contact = Contact::byTenant($tenantId)->findOrFail($contactId);
        $contact->delete();

        return response()->json(['message' => 'Contact deleted successfully.']);
    }

    /**
     * Initiate a call to the specified contact.
     *
     * POST /api/tenants/{tenantId}/contacts/{contactId}/call
     */
    public function call(int $tenantId, int $contactId): JsonResponse
    {
        // Fetch the contact within the tenant's scope
        $contact = Contact::byTenant($tenantId)->findOrFail($contactId);

        // Initiate the call using the CallService
        $callResult = $this->callService->makeCall($contact);

        // Handle different outcomes
        return match ($callResult['status']) {
            'success' => response()->json([
                'message' => $callResult['message'],
                'call_id' => $callResult['call_id'],
            ], 200),
            'failed', 'busy' => response()->json([
                'message' => $callResult['message'],
            ], 200),
            'error' => response()->json([
                'message' => $callResult['message'],
            ], 500),
            default => response()->json([
                'message' => 'Unknown call status.',
            ], 500),
        };
    }
}
