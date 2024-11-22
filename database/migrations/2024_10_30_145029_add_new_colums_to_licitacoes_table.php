<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('licitacoes', function (Blueprint $table) {
            $table->string('sei')->nullable();
            $table->string('sislog')->nullable();
            $table->string('modalidade')->nullable();
            $table->string('situacao')->nullable();
            $table->string('local')->nullable();
            $table->text('observacao')->nullable();
        });
    }

    public function down()
    {
        Schema::table('licitacoes', function (Blueprint $table) {
            $table->dropColumn(['sei', 'sislog', 'modalidade', 'status', 'local', 'observacao']);
        });
    }
};
