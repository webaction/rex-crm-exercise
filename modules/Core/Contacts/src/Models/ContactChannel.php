<?php

namespace Modules\Core\Contacts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Contacts\Database\Factories\ContactChannelFactory;

class ContactChannel extends Model
{
    use HasFactory;

    /**
     * By default, Eloquent assumes the table is "contact_channels".
     */
    protected $table = 'contact_channels';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id',
        'contact_id',
        'channel_type',
        'value',
        'is_primary',
    ];

    protected static function newFactory(): ContactChannelFactory
    {
        return ContactChannelFactory::new();
    }

    /**
     * A contact channel belongs to a tenant.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * A contact channel belongs to a single contact.
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
