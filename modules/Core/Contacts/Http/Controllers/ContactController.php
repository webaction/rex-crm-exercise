<?php

namespace Modules\Core\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Contacts\Http\Requests\StoreContactRequest;
use Modules\Core\Contacts\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * GET /api/tenants/{tenantId}/contacts?phone=+6412345678&domain=gmail.com
     */
    public function index(Request $request, int $tenantId): JsonResponse
    {
        // Scope by tenant
        $contactsQuery = Contact::byTenant($tenantId);

        // Optional filtering by phone
        if ($phone = $request->query('phone')) {
            $contactsQuery->where('phone', $phone);
        }

        // Optional filtering by email domain
        if ($domain = $request->query('domain')) {
            $contactsQuery->where('email', 'LIKE', "%@{$domain}");
        }

        // Paginate (or retrieve all if you'd prefer ->get())
        $contacts = $contactsQuery->paginate(20);

        return response()->json($contacts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * POST /api/tenants/{tenantId}/contacts
     */
    public function store(StoreContactRequest $request, int $tenantId): JsonResponse
    {
        // Validate and merge tenant ID into the data
        $validated = $request->validated();
        $validated['tenant_id'] = $tenantId;

        $contact = Contact::create($validated);

        return response()->json($contact, 201);
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
}
