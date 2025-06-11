<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    protected $table = "foods";
    protected $fillable = ['name', 'category', 'calories', 'proteina_g', 'grasas_g', 'cho_d_g', 'cho_t_g', 
    'fibra_t_g', 'calcio_mg', 'fosforo_mg', 'hierro_mg', 'magnesio_mg', 'zinc_mg', 
    'cobre_mg', 'sodio_mg', 'potasio_mg', 'vitamina_a_er', 'b_caroteno_et', 
    'tiamina_mg', 'riboflavina_mg', 'niacina_mg', 'vitamina_b6_mg', 'acido_asc_mg', 
    'created_at', 'updated_at'];
}
