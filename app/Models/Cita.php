<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'horario_id', 'estado','tenant_id','pagado','Bono'];

    // Relación con horarios
    public function horario()
    {
        return $this->belongsTo(HorarioDisponible::class);
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    // En Cita.php
public function datos() {
    return $this->hasOne(CitaDato::class, 'cita_id');
}

}


?>