<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeminjamanKaryawansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peminjaman_karyawans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('karyawan_id');
            $table->date('tgl_peminjaman');
            $table->integer('nominal');
            $table->string('alasan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peminjaman_karyawans');
    }
}
