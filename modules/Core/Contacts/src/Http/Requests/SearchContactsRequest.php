<?php

namespace Modules\Core\Contacts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Contacts\Rules\PhoneRule;

class SearchContactsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => [
                'nullable',
                'string',
                new PhoneRule(config('contacts.phone_validation_rules_country_codes')),
            ],
            'sort' => 'nullable|in:name,email,phone',
            'order' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}
