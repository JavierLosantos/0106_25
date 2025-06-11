<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono',
        'fecha_nacimiento',
        'genero',
        'peso',
        'altura',
        'tipo_alimentacion',
        'deportista',
        'status',
        'empresa_id',
        'role',
        'consultas',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'fecha_nacimiento' => 'date',
            'deportista' => 'boolean',
        ];
    }

    /**
     * Relación con la tabla tenants (multitenancy).
     */
    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'tenant_user');
    }
    public function files()
    {
        return $this->hasMany(UserFile::class);
     
    }
    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'user_id');
    }
    public function bonos()
{
    return $this->belongsToMany(Bono::class, 'usuario_bonos')
        ->withPivot('pagado', 'sesiones_restantes');
}

}
