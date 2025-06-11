<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu_dias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->string('day'); // monday, tuesday...
            $table->string('meal'); // breakfast, lunch...
            $table->enum('tipo', ['Producto', 'Receta']);
            $table->json('items'); // lista de ids (producto_id o receta_id)
            $table->decimal('total_calories', 8, 2)->default(0);
            $table->decimal('total_protein_g', 8, 2)->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_dias');
    }
};
