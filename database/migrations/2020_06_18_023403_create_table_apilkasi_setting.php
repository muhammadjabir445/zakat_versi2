<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableApilkasiSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_aplikasi', function (Blueprint $table) {
            $table->id();
            $table->float('harga_beras',8,2);
            $table->float('harga_emas',8,2);
            $table->float('harga_perak',8,2);
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
        Schema::dropIfExists('setting_aplikasi');
    }
}
