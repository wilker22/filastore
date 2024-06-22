<?php

namespace App\Traits;

use App\Models\Tenant;
use Filament\Facades\Filament;
use Filament\Forms\Components\Builder;

trait BelongsToTenantTrait 
{
    
    public function scopeLoadTenant(Builder $query, int $tenant = null)
    {
        $tenant = $tenant ?? Filament::getTenant();

        return $query->whereBelongsTo($tenant);
    }



    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}