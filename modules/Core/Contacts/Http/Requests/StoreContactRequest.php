<?php

namespace Modules\Core\Contacts\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Contacts\Models\Contact;

class StoreContactRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // Tenant must be provided and valid for each contact
            'tenant_id' => [
                'required',
                'integer',
            ],

            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                // Unique within the same tenant
                Rule::unique(Contact::class, 'email')->where(function (Builder $query) {
                    return $query->where('tenant_id', $this->input('tenant_id'));
                })
            ],

            'phone' => [
                'required',
                'string',
                // Unique within the same tenant
                Rule::unique('Modules\Core\Contacts\Models\Contact', 'phone')->where(function ($query) {
                    return $query->where('tenant_id', $this->input('tenant_id'));
                }),
                // Custom E.164 + region check
                function ($attribute, $value, $fail) {
                    // Basic E.164 pattern check: + followed by up to 15 digits
                    if (!preg_match("/^\+[1-9]\d{1,14}$/", $value)) {
                        $fail('The phone number must be in valid E.164 format, e.g. +6412345678.');
                    }

                    // Only allow +61 (Australia) or +64 (New Zealand)
                    if (!(str_starts_with($value, '+61') || str_starts_with($value, '+64'))) {
                        $fail('Only Australian (+61) or New Zealand (+64) numbers are allowed.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'tenant_id.required' => 'The Tenant ID field is required.',
            'tenant_id.integer' => 'The Tenant ID must be an integer.',
            'email.unique' => 'A user with this email already exists. Please choose a different email.',
            'phone.unique' => 'A user with this phone number already exists. Please enter a different number.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
