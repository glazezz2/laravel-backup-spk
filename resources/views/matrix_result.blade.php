@extends('layout')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Hasil Perhitungan AHP</h2>
    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Bobot Kriteria</h5>
        </div>
        <div class="card-body">
            @php
                $isConsistent = $result['CR'] < 0.1;
            @endphp
            
            <div class="alert {{ $isConsistent ? 'alert-success' : 'alert-danger' }} text-center">
                <strong>Consistency Ratio (CR): {{ $ahpCalculation->consistency_ratio }}</strong>
                <p class="mb-0">
                    @if($isConsistent)
                        Matriks perbandingan konsisten (CR < 0.1) dan hasil bisa digunakan.
                    @else
                        Matriks perbandingan tidak konsisten (CR >= 0.1). Sebaiknya lakukan penilaian ulang.
                    @endif
                </p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">Kriteria</th>
                            <th class="text-center">Bobot (%)</th>
                            <th class="text-center">Tingkat Kepentingan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Urutkan kriteria berdasarkan bobot
                            $weightedCriteria = [];
                            
                            // Ambil bobot dari database dan bulatkan
                            $weights = [
                                'kelas' => round($ahpCalculation->bobot_kelas, 2),
                                'tertarik berkuliah di matana' => round($ahpCalculation->bobot_tertarik_matana, 2),
                                'biaya' => round($ahpCalculation->bobot_biaya, 2),
                                'fasilitas' => round($ahpCalculation->bobot_fasilitas, 2),
                                'prestasi' => round($ahpCalculation->bobot_prestasi, 2),
                                'orang tua' => round($ahpCalculation->bobot_orang_tua, 2),
                                'jarak' => round($ahpCalculation->bobot_jarak, 2),
                                'akreditasi' => round($ahpCalculation->bobot_akreditasi, 2)
                            ];
                            
                            foreach($criteriaLabels as $criteria) {
                                $weightedCriteria[] = [
                                    'name' => $criteria,
                                    'weight' => $weights[$criteria]
                                ];
                            }
                            
                            // Urutkan berdasarkan bobot (nilai tertinggi)
                            usort($weightedCriteria, function($a, $b) {
                                return $b['weight'] <=> $a['weight'];
                            });
                        @endphp
                        
                        @foreach($weightedCriteria as $rank => $criteria)
                            <tr>
                                <td>{{ ucfirst($criteria['name']) }}</td>
                                <td>{{ $criteria['weight'] }}%</td>
                                <td>{{ $rank + 1 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Detail Perhitungan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-info text-center">
                        <p><strong>Lambda Max:</strong> {{ $ahpCalculation->lambda_max }}</p>
                        <p><strong>Consistency Index (CI):</strong> {{ $ahpCalculation->consistency_index }}</p>
                        <p class="mb-0"><strong>Consistency Ratio (CR):</strong> {{ $ahpCalculation->consistency_ratio }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="text-center">Keterangan:</h5>
                    <ul class="text-center list-unstyled">
                        <li>Bobot menunjukkan tingkat kepentingan dari masing-masing kriteria</li>
                        <li>Peringkat 1 adalah kriteria dengan prioritas tertinggi</li>
                        <li>CR < 0.1 menunjukkan penilaian konsisten</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('matrix.index', ['id_transaksi' => $id_transaksi]) }}" class="btn btn-secondary">
                    <i class="fas fa-edit"></i> Edit Matriks
                </a>
            </div>
            <div class="flex-grow-1 text-center">
                @if($isConsistent)
                    <a href="{{ route('rankingPage', ['id_transaksi' => $id_transaksi]) }}" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Lanjut ke Perankingan
                    </a>
                @else
                <div class="d-inline-block alert alert-warning mb-0 text-center px-3 py-2">
                    <small>Tombol lanjut ke Perankingan akan tersedia setelah matriks perbandingan konsisten (CR < 0.1)</small>
                </div>
                @endif
            </div>
            <div class="invisible">
                <!-- Elemen tak terlihat untuk menyeimbangkan layout -->
                <a class="btn btn-secondary opacity-0" style="visibility: hidden;">
                    <i class="fas fa-edit"></i> Edit Matriks
                </a>
            </div>
        </div>
    </div>    
@endsection