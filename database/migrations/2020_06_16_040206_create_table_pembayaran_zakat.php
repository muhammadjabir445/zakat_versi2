<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePembayaranZakat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_zakat', function (Blueprint $table) {
            $table->id();
            $table->string('kode',40);
            $table->string('nama_muzaki',40);
            $table->boolean('status')->default(false);
            $table->string('bukti_pembayaran',50)->default(false);
            $table->integer('id_jenis');
            $table->integer('total_pembayaran')->nullable();
            $table->integer('total_beras')->nullable();
            $table->integer('id_approval')->nullable()->index();
            $table->softDeletes();
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
        Schema::dropIfExists('pembayaran_zakat');
    }
}
