<?php

namespace Modules\Core\Contacts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Contacts\Traits\AddressValidationRules;

class StoreContactAddressRequest extends FormRequest
{
    use AddressValidationRules;
    protected function prepareForValidation(): void
    {
        // Merge tenant_id & contact_id from route if needed
        $this->merge([
            'tenant_id' => $this->route('tenantId'),
            'contact_id' => $this->route('contactId'),
        ]);
    }

    public function authorize(): bool
    {
        // Implement your authorization logic or return true
        return true;
    }

    public function rules(): array
    {
        return $this->addressRules($this->input('tenant_id'), $this->input('contact_id'));
    }


    public function messages(): array
    {
        return [
            'tenant_id.required' => 'Tenant ID is required.',
            'tenant_id.exists' => 'Tenant does not exist.',
            'contact_id.required' => 'Contact ID is required.',
            'contact_id.exists' => 'Contact does not exist.',
            'line1.required' => 'Line1 is required.',
            'city.required' => 'City is required.',
            'postal_code.max' => 'Postal code must not exceed 20 characters.',
        ];
    }
}
