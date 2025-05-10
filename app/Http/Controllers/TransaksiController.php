<?php

namespace App\Http\Controllers;

use App\Models\Trx;
use App\Models\TrxData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // Contoh 1: Join menggunakan Eloquent Relationship
    public function getDataWithEloquentTrxData()
    {
        // Ambil data transaksi beserta datanya
        $transaksi = Trx::with('trxData')->get();
        
        return response()->json($transaksi);
    }

    public function getDataWithEloquentAhpMatrix()
    {
        // Ambil data transaksi beserta datanya
        $transaksi = Trx::with('ahpMatrix')->get();
        
        return response()->json($transaksi);
    }

    public function getDataWithEloquentAhpCalculation()
    {
        // Ambil data transaksi beserta datanya
        $transaksi = Trx::with('ahpCalculation')->get();
        
        return response()->json($transaksi);
    }

    public function getDataWithEloquentWsmCalculation()
    {
        // Ambil data transaksi beserta datanya
        $transaksi = Trx::with('wsmCalculation')->get();
        
        return response()->json($transaksi);
    }
}