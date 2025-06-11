<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    protected $fillable = ['menu_id','day','meal','food_id','portion'];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class);
    }
}