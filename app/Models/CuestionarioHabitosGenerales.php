<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuestionarioHabitosGenerales extends Model
{
    use HasFactory;

    // Si el nombre de la tabla no es plural o sigue el estándar de Laravel, puedes especificarlo.
    protected $table = 'cuestionario_habitos_generales';

    // Si el nombre de la clave primaria no es 'id', lo debes especificar también.
    protected $primaryKey = 'id';

    // Si la tabla no usa las columnas `created_at` y `updated_at`, puedes deshabilitarlas:
    public $timestamps = true;

    // Especificar los campos que se pueden asignar masivamente (mass assignable)
    protected $fillable = [
        'comidas_por_dia',
        'desayuno',
        'media_manana',
        'comida',
        'merienda',
        'cena',
        'comida_preferida',
        'tiene_habito_especial',
        'descripcion_habito_especial',
        'primera_ingesta_dia',
        'personas_en_casa',
        'quien_cocina_compra',
        'supermercado',
        'come_fuera_lunes_a_viernes',
        'come_fuera_fin_de_semana',
        'comidas_fuera_fin_de_semana',
        'bebida_semana',
        'bebida_fin_de_semana',
        'uso_sal',
        'uso_azucar',
        'uso_edulcorante',
        'tipo_grasa_cocinar',
        'aliño_ensalada',
        'picoteo_entre_horas',
        'cambios_alimentacion_ult_3_meses',
        'alergias',
        'intolerancias',
        'medicacion',
        'hace_ejercicio',
        'dias_ejercicio_semana',
        'duracion_ejercicio',
        'tipo_entrenamiento',
        'hora_entrenamiento',
        'cita_id',
        'user_id',
    ];
}
