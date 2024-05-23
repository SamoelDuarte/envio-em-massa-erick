<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2); // O segundo parâmetro é o número total de dígitos, e o terceiro parâmetro é o número de dígitos após o ponto decimal
            $table->timestamps();
        });

        // Inserir dados padrão
        DB::table('products')->insert([
            [
                'name' => 'P13 Liquido', 'price' => 100.00
            ],
            [
                'name' => 'P13 Vasilhame', 'price' => 200.00
            ],
            [
                'name' => 'P20 Liquido', 'price' => 370.00
            ],
            [
                'name' => 'P20 Vasilhame', 'price' => 500.50
            ],
            [
                'name' => 'P45 Liquido', 'price' => 400.00
            ],
            [
                'name' => 'P45 Vasilhame', 'price' => 600.00
            ],
        ]);
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
