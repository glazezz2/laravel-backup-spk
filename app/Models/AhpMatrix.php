<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AhpMatrix extends Model
{
    use HasFactory;

    protected $table = 'ahp_matrix';
    protected $primaryKey = 'id_ahp_matrix';
    protected $fillable = [
        'id_transaksi',
        'nilai',
    ];

    // Fix: Ensuring proper JSON handling for the nilai field
    protected $casts = [
        //'nilai' => 'array',
    ];

    // Override the setNilaiAttribute method to ensure proper JSON handling
    public function setNilaiAttribute($value)
    {
        $this->attributes['nilai'] = is_array($value) ? json_encode($value) : $value;
    }

    public function transaksi()
    {
        return $this->belongsTo(Trx::class, 'id_transaksi', 'id_transaksi');
    }
}