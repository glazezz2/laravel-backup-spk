@extends('layout')

@section('content')
<div class="container mt-5">
    <h2>Upload Data Evaluasi One Day at Matana</h2>
    
    {{-- Tampilkan pesan error dari validasi Laravel --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tampilkan pesan sukses --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Import Data CSV</h4>
        </div>
        <div class="card-body">
            <form id="uploadForm" action="{{ route('transaksi.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="csv_file">Pilih file CSV (dipisahkan dengan koma):</label>
                    <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
                    <small class="form-text text-muted">
                        - Format CSV harus berisi kolom: nama, kelas, tertarik_matana, biaya, fasilitas, prestasi, orang_tua, jarak, akreditasi<br>
                        - Semua data dalam satu file akan memiliki ID Transaksi yang sama
                    </small>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Import Data</button>
                </div>
            </form>
        </div>
    </div>

<script>
    // Fungsi untuk mengecek delimiter file CSV
    function checkDelimiter(fileContent, expectedDelimiter = ',') {
        // Kita periksa beberapa baris pertama (misalnya 3 baris) untuk melihat apakah ada delimiter
        const lines = fileContent.split(/\r?\n/).slice(0, 3);
        for (let line of lines) {
            if (line.indexOf(expectedDelimiter) !== -1) {
                return true;
            }
        }
        return false;
    }

    // Fungsi untuk memeriksa header CSV
    function checkCSVHeaders(fileContent) {
        const requiredHeaders = ['nama', 'kelas', 'tertarik_matana', 'biaya', 'fasilitas', 'prestasi', 'orang_tua', 'jarak', 'akreditasi'];
        const lines = fileContent.split(/\r?\n/);
        
        if (lines.length === 0) return false;
        
        const headers = lines[0].toLowerCase().split(',').map(header => header.trim());
        
        // Periksa apakah semua header yang diperlukan ada
        return requiredHeaders.every(header => headers.includes(header));
    }

    document.getElementById('csv_file').addEventListener('change', function(e) {
        const fileInput = e.target;
        const file = fileInput.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const fileContent = event.target.result;
                // Jika file tidak mengandung delimiter koma
                if (!checkDelimiter(fileContent, ',')) {
                    alert('File CSV tidak sesuai dengan delimiter yang diharapkan (koma). Harap periksa file Anda.');
                    fileInput.value = ''; // Kosongkan input
                    return;
                }
                
                // Periksa header CSV
                if (!checkCSVHeaders(fileContent)) {
                    alert('Format CSV tidak valid. Pastikan file CSV Anda memiliki header: nama, kelas, tertarik_matana, biaya, fasilitas, prestasi, orang_tua, jarak, akreditasi');
                    fileInput.value = '';
                }
            };
            reader.readAsText(file);
        }
    });

    // Validasi form saat submit
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('csv_file');
        if (!fileInput.value) {
            e.preventDefault();
            alert('Silakan pilih file CSV terlebih dahulu.');
            return;
        }
    });
</script>
@endsection