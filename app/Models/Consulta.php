<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'texto_largo',
        'Revisado',
    ];

    /**
     * Relación con el modelo User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el modelo Imagen.
     */
    public function imagenes()
    {
        return $this->hasMany(Imagen::class);
    }
}


?>