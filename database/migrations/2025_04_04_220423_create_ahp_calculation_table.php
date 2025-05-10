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
        Schema::create('ahp_calculation', function (Blueprint $table) {
            $table->id('id_ahp_calculation');
            $table->uuid('id_transaksi');
            $table->decimal('bobot_kelas', 10, 8);
            $table->decimal('bobot_tertarik_matana', 10, 8);
            $table->decimal('bobot_biaya', 10, 8);
            $table->decimal('bobot_fasilitas', 10, 8);
            $table->decimal('bobot_prestasi', 10, 8);
            $table->decimal('bobot_orang_tua', 10, 8);
            $table->decimal('bobot_jarak', 10, 8);
            $table->decimal('bobot_akreditasi', 10, 8);
            $table->decimal('lambda_max', 10, 8);
            $table->decimal('consistency_index', 10, 8);
            $table->decimal('consistency_ratio', 10, 8);
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
        Schema::dropIfExists('ahp_calculation');
    }
};