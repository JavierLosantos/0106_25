<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantUser extends Model
{
    use HasFactory;

    // Si la tabla tiene un nombre diferente o se usa una tabla intermedia,
    // puedes especificar la tabla explícitamente
    protected $table = 'tenant_user';

    // No necesitamos definir $fillable o $guarded si no estamos interactuando directamente con la tabla
    // ya que solo es una tabla intermedia

    // Si necesitas manipular más campos en la tabla intermedia, como timestamps,
    // puedes hacer lo siguiente:

    public $timestamps = true; // Si usas created_at y updated_at en la tabla

    // Si no tienes estos campos en la tabla, usa false:
    // public $timestamps = false;
}
