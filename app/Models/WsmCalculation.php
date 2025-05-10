<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WsmCalculation extends Model
{
    use HasFactory;

    protected $table = 'wsm_calculation';
    protected $primaryKey = 'id_wsm_calculation';

    protected $fillable = [
        'id_transaksi',
        'alternatif',
        'nilai_kelas',
        'nilai_tertarik_matana',
        'nilai_biaya',
        'nilai_fasilitas',
        'nilai_prestasi',
        'nilai_orang_tua',
        'nilai_jarak',
        'nilai_akreditasi',
        'total_nilai',
        'rank'
    ];

    public function trx()
    {
        return $this->belongsTo(Trx::class, 'id_transaksi', 'id_transaksi');
    }
}