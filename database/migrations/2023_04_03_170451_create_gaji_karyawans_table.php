<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGajiKaryawansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gaji_karyawans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('karyawan_id');
            $table->string('bulan');
            $table->integer('tahun');
            $table->integer('total_gaji_kotor');
            $table->integer('total_utang');
            $table->integer('total_gaji_bersih');
            $table->string('status');
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
        Schema::dropIfExists('gaji_karyawans');
    }
}
