<?php

namespace Modules\Core\Contacts\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Contacts\Traits\AddressValidationRules;


//use Modules\Core\Contacts\Rules\UniqueTenantChannel; // ← Updated rule that checks contact_channels

class StoreContactRequest extends BaseRequest
{
    use AddressValidationRules;

    /**
     * Merge the tenantId route parameter into the request data.
     */
    protected function prepareForValidation(): void
    {
        // Merge tenant_id from the route into the request data
        $this->merge([
            'tenant_id' => $this->route('tenant_id'),
        ]);
    }

    /**
     * The validation rules.
     */
    public function rules(): array
    {
        // Basic contact rules:
        $contactRules = [
            'tenant_id' => ['required', 'integer', 'exists:tenants,id'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'salutation' => ['nullable', 'string', 'max:50'],
            'suffix'     => ['nullable', 'string', 'max:50'],
            'preferred_name' => ['nullable', 'string', 'max:100'],
            'job_title'  => ['nullable', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:100'],
            'contact_type' => ['nullable', Rule::in([
                'Buyer', 'Seller', 'Tenant', 'Landlord', 'Agent', 'Mortgage Broker', 'Other'
            ])],
            'status' => ['nullable', Rule::in(['Active', 'Inactive', 'Archive', 'Deleted'])],
            'addresses' => ['sometimes', 'array'],
        ];

        foreach ($this->addressRules($this->input('tenant_id'), $this->input('contact_id')) as $field => $rules) {
            $contactRules["addresses.*.$field"] = $rules;
        }

        return $contactRules;
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'tenant_id.required' => 'The Tenant ID field is required.',
            'tenant_id.integer' => 'The Tenant ID must be an integer.',
            'tenant_id.exists' => 'The specified Tenant does not exist.',

            'first_name.required' => 'Please provide the first name.',
            'last_name.required' => 'Please provide the last name.',

            'email.required' => 'Please provide an email address.',
            'email.email' => 'You must provide a valid email address.',
            'phone.required' => 'Please provide a phone number.',

            // You can also override the default error messages from your unique/phone rules if needed:
            'email.unique' => 'A contact with this email already exists for this tenant.',
            'phone.unique' => 'A contact with this phone number already exists for this tenant.',
        ];
    }
}
