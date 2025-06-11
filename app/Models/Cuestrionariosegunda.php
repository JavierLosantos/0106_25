<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuestrionariosegunda extends Model
{
    use HasFactory;

    // Si el nombre de la tabla no es plural o sigue el estándar de Laravel, puedes especificarlo.
    protected $table = 'cuestionario_segunda_visita';

    // Si el nombre de la clave primaria no es 'id', lo debes especificar también.
    protected $primaryKey = 'id';

    // Si la tabla no usa las columnas `created_at` y `updated_at`, puedes deshabilitarlas:
    public $timestamps = true;

    // Especificar los campos que se pueden asignar masivamente (mass assignable)
    protected $fillable = [
        'id',
        'descripcion',
        'cita_id',
        'user_id',
        'created_at',
        'updated_at',
    ];
}
