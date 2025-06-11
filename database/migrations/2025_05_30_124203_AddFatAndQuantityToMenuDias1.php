<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFatAndQuantityToMenuDias1 extends Migration
{
    public function up()
    {
        Schema::table('menu_dias', function (Blueprint $table) {
            $table->decimal('total_carbohidratos_g', 8, 2)->default(0)->after('total_protein_g');
           
        });
    }

   
}
