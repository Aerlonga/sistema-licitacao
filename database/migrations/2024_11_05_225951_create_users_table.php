<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Cria um campo 'id' como chave primária
            $table->string('name'); // Nome do usuário
            $table->string('email')->unique(); // Email único
            $table->timestamp('email_verified_at')->nullable(); // Campo para verificação de email
            $table->string('password'); // Senha do usuário
            $table->rememberToken(); // Token para o 'remember me'
            $table->timestamps(); // Cria os campos 'created_at' e 'updated_at'
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
