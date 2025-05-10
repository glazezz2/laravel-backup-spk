<?php

namespace App\Http\Controllers;

use App\Models\AhpMatrix;
use App\Models\AhpCalculation;
use App\Models\Trx;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MatrixController extends Controller
{
    /**
     * Menampilkan halaman input matrix perbandingan
     * 
     * @param Request $request
     * @param string|null $idTransaksi
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $idTransaksi = null)
    {
        // Gunakan ID transaksi dari parameter atau dari session
        $idTransaksi = $idTransaksi ?? Session::get('active_transaction');
        
        if (!$idTransaksi) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Tidak ada transaksi aktif. Silakan buat transaksi terlebih dahulu.');
        }

        // Cek apakah transaksi ada
        $transaksi = Trx::where('id_transaksi', $idTransaksi)->first();
        if (!$transaksi) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        // Cek apakah sudah ada data matrix untuk transaksi ini
        $ahpMatrix = AhpMatrix::where('id_transaksi', $idTransaksi)->first();
        $matrixData = null;
        
        if ($ahpMatrix) {
            // Periksa apakah nilai sudah dalam format array atau masih JSON string
            if (is_string($ahpMatrix->nilai)) {
                $matrixData = json_decode($ahpMatrix->nilai, true);
            } else {
                $matrixData = $ahpMatrix->nilai;
            }
        }
        
        // Cek apakah wsm_calculation sudah terisi untuk transaksi ini
        $wsmCalculationExists = DB::table('wsm_calculation')->where('id_transaksi', $idTransaksi)->exists();
        $viewOnly = $wsmCalculationExists; // Set mode view-only jika wsm_calculation sudah ada
        
        return view('matrix', compact('idTransaksi', 'matrixData', 'viewOnly'));
    }
    
    /**
 * Memproses input matrix dari form
 * 
 * @param Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function process(Request $request)
{
    // Validasi input
    $request->validate([
        'id_transaksi' => 'required|uuid|exists:trx,id_transaksi',
        'matrix' => 'required|array',
    ]);

    // Ambil data matrix dari form
    $matrix = $request->input('matrix');
    $idTransaksi = $request->input('id_transaksi');

    // Simpan data matrix dalam session
    session(['matrix_data' => $matrix]);

    // Definisikan kriteria AHP
    $criteriaLabels = [
        'kelas',
        'tertarik berkuliah di matana',
        'biaya',
        'fasilitas',
        'prestasi',
        'orang tua',
        'jarak',
        'akreditasi'
    ];
    $criteriaCount = count($criteriaLabels);

    // Fungsi untuk membulatkan nilai khusus
    function roundSpecialValues($value) {
        $value = (float)$value;
        
        // Untuk nilai-nilai 3, 6, 7, 9 dan inverse-nya, lakukan pembulatan
        // Untuk 3 dan 1/3
        if (abs($value - 3) < 0.1 || abs($value - 0.333) < 0.01) {
            return ($value > 1) ? 3.0 : (1/3);
        }
        // Untuk 6 dan 1/6
        else if (abs($value - 6) < 0.1 || abs($value - 0.167) < 0.01) {
            return ($value > 1) ? 6.0 : (1/6);
        }
        // Untuk 7 dan 1/7
        else if (abs($value - 7) < 0.1 || abs($value - 0.143) < 0.01) {
            return ($value > 1) ? 7.0 : (1/7);
        }
        // Untuk 9 dan 1/9
        else if (abs($value - 9) < 0.1 || abs($value - 0.111) < 0.01) {
            return ($value > 1) ? 9.0 : (1/9);
        }
        
        // Kembalikan nilai asli jika tidak perlu dibulatkan
        return $value;
    }

    // Langkah 1: Inisialisasi matriks dan pastikan semua nilai dikonversi ke float
    $processedMatrix = [];
    $matrixRounded = []; // Tambahkan matriks untuk nilai yang dibulatkan
    
    for ($i = 0; $i < $criteriaCount; $i++) {
        $processedMatrix[$i] = [];
        $matrixRounded[$i] = [];
        
        for ($j = 0; $j < $criteriaCount; $j++) {
            // Diagonal matrix selalu 1
            if ($i == $j) {
                $processedMatrix[$i][$j] = 1.0;
                $matrixRounded[$i][$j] = 1.0;
                continue;
            }
            
            // Untuk nilai dari form
            if (isset($matrix[$i][$j]) && is_numeric($matrix[$i][$j])) {
                // Simpan nilai asli dengan presisi tinggi
                $processedMatrix[$i][$j] = (float) number_format((float)$matrix[$i][$j], 6, '.', '');
                
                // Bulatkan nilai khusus untuk penyimpanan
                $matrixRounded[$i][$j] = roundSpecialValues($matrix[$i][$j]);
            } else {
                $processedMatrix[$i][$j] = 0.0;
                $matrixRounded[$i][$j] = 0.0;
            }
        }
    }
    
    // Langkah 2: Hitung nilai inverse dengan benar
    for ($i = 0; $i < $criteriaCount; $i++) {
        for ($j = 0; $j < $criteriaCount; $j++) {
            // Jika i < j dan nilai j,i ada dan tidak nol, hitung inverse
            if ($i < $j && isset($processedMatrix[$j][$i]) && $processedMatrix[$j][$i] > 0) {
                $inverseValue = 1.0 / (float)$processedMatrix[$j][$i];
                // Gunakan presisi yang cukup untuk menghindari pembulatan yang berlebihan
                $processedMatrix[$i][$j] = (float) number_format($inverseValue, 6, '.', '');
                
                // Bulatkan nilai inverse untuk penyimpanan
                $matrixRounded[$i][$j] = roundSpecialValues($processedMatrix[$i][$j]);
            }
        }
    }

    try {
        // Mulai transaksi database
        DB::beginTransaction();
        
        // Tambahkan matrix_rounded sebagai input tersembunyi pada form
        $request->merge(['matrix_rounded' => $matrixRounded]);
        
        // PERUBAHAN PENTING: Simpan matrixRounded ke database
        $matrixString = json_encode($matrixRounded, 
            JSON_NUMERIC_CHECK | 
            JSON_PRESERVE_ZERO_FRACTION | 
            JSON_UNESCAPED_UNICODE
        );
        
        // Periksa apakah JSON encoding berhasil
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("JSON encoding error: " . json_last_error_msg());
        }
        
        // Gunakan nilai string JSON yang sudah dibuat
        AhpMatrix::updateOrCreate(
            ['id_transaksi' => $idTransaksi],
            ['nilai' => $matrixString]
        );
        
        // Hitung hasil AHP menggunakan nilai yang sudah dibulatkan
        $result = $this->calculateAHP($matrixRounded, $criteriaLabels);
        
        // Simpan hasil perhitungan ke tabel ahp_calculation
        $ahpCalculation = [
            'id_transaksi' => $idTransaksi,
            'bobot_kelas' => $result['eigenVector'][0] * 100,
            'bobot_tertarik_matana' => $result['eigenVector'][1] * 100,
            'bobot_biaya' => $result['eigenVector'][2] * 100,
            'bobot_fasilitas' => $result['eigenVector'][3] * 100,
            'bobot_prestasi' => $result['eigenVector'][4] * 100,
            'bobot_orang_tua' => $result['eigenVector'][5] * 100,
            'bobot_jarak' => $result['eigenVector'][6] * 100,
            'bobot_akreditasi' => $result['eigenVector'][7] * 100,
            'lambda_max' => $result['lambdaMax'],
            'consistency_index' => $result['CI'],
            'consistency_ratio' => $result['CR']
        ];
        
        AhpCalculation::updateOrCreate(
            ['id_transaksi' => $idTransaksi],
            $ahpCalculation
        );
        
        DB::commit();
        
        // Redirect ke halaman hasil
        return redirect()->route('matrix.result', ['id_transaksi' => $idTransaksi])
            ->with('success', 'Data matrix berhasil disimpan!');
            
    } catch (\Exception $e) {
        // Rollback transaksi jika terjadi error
        DB::rollBack();
        
        return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
    }
}
    
    /**
     * Menampilkan hasil perhitungan AHP
     * 
     * @param string $idTransaksi
     * @return \Illuminate\View\View
     */
    public function result($idTransaksi)
    {
        // Ambil data matrix dari database
        $ahpMatrix = AhpMatrix::where('id_transaksi', $idTransaksi)->first();
        
        if (!$ahpMatrix) {
            return redirect()->route('matrix.index')
                ->with('error', 'Data matrix tidak ditemukan.');
        }
        
        // Ambil hasil perhitungan dari tabel ahp_calculation
        $ahpCalculation = AhpCalculation::where('id_transaksi', $idTransaksi)->first();
        // Cek apakah wsm_calculation sudah terisi untuk transaksi ini
        $wsmCalculationExists = DB::table('wsm_calculation')->where('id_transaksi', $idTransaksi)->exists();
        $viewOnly = $wsmCalculationExists; // Set mode view-only jika wsm_calculation sudah ada
        
        if (!$ahpCalculation) {
            return redirect()->route('matrix.index')
                ->with('error', 'Data hasil perhitungan tidak ditemukan.');
        }
        
        // Definisikan kriteria
        $criteriaLabels = [
            'kelas',
            'tertarik berkuliah di matana',
            'biaya',
            'fasilitas',
            'prestasi',
            'orang tua',
            'jarak',
            'akreditasi'
        ];
        
        // Ambil nilai matrix dan decode JSON jika dalam format string
        $matrix = $ahpMatrix->nilai;
        if (is_string($matrix)) {
            $matrix = json_decode($matrix, true);
        }
        
        // Bangun eigenVector dari data di database
        $eigenVector = [
            $ahpCalculation->bobot_kelas / 100,
            $ahpCalculation->bobot_tertarik_matana / 100,
            $ahpCalculation->bobot_biaya / 100,
            $ahpCalculation->bobot_fasilitas / 100,
            $ahpCalculation->bobot_prestasi / 100,
            $ahpCalculation->bobot_orang_tua / 100,
            $ahpCalculation->bobot_jarak / 100,
            $ahpCalculation->bobot_akreditasi / 100
        ];
        
        $result = [
            'eigenVector' => $eigenVector,
            'lambdaMax' => $ahpCalculation->lambda_max,
            'CI' => $ahpCalculation->consistency_index,
            'CR' => $ahpCalculation->consistency_ratio,
            'isConsistent' => $ahpCalculation->consistency_ratio < 0.1
        ];
        
        return view('matrix_result', [
            'matrix' => $matrix,
            'criteriaLabels' => $criteriaLabels,
            'result' => $result,
            'id_transaksi' => $idTransaksi,
            'ahpCalculation' => $ahpCalculation,
            'viewOnly' => $viewOnly
        ]);
    }
    
    /**
     * Fungsi untuk melakukan perhitungan AHP yang benar
     * 
     * @param array $matrix
     * @param array $criteriaLabels
     * @return array
     */
    private function calculateAHP($matrix, $criteriaLabels)
    {
        $criteriaCount = count($criteriaLabels);
        
        // Validasi input
        if ($criteriaCount <= 0) {
            throw new \Exception("Invalid criteria count: $criteriaCount");
        }
        
        if (!is_array($matrix) || count($matrix) != $criteriaCount) {
            throw new \Exception("Invalid matrix structure");
        }
        
        // 1. Pastikan matriks dalam format numerik yang benar
        for ($i = 0; $i < $criteriaCount; $i++) {
            for ($j = 0; $j < $criteriaCount; $j++) {
                if (!isset($matrix[$i][$j])) {
                    throw new \Exception("Matrix element at position [$i][$j] is missing");
                }
                $matrix[$i][$j] = (float)$matrix[$i][$j];
                
                // Pastikan diagonal matriks adalah 1
                if ($i == $j && $matrix[$i][$j] != 1) {
                    $matrix[$i][$j] = 1.0;
                }
            }
        }
        
        // 2. AHP STEP 1: Hitung jumlah tiap kolom
        $colSums = array_fill(0, $criteriaCount, 0);
        for ($j = 0; $j < $criteriaCount; $j++) {
            for ($i = 0; $i < $criteriaCount; $i++) {
                $colSums[$j] += $matrix[$i][$j];
            }
        }
        
        // 3. AHP STEP 2: Normalisasi matriks
        $normalizedMatrix = [];
        for ($i = 0; $i < $criteriaCount; $i++) {
            $normalizedMatrix[$i] = [];
            for ($j = 0; $j < $criteriaCount; $j++) {
                if ($colSums[$j] != 0) {
                    $normalizedMatrix[$i][$j] = $matrix[$i][$j] / $colSums[$j];
                } else {
                    $normalizedMatrix[$i][$j] = 0;
                }
            }
        }
        
        // 4. AHP STEP 3: Hitung priority vector (eigen vector)
        $priorityVector = [];
        for ($i = 0; $i < $criteriaCount; $i++) {
            $rowSum = 0;
            for ($j = 0; $j < $criteriaCount; $j++) {
                $rowSum += $normalizedMatrix[$i][$j];
            }
            $priorityVector[$i] = $rowSum / $criteriaCount;
        }
        
        // 5. AHP STEP 4: Hitung weighted sum vector
        $weightedSumVector = [];
        for ($i = 0; $i < $criteriaCount; $i++) {
            $weightedSumVector[$i] = 0;
            for ($j = 0; $j < $criteriaCount; $j++) {
                $weightedSumVector[$i] += $matrix[$i][$j] * $priorityVector[$j];
            }
        }
        
        // 6. AHP STEP 5: Hitung lambda values (consistency measurement)
        $lambdaValues = [];
        for ($i = 0; $i < $criteriaCount; $i++) {
            if ($priorityVector[$i] > 0) {
                $lambdaValues[$i] = $weightedSumVector[$i] / $priorityVector[$i];
            } else {
                $lambdaValues[$i] = 0;
            }
        }
        
        // 7. AHP STEP 6: Hitung lambda max
        $validLambdas = array_filter($lambdaValues, function($value) {
            return $value > 0 && !is_nan($value) && !is_infinite($value);
        });
        
        $lambdaMax = count($validLambdas) > 0 ? array_sum($validLambdas) / count($validLambdas) : 0;
        
        // 8. AHP STEP 7: Hitung Consistency Index (CI)
        $CI = ($criteriaCount > 1) ? ($lambdaMax - $criteriaCount) / ($criteriaCount - 1) : 0;
        
        // 9. AHP STEP 8: Hitung Consistency Ratio (CR)
        // Random Index (RI) values for different matrix sizes
        $RI_values = [0, 0, 0, 0.58, 0.9, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];
        $RI = ($criteriaCount < count($RI_values)) ? $RI_values[$criteriaCount] : 1.49;
        
        $CR = ($RI > 0) ? ($CI / $RI) : 0;
        
        // 10. Return results
        $result = [
            'eigenVector' => $priorityVector,
            'lambdaMax' => $lambdaMax,
            'CI' => $CI,
            'CR' => $CR,
            'isConsistent' => $CR < 0.1
        ];
        
        return $result;
    }

}