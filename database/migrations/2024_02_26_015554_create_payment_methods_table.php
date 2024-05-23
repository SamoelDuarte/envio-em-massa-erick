<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Inserir dados padrão
        DB::table('payment_methods')->insert([
            ['name' => 'Pix'],
            ['name' => 'Cartão de Crédito'],
            ['name' => 'Cartão de Débito'],
            ['name' => 'Dinheiro'],
            ['name' => 'Pendente'],
            ['name' => 'Vale'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
