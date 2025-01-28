<?php

namespace Modules\Core\Contacts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Contacts\Database\Factories\TenantFactory;

class Tenant extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * By default, Eloquent assumes "tenants"
     */
     protected $table = 'tenants';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'domain',
        // other tenant-related fields
    ];

    protected static function newFactory(): TenantFactory
    {
        return TenantFactory::new();
    }

    /**
     * A tenant has many contacts.
     */
    public function contacts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
