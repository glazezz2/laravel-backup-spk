@extends('layout')

@section('content')
<div class="container mt-5">
    <h2>Riwayat Data Evaluasi One Day at Matana</h2>
    
    {{-- Tampilkan pesan sukses --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Data Terbaru</h4>
        {{-- <a href="{{ route('transaksi.export') }}" class="btn btn-success">Export Data</a> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0 text-center">
                    <thead class="thead-dark">
                        <tr class="align-middle">
                            <th>ID Data</th>
                            <th>Tanggal</th>
                            <th>Jumlah Data</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($transaksi) && count($transaksi) > 0)
                            @foreach($transaksi as $trx)
                                @php
                                    // Check if data exists in processing tables
                                    $ahpMatrixCount = DB::table('ahp_matrix')->where('id_transaksi', $trx->id_transaksi)->count();
                                    $ahpCalculationCount = DB::table('ahp_calculation')->where('id_transaksi', $trx->id_transaksi)->count();
                                    $wsmCalculationCount = DB::table('wsm_calculation')->where('id_transaksi', $trx->id_transaksi)->count();
                                    
                                    // Determine status based on counts - simplified to just "Selesai" or "Belum Selesai"
                                    $statusClass = '';
                                    $statusText = '';
                                    
                                    if ($ahpMatrixCount > 0 && $ahpCalculationCount > 0 && $wsmCalculationCount > 0) {
                                        $statusClass = 'success';
                                        $statusText = 'Selesai';
                                    } else {
                                        $statusClass = 'danger';
                                        $statusText = 'Belum Selesai';
                                    }
                                @endphp
                                <tr class="align-middle">
                                    <td>{{ $trx->id_transaksi }}</td>
                                    <td>{{ $trx->created_at->format('d-m-Y H:i') }}</td>
                                    <td>{{ $trx->trxData->count() }} data</td>
                                    <td>
                                        <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('history.detail', $trx->id_transaksi) }}" class="btn btn-sm btn-info">Detail</a>
                                        <form action="{{ route('history.delete', $trx->id_transaksi) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus transaksi ini dan semua datanya?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data transaksi</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection