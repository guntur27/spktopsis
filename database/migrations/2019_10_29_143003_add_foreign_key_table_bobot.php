<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyTableBobot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bobot', function (Blueprint $table) {
            // Foreign key
            $table->string('id_alternatif', 20);
            $table->string('id_kriteria', 20);

            $table->foreign('id_alternatif')->references('id_alternatif')->on('alternatif');
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
        Schema::table('bobot', function (Blueprint $table) {
            //
        });
    }
}
