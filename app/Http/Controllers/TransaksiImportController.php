<?php

namespace App\Http\Controllers;

use App\Models\Trx;
use App\Models\TrxData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransaksiImportController extends Controller
{
    /**
     * Menampilkan halaman upload CSV
     */
    public function index()
    {
        // Ambil transaksi dengan data terkait, dikelompokkan per ID transaksi
        $transaksi = Trx::with('trxData')
                      ->orderBy('created_at', 'desc')
                      ->take(10)
                      ->get();
        
        return view('upload', compact('transaksi'));
    }
    
    /**
     * Memproses file CSV yang diupload
     * Semua baris data dalam satu file CSV akan memiliki ID transaksi yang sama
     */
    /**
 * Memproses file CSV yang diupload
 * Semua baris data dalam satu file CSV akan memiliki ID transaksi yang sama
 */
public function import(Request $request)
{
    // Validasi file
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt|max:2048',
    ]);
    
    // Buka file
    $file = $request->file('csv_file');
    $path = $file->getRealPath();
    
    // Baca file
    $csvData = array_map('str_getcsv', file($path));
    
    // Ambil header
    $header = array_map('strtolower', $csvData[0]);
    
    // Validasi header
    $requiredColumns = ['nama', 'kelas', 'tertarik_matana', 'biaya', 'fasilitas', 'prestasi', 'orang_tua', 'jarak', 'akreditasi'];
    $missingColumns = array_diff($requiredColumns, $header);
    
    if (count($missingColumns) > 0) {
        return redirect()->back()->withErrors(['csv_file' => 'File CSV tidak memiliki kolom yang diperlukan: ' . implode(', ', $missingColumns)]);
    }
    
    // Hapus header dari data
    array_shift($csvData);
    
    // Periksa apakah ada data yang valid
    if (empty($csvData)) {
        return redirect()->back()->withErrors(['csv_file' => 'File CSV tidak memiliki data yang valid']);
    }
    
    DB::beginTransaction();
    
    try {
        // Buat satu transaksi untuk semua data dalam CSV
        $transaksi = new Trx();
        $transaksi->save();
        
        // ID transaksi yang akan digunakan untuk semua data
        $idTransaksi = $transaksi->id_transaksi;
        
        // Loop melalui setiap baris
        foreach ($csvData as $row) {
            if (count($row) !== count($header)) {
                continue; // Lewati baris yang tidak memiliki jumlah kolom yang benar
            }
            
            // Combine header dengan nilai untuk membuat array asosiatif
            $data = array_combine($header, $row);
            
            // Map kolom ke struktur database trx_data
            $trxData = new TrxData([
                'id_transaksi' => $idTransaksi, // Gunakan ID transaksi yang sama untuk semua data
                'nama' => $data['nama'],
                'kelas' => (int)$data['kelas'],
                'tertarik_matana' => $data['tertarik_matana'],
                'biaya' => (int)$data['biaya'],
                'fasilitas' => (int)$data['fasilitas'],
                'prestasi' => (int)$data['prestasi'],
                'orang_tua' => (int)$data['orang_tua'],
                'jarak' => (int)$data['jarak'],
                'akreditasi' => $data['akreditasi'],
            ]);
            
            $trxData->save();
        }
        
        DB::commit();
        
        $jumlahData = count(TrxData::where('id_transaksi', $idTransaksi)->get());
        
        // Redirect ke halaman detail transaksi yang baru dibuat
        return redirect()->route('detail', $idTransaksi)->with('success', "Data berhasil diimport! {$jumlahData} data telah ditambahkan dengan ID Transaksi: {$idTransaksi}");
        
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(['csv_file' => 'Terjadi kesalahan saat import data: ' . $e->getMessage()]);
    }
}
    
    /**
     * Export data ke CSV
     */
    public function export()
    {
        $transaksi = Trx::with('trxData')->get();
        $fileName = 'transaksi_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];
        
        $columns = ['ID Transaksi', 'Tanggal', 'Nama', 'Kelas', 'Mata Pelajaran', 'Biaya', 
                    'Fasilitas', 'Prestasi', 'Orang Tua', 'Jarak', 'Akreditasi'];
        
        $callback = function() use($transaksi, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($transaksi as $trx) {
                foreach ($trx->trxData as $data) {
                    $row = [
                        $trx->id_transaksi,
                        $trx->created_at->format('Y-m-d H:i:s'),
                        $data->nama,
                        $data->kelas,
                        $data->tertarik_matana,
                        $data->biaya,
                        $data->fasilitas,
                        $data->prestasi,
                        $data->orang_tua,
                        $data->jarak,
                        $data->akreditasi
                    ];
                    
                    fputcsv($file, $row);
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Menampilkan detail transaksi
     */
    public function detail($id)
    {
        $transaksi = Trx::with('trxData')->findOrFail($id);
        return view('detail', compact('transaksi'));
    }
    
    /**
     * Menampilkan form edit transaksi
     */
    public function edit($id)
    {
        $transaksi = Trx::with('trxData')->findOrFail($id);
        return view('transaksi.edit', compact('transaksi'));
    }
    
    /**
     * Update data transaksi
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string',
            'kelas' => 'required|integer',
            'tertarik_matana' => 'required|string',
            'biaya' => 'required|integer',
            'fasilitas' => 'required|integer',
            'prestasi' => 'required|integer',
            'orang_tua' => 'required|integer',
            'jarak' => 'required|integer',
            'akreditasi' => 'required|integer',
        ]);
        
        $transaksi = Trx::findOrFail($id);
        $trxData = TrxData::where('id_transaksi', $id)->first();
        
        if ($trxData) {
            $trxData->update([
                'nama' => $request->nama,
                'kelas' => $request->kelas,
                'tertarik_matana' => $request->tertarik_matana,
                'biaya' => $request->biaya,
                'fasilitas' => $request->fasilitas,
                'prestasi' => $request->prestasi,
                'orang_tua' => $request->orang_tua,
                'jarak' => $request->jarak,
                'akreditasi' => $request->akreditasi,
            ]);
        }
        
        return redirect()->route('transaksi.index')->with('success', 'Data berhasil diperbarui!');
    }
    
    /**
     * Hapus transaksi (soft delete)
     */
    public function delete($id)
    {
        $transaksi = Trx::findOrFail($id);
        $transaksi->delete(); // Soft delete
        
        return redirect()->route('transaksi.index')->with('success', 'Data berhasil dihapus!');
    }
}