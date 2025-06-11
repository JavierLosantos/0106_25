<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    use HasFactory;
 protected $table = 'imagenes';
 
    protected $fillable = [
        'consulta_id', 
        'user_id', 
        'imagen_path',
    ];

    /**
     * Relación con el modelo Consulta.
     */
    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }

    /**
     * Relación con el modelo User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}



?>