<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFatAndQuantityToMenuDias extends Migration
{
    public function up()
    {
        Schema::table('menu_dias', function (Blueprint $table) {
            $table->decimal('total_fat_g', 8, 2)->default(0)->after('total_protein_g');
            $table->json('quantities')->nullable()->after('items'); // clave: id, valor: cantidad
        });
    }

    public function down()
    {
        Schema::table('menu_dias', function (Blueprint $table) {
            $table->dropColumn('total_fat_g');
            $table->dropColumn('quantities');
        });
    }
}
