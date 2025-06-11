<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaccionView extends Model
{
    use HasFactory;
    protected $table = 'v_transacciones';
    public $timestamps = false; // Porque las vistas no tienen created_at y updated_at
}
