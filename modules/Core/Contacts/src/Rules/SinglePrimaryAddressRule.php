<?php

namespace Modules\Core\Contacts\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class SinglePrimaryAddress implements ValidationRule
{
    /**
     * @param  int       $tenantId
     * @param  int       $contactId
     * @param  int|null  $ignoreId  If updating an existing address, pass its ID here
     */
    public function __construct(
        protected int $tenantId,
        protected int $contactId,
        protected ?int $ignoreId = null
    ) {
    }

    /**
     * Validate the attribute.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // If is_primary = false (or not set), no special check needed
        if (!$value) {
            return;
        }

        // If is_primary = true, ensure no other row for (tenant_id, contact_id) is already primary
        $query = DB::table('contact_addresses')
            ->where('tenant_id', $this->tenantId)
            ->where('contact_id', $this->contactId)
            ->where('is_primary', true);

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        if ($query->exists()) {
            $fail("Only one address can be set to primary for this contact.");
        }
    }
}
