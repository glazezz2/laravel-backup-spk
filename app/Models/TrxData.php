<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxData extends Model
{
    use HasFactory;
    
    protected $table = 'trx_data';
    protected $primaryKey = 'id_data';
    
    protected $fillable = [
        'id_transaksi',
        'nama',
        'kelas',
        'tertarik_matana',
        'biaya',
        'fasilitas',
        'prestasi',
        'orang_tua',
        'jarak',
        'akreditasi'
    ];
    
    // Relasi ke Trx
    public function trx()
    {
        return $this->belongsTo(Trx::class, 'id_transaksi', 'id_transaksi');
    }
}