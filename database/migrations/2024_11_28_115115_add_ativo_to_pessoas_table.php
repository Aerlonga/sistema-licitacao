<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAtivoToPessoasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->boolean('ativo')->default(1)->after('nome');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
    }
}
