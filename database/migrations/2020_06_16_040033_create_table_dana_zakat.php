<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDanaZakat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dana_zakat', function (Blueprint $table) {
            $table->id();
            $table->float('total_uang',8,2)->nullable();
            $table->float('total_beras',5,2)->nullable();
            $table->boolean('status')->default(false);
            $table->integer('id_penyalur');
            $table->text('deskripsi')->nullable();
            $table->text('deskripsi_penyalur')->nullable();
            $table->string('file_bukti',100);
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
        Schema::dropIfExists('dana_zakat');
    }
}
