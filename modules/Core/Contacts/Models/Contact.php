<?php

namespace Modules\Core\Contacts\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $guarded = ['id'];

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
    ];

    /**
     * @param $query
     * @param int $tenantId
     * @return mixed
     */
    public function scopeByTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }
}
