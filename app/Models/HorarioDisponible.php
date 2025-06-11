<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioDisponible extends Model
{
    use HasFactory;

    protected $table = 'horarios_disponibles';

    protected $fillable = [
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'horario_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}


?>