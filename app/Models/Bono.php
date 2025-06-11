<?php
// app/Models/Bono.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bono extends Model
{
    use HasFactory;

    protected $table = 'bonos';
    protected $fillable = ['nombre', 'descripcion', 'precio', 'sesiones', 'tenant_id'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function usuarios()
    {
        return $this->hasMany(UsuarioBono::class);
    }
    public function users()
{
    return $this->belongsToMany(User::class, 'usuario_bonos')
        ->withPivot('pagado', 'sesiones_restantes');
}

}
