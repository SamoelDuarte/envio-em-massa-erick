<?php

use App\Http\Controllers\Utils;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('picture', 255)->nullable()->default(null);
            $table->string('email', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('salt', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        $defaultUser = new User();
        $defaultUser->first_name = 'samoel';
        $defaultUser->email = 'admin@admin.com.br';
        $defaultUser->salt = Utils::createPasswordSalt();
        $defaultUser->password = Utils::createPasswordHash('password', $defaultUser->salt);
        $defaultUser->role = 'admin';
        $defaultUser->save();

        $defaultUser = new User();
        $defaultUser->first_name = 'samoel';
        $defaultUser->email = 'user@user.com.br';
        $defaultUser->salt = Utils::createPasswordSalt();
        $defaultUser->password = Utils::createPasswordHash('password', $defaultUser->salt);
        $defaultUser->role = 'user';
        $defaultUser->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
