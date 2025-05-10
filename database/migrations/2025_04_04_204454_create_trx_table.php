<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxTable extends Migration
{
    public function up()
    {
        Schema::create('trx', function (Blueprint $table) {
            $table->uuid('id_transaksi')->primary();
            $table->timestamps();
            $table->softDeletes(); // Membuat kolom deleted_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('trx');
    }
}