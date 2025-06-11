<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = ['user_id', 'week_start','Tipo'];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }
    
}
