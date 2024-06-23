<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;
    protected $guarded = [];

    //refere-se aos nossos inquilinos
    public function members() : BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function stores() : HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function products() : HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function categories() : HasMany
    {
        return $this->hasMany(category::class);
    }

    //refere-se aos usuarios dos nossos inquilinos
    public function users() : HasMany
    {
        return $this->hasMany(User::class);
    }
}
