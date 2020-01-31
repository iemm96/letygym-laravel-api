<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSociosMembresiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socios_membresias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_socio');
            $table->integer('id_tipoMembresia');
            $table->integer('fecha_fin');
            $table->integer('fecha_inicio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socios_membresias');
    }
}
