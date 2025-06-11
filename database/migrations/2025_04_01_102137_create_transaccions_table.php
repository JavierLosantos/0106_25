<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['ingreso', 'gasto']);
            $table->decimal('monto', 10, 2);
            $table->string('descripcion')->nullable();
            $table->date('fecha');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaccions');
    }
};
