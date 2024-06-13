<?php

namespace App\Models;

use App\Traits\BelongsToTenantTrait;
use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory, BelongsToTenantTrait;

    protected $guarded = [];

    public function price(): Attribute
    {
        return new Attribute(get: fn($attr) => $attr / 100);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
