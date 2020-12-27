<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // id := codigo_ibge
        Schema::create('estados', function (Blueprint $table) {
            $table->primary("codigo_ibge");
            $table->unsignedTinyInteger("codigo_ibge");
            $table->string("nome", 31)->unique();
            $table->char("sigla", 2)->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estados');
    }
}
