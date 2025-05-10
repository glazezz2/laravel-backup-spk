@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Data Transaksi</h2>
        <div>
            <a href="javascript:history.back()" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informasi Transaksi</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID Transaksi:</strong> {{ $transaksi->id_transaksi }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tanggal:</strong> {{ $transaksi->created_at->format('d-m-Y H:i:s') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Jumlah Data:</strong> {{ $transaksi->trxData->count() }} data</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Transaksi</h5>
            @if($transaksi->trxData->count() > 5)
                <a href="#" id="toggleButton" class="btn btn-light btn-sm">
                    <span id="toggleText">Lihat Semua</span>
                </a>
            @endif
        </div>
        <div class="card-body">
            <!-- Tabel untuk 5 data pertama - selalu ditampilkan -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr class="align-middle">
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Tertarik Berkuliah di Matana</th>
                            <th>Biaya</th>
                            <th>Fasilitas</th>
                            <th>Prestasi</th>
                            <th>Orang Tua</th>
                            <th>Jarak</th>
                            <th>Akreditasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi->trxData as $index => $data)
                            @if($index < 5)
                                <tr class="align-middle">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->kelas }}</td>
                                    <td>{{ $data->tertarik_matana }}</td>
                                    <td>{{ $data->biaya }}</td>
                                    <td>{{ $data->fasilitas }}</td>
                                    <td>{{ $data->prestasi }}</td>
                                    <td>{{ $data->orang_tua }}</td>
                                    <td>{{ $data->jarak }}</td>
                                    <td>{{ $data->akreditasi }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Informasi bahwa hanya menampilkan sebagian data -->
            @if($transaksi->trxData->count() > 5)
                <div class="text-center text-muted mb-3 mt-2">
                    Menampilkan 5 dari {{ $transaksi->trxData->count() }} data
                </div>
            @endif
            
            <!-- Tabel untuk data tambahan - hidden by default -->
            <div id="additionalData" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr class="align-middle">
                                <th>No</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Tertarik Berkuliah di Matana</th>
                                <th>Biaya</th>
                                <th>Fasilitas</th>
                                <th>Prestasi</th>
                                <th>Orang Tua</th>
                                <th>Jarak</th>
                                <th>Akreditasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi->trxData as $index => $data)
                                @if($index >= 5)
                                    <tr class="align-middle">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $data->nama }}</td>
                                        <td>{{ $data->kelas }}</td>
                                        <td>{{ $data->tertarik_matana }}</td>
                                        <td>{{ $data->biaya }}</td>
                                        <td>{{ $data->fasilitas }}</td>
                                        <td>{{ $data->prestasi }}</td>
                                        <td>{{ $data->orang_tua }}</td>
                                        <td>{{ $data->jarak }}</td>
                                        <td>{{ $data->akreditasi }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Modal Detail Data -->
            @foreach($transaksi->trxData as $data)
                <div class="modal fade" id="detailModal{{ $data->id_data }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $data->id_data }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailModalLabel{{ $data->id_data }}">Detail Data: {{ $data->nama }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <p><strong>Nama:</strong> {{ $data->nama }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p><strong>Kelas:</strong> {{ $data->kelas }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p><strong>Tertarik Berkuliah di Matana:</strong> {{ $data->tertarik_matana }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p><strong>Biaya:</strong> Rp {{ $data->biaya }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p><strong>Fasilitas:</strong> {{ $data->fasilitas }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p><strong>Prestasi:</strong> {{ $data->prestasi }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p><strong>Orang Tua:</strong> {{ $data->orang_tua }}</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <p><strong>Jarak:</strong> {{ $data->jarak }}</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <p><strong>Akreditasi:</strong> {{ $data->akreditasi }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <!-- Tombol Lanjut untuk menuju page input perbandingan matrix -->
            <div class="text-end mt-4">
                <div class="text-center">
                <a href="{{ route('matrix.index', ['id_transaksi' => $transaksi->id_transaksi]) }}" class="btn btn-primary">
                    Lanjut ke Matriks Perbandingan <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mengambil elemen tombol dan area data tambahan
        const toggleButton = document.getElementById('toggleButton');
        const toggleText = document.getElementById('toggleText');
        const additionalData = document.getElementById('additionalData');
        
        // Hanya lakukan jika elemen-elemen ditemukan
        if (toggleButton && additionalData) {
            // Variabel untuk melacak status (apakah diperluas atau tidak)
            let isExpanded = false;
            
            // Menambahkan event listener untuk tombol
            toggleButton.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah link dari navigasi
                
                // Toggle visibilitas
                if (isExpanded) {
                    additionalData.style.display = 'none';
                    toggleText.textContent = 'Lihat Semua';
                } else {
                    additionalData.style.display = 'block';
                    toggleText.textContent = 'Sembunyikan';
                }
                
                // Toggle status
                isExpanded = !isExpanded;
            });
        }
    });
</script>
@endsection