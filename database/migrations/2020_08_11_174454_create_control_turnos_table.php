<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlTurnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('control_turnos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('turno',array('Matutino','Vespertino'));
            $table->dateTime('fechaHora_inicio');
            $table->dateTime('fechaHora_fin');
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
        Schema::dropIfExists('control_turnos');
    }
}
