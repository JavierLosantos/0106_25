<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    use HasFactory;

    protected $table = 'transacciones'; // Nombre de la tabla en la BD

    protected $fillable = ['tipo', 'monto', 'descripcion', 'fecha','id_cita','id_tenant','id_cita'];
}
