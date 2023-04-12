<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengeluaranKedaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengeluaran_kedais', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('menu_kedai_id')->nullable();
            $table->string('nama_barang');
            $table->date('tgl_pembelian');
            $table->integer('jumlah_barang');
            $table->integer('harga_pembelian');
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
        Schema::dropIfExists('pengeluaran_kedais');
    }
}
