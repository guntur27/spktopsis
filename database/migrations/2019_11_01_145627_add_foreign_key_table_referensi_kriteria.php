<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyTableReferensiKriteria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referensi_kriteria', function (Blueprint $table) {
            $table->string('username');
            $table->string('id_kriteria', 20);

            $table->foreign('username')->references('username')->on('users');
            $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referensi_kriteria', function (Blueprint $table) {
            //
        });
    }
}
