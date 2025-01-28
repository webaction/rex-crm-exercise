<?php

namespace Modules\Core\Contacts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Contacts\Database\Factories\ContactAddressFactory;

class ContactAddress extends Model
{
    use HasFactory;

    /**
     * By default, Eloquent assumes the table is "contact_addresses".
     */
    protected $table = 'contact_addresses';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id',
        'contact_id',
        'address_type',
        'line1',
        'line2',
        'city',
        'state',
        'postal_code',
        'country',
        'is_primary',
    ];

    protected static function newFactory(): ContactAddressFactory
    {
        return ContactAddressFactory::new();
    }

    /**
     * A contact address belongs to a tenant.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * A contact address belongs to a single contact.
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
