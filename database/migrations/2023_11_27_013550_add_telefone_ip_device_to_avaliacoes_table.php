<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTelefoneIpDeviceToAvaliacoesTable extends Migration
{
    public function up()
    {
        Schema::table('avaliacoes', function (Blueprint $table) {
            $table->string('telefone')->nullable();
            $table->string('ip_device')->nullable();
        });
    }

    public function down()
    {
        Schema::table('avaliacoes', function (Blueprint $table) {
            $table->dropColumn('telefone');
            $table->dropColumn('ip_device');
        });
    }
}
