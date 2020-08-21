<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('turno',array('Matutino','Vespertino'));
            $table->enum('metodo',array('Efectivo','Tarjeta'));
            $table->mediumText('concepto');
            $table->integer('cantidad');
            $table->dateTime('fechaHora');
            $table->enum('tipo',array('Ingreso','Egreso'));
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
        Schema::dropIfExists('transacciones');
    }
}
