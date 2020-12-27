<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string("nome", 128);
            $table->char("cpf", 11)->unique();
            $table->dateTime("dataNascimento");
            $table->string("email")->unique();
            $table->string("telefone", 15);
            $table->unsignedBigInteger("endereco_id");
            $table->timestamps();

            $table->foreign("endereco_id")->references("id")->on("enderecos")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
