<?php

namespace Modules\Core\Contacts\Data;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class ChannelData extends Data
{
    public function __construct(
        #[Rule('integer|min:1')]
        public int     $id,

        #[Rule('required|integer|min:1')]
        public int     $tenantId,

        #[Rule('required|integer|min:1')]
        public int     $contactId,

        #[Rule('required|string|max:50|in:PHONE,EMAIL,SOCIAL')]
        public string  $channelType,

        // Custom E164 validation applied conditionally for phone numbers
        #[Rule('required|string|max:255')]
        public string  $value,

        #[Rule('required|boolean')]
        public bool    $isPrimary,

        #[Rule('nullable|date_format:Y-m-d H:i:s')]
        public ?string $createdAt = null,

        #[Rule('nullable|date_format:Y-m-d H:i:s')]
        public ?string $updatedAt = null
    )
    {
    }

    public function rules(): array
    {
        $rules = [
            'value' => ['required', 'string', 'max:255'],
        ];

        if ($this->channelType === 'PHONE') {
            $rules['value'][] = 'regex:/^\+?[1-9]\d{1,14}$/';
        }

        return $rules;
    }
}
