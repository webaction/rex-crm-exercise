<?php

namespace Modules\Core\Contacts\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Core\Contacts\Models\Contact;

/**
 * Validates that a contact field (phone or email) is unique within a given tenant.
 */
class UniqueTenantContact implements ValidationRule
{
    /**
     * The ID of the tenant.
     *
     * @var int|string
     */
    protected int|string $tenantId;

    /**
     * The contact field to check, e.g. 'email' or 'phone'.
     */
    protected string $field;

    /**
     * If updating an existing record, pass its ID to exclude it from uniqueness check.
     *
     * @var int|string|null
     */
    protected int|string|null $excludedId;

    /**
     * Create a new rule instance.
     *
     * @param int|string $tenantId
     * @param string $field e.g. 'email' or 'phone'
     * @param int|string|null $excludedId If updating an existing record, pass its ID here
     */
    public function __construct(
        int|string      $tenantId,
        string          $field,
        int|string|null $excludedId = null
    )
    {
        $this->tenantId = $tenantId;
        $this->field = $field;
        $this->excludedId = $excludedId;
    }

    /**
     * Perform validation using the newer ValidationRule interface.
     *
     * @param string $attribute The name of the attribute under validation
     * @param mixed $value The value of the attribute
     * @param \Closure $fail The callback to report validation failure
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 1) Build a query to check if the given (field => value) already exists in this tenant
        $query = Contact::query()
            ->where('tenant_id', $this->tenantId)
            ->where($this->field, $value);

        // 2) If updating (excludedId provided), exclude that record
        if ($this->excludedId !== null) {
            $query->where('id', '!=', $this->excludedId);
        }

        // 3) If such a record exists, fail
        if ($query->exists()) {
            $fail("The :attribute must be unique within this tenant.");
        }
    }
}
