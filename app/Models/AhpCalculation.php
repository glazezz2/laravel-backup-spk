<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AhpCalculation extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model
     *
     * @var string
     */
    protected $table = 'ahp_calculation';

    /**
     * Primary key dari tabel
     *
     * @var string
     */
    protected $primaryKey = 'id_ahp_calculation';

    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array
     */
    protected $fillable = [
        'id_transaksi',
        'bobot_kelas',
        'bobot_tertarik_matana',
        'bobot_biaya',
        'bobot_fasilitas',
        'bobot_prestasi',
        'bobot_orang_tua',
        'bobot_jarak',
        'bobot_akreditasi',
        'lambda_max',
        'consistency_index',
        'consistency_ratio'
    ];

    /**
     * Set precision untuk atribut decimal
     */
    protected $casts = [
        'bobot_kelas' => 'decimal:8',
        'bobot_tertarik_matana' => 'decimal:8',
        'bobot_biaya' => 'decimal:8',
        'bobot_fasilitas' => 'decimal:8',
        'bobot_prestasi' => 'decimal:8',
        'bobot_orang_tua' => 'decimal:8',
        'bobot_jarak' => 'decimal:8',
        'bobot_akreditasi' => 'decimal:8',
        'lambda_max' => 'decimal:8',
        'consistency_index' => 'decimal:8',
        'consistency_ratio' => 'decimal:8'
    ];

    /**
     * Relasi dengan model Trx
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaksi()
    {
        return $this->belongsTo(Trx::class, 'id_transaksi', 'id_transaksi');
    }
}