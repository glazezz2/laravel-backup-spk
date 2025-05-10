<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Trx extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'trx';
    protected $primaryKey = 'id_transaksi';
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id_transaksi'
    ];
    
    // Generate UUID saat membuat model baru
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->id_transaksi = Str::uuid()->toString();
        });
    }
    
    // Relasi ke TrxData
    public function trxData()
    {
        return $this->hasMany(TrxData::class, 'id_transaksi', 'id_transaksi');
    }

    // Relasi ke AHP Matrix
    public function ahpMatrix()
    {
        return $this->hasOne(AhpMatrix::class, 'id_transaksi', 'id_transaksi');
    }

    // Relasi ke AHP Calculation
    public function ahpCalculation()
    {
        return $this->hasMany(AhpCalculation::class, 'id_transaksi', 'id_transaksi');
    }

    // Relasi ke WSM Calculation
    public function wsmCalculation()
    {
        return $this->hasMany(WsmCalculation::class, 'id_transaksi', 'id_transaksi');
    }
}