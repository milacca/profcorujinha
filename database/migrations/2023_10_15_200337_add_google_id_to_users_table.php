<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogleIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Adiciona o campo google_id à tabela users
            // O método 'nullable' é usado porque nem todos os usuários podem ter uma conta Google.
            $table->string('google_id')->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // No método down(), removemos o campo, para que possamos reverter a migração, se necessário.
            $table->dropColumn('google_id');
        });
    }
}
