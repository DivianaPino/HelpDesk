<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('respuesta_id')->nullable(); 
            $table->unsignedBigInteger('ticket_id');
            $table->text('mensaje')->nullable();
            $table->string('nivel_satisfaccion')->nullable();
            $table->boolean('bool_reabrir')->default(false);
            $table->timestamps();

            $table->foreign('respuesta_id')->references('id')->on('respuestas')->onDelete('cascade');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comentarios');
    }
}
