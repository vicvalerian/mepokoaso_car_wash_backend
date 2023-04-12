<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiKedaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_kedais', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('karyawan_id');
            $table->string('no_penjualan');
            $table->integer('total_penjualan');
            $table->date('tgl_penjualan');
            $table->time('waktu_penjualan');
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
        Schema::dropIfExists('transaksi_kedais');
    }
}
