<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ahp_matrix', function (Blueprint $table) {
            $table->id('id_ahp_matrix');
            $table->uuid('id_transaksi');
            $table->json('nilai');
            $table->timestamps();

             // Foreign key
             $table->foreign('id_transaksi')->references('id_transaksi')->on('trx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahp_matrix');
    }
};
