<?php

namespace App\Http\Controllers;

use App\Models\TrxData;
use App\Models\AhpCalculation;
use App\Models\WsmCalculation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function ranking(Request $request, $id_transaksi = null)
    {
        // If no ID is provided in the URL, get the latest transaction ID
        if (!$id_transaksi) {
            $id_transaksi = DB::table('trx')
                ->join('ahp_calculation', 'trx.id_transaksi', '=', 'ahp_calculation.id_transaksi')
                ->orderBy('trx.created_at', 'desc')
                ->value('trx.id_transaksi');
        }

        if (!$id_transaksi) {
            return view('ranking', ['rankings' => []]);
        }

        // Get the AHP weights from the specified transaction
        $ahpWeights = AhpCalculation::where('id_transaksi', $id_transaksi)->first();
        
        if (!$ahpWeights) {
            return view('ranking', ['rankings' => []]);
        }

        // Get all student data related to this transaction ID
        $studentsData = TrxData::where('id_transaksi', $id_transaksi)->get();

        // Calculate WSM for each student
        $wsmData = [];
        foreach ($studentsData as $student) {
            // Calculate individual criterion values
            $nilai_kelas = ($student->kelas == 12) ? $ahpWeights->bobot_kelas : 0;
            $nilai_tertarik_matana = ($student->tertarik_matana == "Ya") ? $ahpWeights->bobot_tertarik_matana : 0;
            $nilai_biaya = ($student->biaya == 1) ? $ahpWeights->bobot_biaya : 0;
            $nilai_fasilitas = ($student->fasilitas == 1) ? $ahpWeights->bobot_fasilitas : 0;
            $nilai_prestasi = ($student->prestasi == 1) ? $ahpWeights->bobot_prestasi : 0;
            $nilai_orang_tua = ($student->orang_tua == 1) ? $ahpWeights->bobot_orang_tua : 0;
            $nilai_jarak = ($student->jarak == 1) ? $ahpWeights->bobot_jarak : 0;
            $nilai_akreditasi = ($student->akreditasi == 1) ? $ahpWeights->bobot_akreditasi : 0;
            
            // Calculate total value
            $total_nilai = $nilai_kelas + $nilai_tertarik_matana + $nilai_biaya + $nilai_fasilitas +
                          $nilai_prestasi + $nilai_orang_tua + $nilai_jarak + $nilai_akreditasi;
            
            // Store WSM calculation for this student
            $wsmData[] = [
                'id_transaksi' => $id_transaksi,
                'student_id' => $student->id_data,
                'nama' => $student->nama,
                'kelas' => $student->kelas,
                'tertarik_matana' => $student->tertarik_matana,
                'biaya' => $student->biaya,
                'fasilitas' => $student->fasilitas,
                'prestasi' => $student->prestasi,
                'orang_tua' => $student->orang_tua,
                'jarak' => $student->jarak,
                'akreditasi' => $student->akreditasi,
                'nilai_kelas' => $nilai_kelas,
                'nilai_tertarik_matana' => $nilai_tertarik_matana,
                'nilai_biaya' => $nilai_biaya,
                'nilai_fasilitas' => $nilai_fasilitas,
                'nilai_prestasi' => $nilai_prestasi,
                'nilai_orang_tua' => $nilai_orang_tua,
                'nilai_jarak' => $nilai_jarak,
                'nilai_akreditasi' => $nilai_akreditasi,
                'total_nilai' => $total_nilai
            ];
        }

        // Sort by total_nilai in descending order
        usort($wsmData, function($a, $b) {
            return $b['total_nilai'] <=> $a['total_nilai'];
        });

        // Assign ranks (same rank for same total_nilai)
        $currentRank = 1;
        $previousValue = null;
        foreach ($wsmData as $key => $data) {
            if ($key > 0 && $data['total_nilai'] != $previousValue) {
                $currentRank = $key + 1;
            }
            $wsmData[$key]['rank'] = $currentRank;
            $previousValue = $data['total_nilai'];
        }

        // Save all records to the wsm_calculation table
        // First, delete existing records for this transaction to avoid duplicates
        WsmCalculation::where('id_transaksi', $id_transaksi)->delete();
        
        // Now save all the records
        foreach ($wsmData as $data) {
            WsmCalculation::create([
                'id_transaksi' => $data['id_transaksi'],
                'alternatif' => $data['nama'], // Save the student name as 'alternatif'
                'nilai_kelas' => $data['nilai_kelas'],
                'nilai_tertarik_matana' => $data['nilai_tertarik_matana'],
                'nilai_biaya' => $data['nilai_biaya'],
                'nilai_fasilitas' => $data['nilai_fasilitas'],
                'nilai_prestasi' => $data['nilai_prestasi'],
                'nilai_orang_tua' => $data['nilai_orang_tua'],
                'nilai_jarak' => $data['nilai_jarak'],
                'nilai_akreditasi' => $data['nilai_akreditasi'],
                'total_nilai' => $data['total_nilai'],
                'rank' => $data['rank']
            ]);
        }

        // Convert to object for blade template compatibility
        $rankings = json_decode(json_encode($wsmData));
        
        // Pass the transaction ID to the view
        return view('ranking', compact('rankings', 'id_transaksi'));
    }
    
    // Add a method to list all available transactions
    public function transactionList()
    {
        $transactions = DB::table('trx')
            ->join('ahp_calculation', 'trx.id_transaksi', '=', 'ahp_calculation.id_transaksi')
            ->select('trx.id_transaksi', 'trx.created_at')
            ->orderBy('trx.created_at', 'desc')
            ->get();
            
        return view('transaction-list', compact('transactions'));
    }
}