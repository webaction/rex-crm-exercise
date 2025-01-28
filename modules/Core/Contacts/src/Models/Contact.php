<?php

namespace Modules\Core\Contacts\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Contacts\Database\Factories\ContactFactory;

/**
 * @property mixed|string $phone
 */
class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $guarded = ['id'];

    protected $fillable = [
        'tenant_id',
        'first_name',
        'last_name',
        'salutation',
        'suffix',
        'preferred_name',
        'job_title',
        'department',
        'contact_type',
        'status',
        'owner_id',
        'created_by',
        'updated_by',
    ];

    protected static function newFactory(): ContactFactory
    {
        return ContactFactory::new();
    }

    /**
     * @param $query
     * @param int $tenantId
     * @return mixed
     */
    public function scopeByTenant($query, int $tenantId): mixed
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * A contact belongs to a single tenant.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * A contact can have many addresses.
     */
    public function addresses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContactAddress::class);
    }

    /**
     * A contact can have many communication channels (phone, email, etc.).
     */
    public function channels(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContactChannel::class);
    }

    /**
     * The user who owns (is assigned to) this contact.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * The user who created this contact.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The user who last updated this contact.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Optionally, you can auto-fill created_by / updated_by using model events.
     */
    protected static function booted(): void
    {
        static::creating(function ($contact) {
            if (Auth::check()) {
                $contact->created_by = Auth::id();
                $contact->updated_by = Auth::id();
            }
        });

        static::updating(function ($contact) {
            if (Auth::check()) {
                $contact->updated_by = Auth::id();
            }
        });
    }
}
