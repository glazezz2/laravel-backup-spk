<?php

namespace App\Http\Controllers;

use App\Models\Trx;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Menampilkan halaman history transaksi (read-only)
     */
    public function index()
    {
        // Ambil transaksi dengan data terkait, dikelompokkan per ID transaksi
        $transaksi = Trx::with('trxData')
                      ->orderBy('created_at', 'desc')
                      ->get();
        
        return view('history', compact('transaksi'));
    }

    /**
     * Export data ke CSV (menggunakan fungsi yang sama dengan TransaksiImportController)
     */
    public function export()
    {
        // Ini hanya akan memanggil fungsi export dari TransaksiImportController
        // Anda bisa membuat route untuk ini yang mengarah ke TransaksiImportController@export
        $controller = new TransaksiImportController();
        return $controller->export();
    }

    /**
     * Menampilkan detail transaksi (read-only)
     */
    public function detail($id)
    {
        $transaksi = Trx::with('trxData')->findOrFail($id);
        return view('detail', compact('transaksi'));
    }
    
    /**
     * Hapus transaksi (soft delete)
     */
    public function delete($id)
    {
        $transaksi = Trx::findOrFail($id);
        $transaksi->delete(); // Soft delete
        
        return redirect()->route('history.index')->with('success', 'Data berhasil dihapus!');
    }
}