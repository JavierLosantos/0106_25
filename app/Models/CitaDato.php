<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CitaDato extends Model
{
    protected $table = 'cita_datos';

   protected $fillable = [
        'cita_id',
        'peso',
        'altura',
        'imc',
        'cansado',
        'agua',
        'grasa',
        'brazo_izq',
        'brazo_der',
        'tronco',
        'pierna_izq',
        'pierna_der',
        'masa_muscular',
        'masa_muscular_brazo_izq',
        'masa_muscular_brazo_der',
        'masa_muscular_tronco',
        'masa_muscular_pierna_izq',
        'masa_muscular_pierna_der',
        'masa_osea',
        'edad_metabolica',
        'grasa_visceral',
    ];

    
    
    protected $casts = [
    'peso' => 'decimal:2',
    'altura' => 'decimal:2',
    'agua' => 'decimal:2',
    'grasa' => 'decimal:2',
    'imc' => 'decimal:2',
    'brazo_izq' => 'decimal:2',
    'brazo_der' => 'decimal:2',
    'tronco' => 'decimal:2',
    'pierna_izq' => 'decimal:2',
    'pierna_der' => 'decimal:2',
    'masa_muscular' => 'decimal:2',
    'masa_muscular_brazo_izq' => 'decimal:2',
    'masa_muscular_brazo_der' => 'decimal:2',
    'masa_muscular_tronco' => 'decimal:2',
    'masa_muscular_pierna_izq' => 'decimal:2',
    'masa_muscular_pierna_der' => 'decimal:2',
    'masa_osea' => 'decimal:2',
    'edad_metabolica' => 'integer',
    'grasa_visceral' => 'decimal:2',
];

}
