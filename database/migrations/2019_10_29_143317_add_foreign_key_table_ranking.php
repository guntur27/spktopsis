<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyTableRanking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ranking', function (Blueprint $table) {
            // Foreign key
            $table->string('id_hasil', 20);
            $table->string('id_alternatif', 20);

            $table->foreign('id_hasil')->references('id_hasil')->on('hasil');
            $table->foreign('id_alternatif')->references('id_alternatif')->on('alternatif');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ranking', function (Blueprint $table) {
            //
        });
    }
}
