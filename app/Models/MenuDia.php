<?php
// app/Models/MenuDia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuDia extends Model
{
    protected $fillable = [
        'menu_id',
        'usuario_id',
        'day',
        'meal',
        'tipo',
        'items',
        'total_calories',
        'total_protein_g',
        'quantities',
        'total_fat_g',
        'total_carbohidratos_g',
    ];

    protected $casts = [
    'items' => 'array',
    'quantities' => 'array',
    ];
}
