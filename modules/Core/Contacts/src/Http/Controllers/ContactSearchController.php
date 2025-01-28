<?php

namespace Modules\Core\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Modules\Core\Contacts\Http\Requests\SearchContactsRequest;
use Modules\Core\Contacts\Models\Contact;
use Modules\Core\Contacts\Services\ContactService;

class ContactSearchController extends Controller
{
    /**
     * @param SearchContactsRequest $request
     * @param int $tenantId
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function __invoke(SearchContactsRequest $request, int $tenantId): JsonResponse
    {
        $contactService = app()->make(ContactService::class);
        return response()->json($contactService->searchContacts($request));
    }
}
