<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxDataTable extends Migration
{
    public function up()
    {
        Schema::create('trx_data', function (Blueprint $table) {
            $table->id('id_data'); // Auto Increment
            $table->uuid('id_transaksi');
            $table->string('nama');
            $table->integer('kelas');
            $table->string('tertarik_matana');
            $table->integer('biaya');
            $table->integer('fasilitas');
            $table->integer('prestasi');
            $table->integer('orang_tua');
            $table->integer('jarak');
            $table->integer('akreditasi');
            $table->timestamps();
            
            // Foreign key
            $table->foreign('id_transaksi')->references('id_transaksi')->on('trx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trx_data');
    }
}