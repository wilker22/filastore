<?php

namespace App\Traits;

use App\Models\Tenant;

trait BelongsToTenantTrait 
{
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}