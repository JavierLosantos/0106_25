<?php
// app/Models/UsuarioBono.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioBono extends Model
{
    use HasFactory;

    protected $table = 'usuario_bonos';
    protected $fillable = ['user_id', 'bono_id', 'sesiones_restantes','pagado'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bono()
    {
        return $this->belongsTo(Bono::class, 'bono_id');
    }
}