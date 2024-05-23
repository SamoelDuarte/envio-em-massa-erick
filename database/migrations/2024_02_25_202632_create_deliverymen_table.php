<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliverymenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliverymens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('deleted')->default(false); // Coluna 'deleted'
            $table->string('image')->nullable(); // Coluna 'image'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliverymens');
    }
}