<?php

namespace Modules\Core\Contacts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Contacts\database\factories\ContactFactory;

/**
 * @method static byTenant(int $tenantId)
 * @property mixed|string $phone
 */
class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $guarded = ['id'];

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
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
}
