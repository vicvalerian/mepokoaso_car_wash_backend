<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiPencuciansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_pencucians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('kendaraan_id');
            $table->bigInteger('karyawan_id');
            $table->bigInteger('mobil_pelanggan_id')->nullable();
            $table->string('no_pencucian');
            $table->string('no_polisi');
            $table->string('jenis_kendaraan');
            $table->integer('tarif_kendaraan');
            $table->date('tgl_pencucian');
            $table->time('waktu_pencucian');
            $table->string('status');
            $table->integer('total_pembayaran')->default(0);
            $table->boolean('is_free')->default(false);
            $table->integer('keuntungan')->default(0);
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
        Schema::dropIfExists('transaksi_pencucians');
    }
}
