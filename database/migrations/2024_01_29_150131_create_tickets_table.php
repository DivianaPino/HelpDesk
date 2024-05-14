<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('clasificacion_id');
            $table->unsignedBigInteger('estado_id');
            $table->unsignedBigInteger('prioridad_id');
            $table->string('asunto');
            $table->text('mensaje');
            $table->string('asignado_a')->nullable();
            $table->text('imagen')->nullable();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_caducidad')->nullable();
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('estado_id')->references('id')->on('estados')->onDelete('cascade');
            $table->foreign('prioridad_id')->references('id')->on('prioridads')->onDelete('cascade');  
            $table->foreign('clasificacion_id')->references('id')->on('clasificacions')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
