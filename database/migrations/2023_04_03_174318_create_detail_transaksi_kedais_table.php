<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailTransaksiKedaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_transaksi_kedais', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('transaksi_kedai_id');
            $table->bigInteger('menu_kedai_id');
            $table->integer('kuantitas');
            $table->integer('sub_total');
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
        Schema::dropIfExists('detail_transaksi_kedais');
    }
}
