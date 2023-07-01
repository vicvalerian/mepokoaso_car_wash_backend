<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTransaksiDropColumnNomor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi_pencucians', function (Blueprint $table) {
            $table->dropColumn('no_pencucian');
        });

        Schema::table('transaksi_kedais', function (Blueprint $table) {
            $table->dropColumn('no_penjualan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
