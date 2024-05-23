<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_list', function (Blueprint $table) {
            $table->id(); // Cria uma coluna 'id' auto-incrementada
            $table->string('phone'); // Cria uma coluna 'phone' do tipo string
            $table->unsignedBigInteger('contact_id'); // Cria uma coluna 'contact_id' do tipo unsigned big integer
            $table->timestamps(); // Cria as colunas 'created_at' e 'updated_at'

            // Define a chave estrangeira
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_list');
    }
};
