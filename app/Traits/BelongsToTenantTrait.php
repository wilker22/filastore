<?php

namespace App\Traits;

use App\Models\Tenant;
use Filament\Facades\Filament;
use Filament\Forms\Components\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenantTrait
{

    public function scopeLoadWithTenant(EloquentBuilder $query, int $tenant = null)
    {

        $tenant = $tenant ?? Filament::getTenant();

        return $query->whereBelongsTo($tenant);
    }



    public function tenant() : BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
