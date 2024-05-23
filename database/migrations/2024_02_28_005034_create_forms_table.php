<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deliveryman_id');
            $table->foreignId('user_id');
            $table->date('date');
            $table->string('maq');
            $table->integer('disk')->nullable();
            $table->integer('auto')->nullable();
            $table->decimal('total', 10, 2);
            $table->timestamps();

            // Adicione a chave estrangeira para o motorista
            $table->foreign('deliveryman_id')->references('id')->on('deliverymens')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forms');
    }
}