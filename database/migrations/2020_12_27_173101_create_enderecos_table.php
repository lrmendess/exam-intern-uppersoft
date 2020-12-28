<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnderecosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->primary("id"); // PK && Usuario_FK
            $table->unsignedBigInteger("id");
            
            $table->string("complemento")->nullable();
            $table->integer("numero");
            $table->string("logradouro");
            $table->string("bairro");
            $table->string("cidade");
            $table->char("uf", 2);
            $table->timestamps();

            $table->foreign("uf")->references("sigla")->on("estados");
            $table->foreign("id")->references("id")->on("usuarios")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enderecos');
    }
}
