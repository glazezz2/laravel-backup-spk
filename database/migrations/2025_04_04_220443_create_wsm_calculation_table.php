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
        Schema::create('wsm_calculation', function (Blueprint $table) {
            $table->id('id_wsm_calculation');
            $table->uuid('id_transaksi');
            $table->string('alternatif');
            $table->decimal('nilai_kelas', 10, 8)->default(0);
            $table->decimal('nilai_tertarik_matana', 10, 8)->default(0);
            $table->decimal('nilai_biaya', 10, 8)->default(0);
            $table->decimal('nilai_fasilitas', 10, 8)->default(0);
            $table->decimal('nilai_prestasi', 10, 8)->default(0);
            $table->decimal('nilai_orang_tua', 10, 8)->default(0);
            $table->decimal('nilai_jarak', 10, 8)->default(0);
            $table->decimal('nilai_akreditasi', 10, 8)->default(0);
            $table->decimal('total_nilai', 10, 8);
            $table->integer('rank')->nullable();
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
        Schema::dropIfExists('wsm_calculation');
    }
};