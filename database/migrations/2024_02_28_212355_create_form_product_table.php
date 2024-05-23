<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('form_product', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('form_id');
        $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
        $table->unsignedBigInteger('product_id');
        $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        $table->decimal('value', 10, 2);
        $table->decimal('discount', 10, 2)->nullable()->default(0);
        $table->integer('quantity');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('form_product');
}

};
