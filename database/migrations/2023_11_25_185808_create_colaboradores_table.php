<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  // Em um arquivo de migration
public function up()
{
    Schema::create('colaboradores', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('imagem')->nullable(); // Adicione 'nullable()' se a imagem for opcional
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('colaboradores');
}

};
