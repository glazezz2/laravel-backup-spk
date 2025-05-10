

<?php $__env->startSection('content'); ?>
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
                    <p><strong>ID Transaksi:</strong> <?php echo e($transaksi->id_transaksi); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tanggal:</strong> <?php echo e($transaksi->created_at->format('d-m-Y H:i:s')); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Jumlah Data:</strong> <?php echo e($transaksi->trxData->count()); ?> data</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Transaksi</h5>
            <?php if($transaksi->trxData->count() > 5): ?>
                <a href="#" id="toggleButton" class="btn btn-light btn-sm">
                    <span id="toggleText">Lihat Semua</span>
                </a>
            <?php endif; ?>
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
                        <?php $__empty_1 = true; $__currentLoopData = $transaksi->trxData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if($index < 5): ?>
                                <tr class="align-middle">
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($data->nama); ?></td>
                                    <td><?php echo e($data->kelas); ?></td>
                                    <td><?php echo e($data->tertarik_matana); ?></td>
                                    <td><?php echo e($data->biaya); ?></td>
                                    <td><?php echo e($data->fasilitas); ?></td>
                                    <td><?php echo e($data->prestasi); ?></td>
                                    <td><?php echo e($data->orang_tua); ?></td>
                                    <td><?php echo e($data->jarak); ?></td>
                                    <td><?php echo e($data->akreditasi); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Informasi bahwa hanya menampilkan sebagian data -->
            <?php if($transaksi->trxData->count() > 5): ?>
                <div class="text-center text-muted mb-3 mt-2">
                    Menampilkan 5 dari <?php echo e($transaksi->trxData->count()); ?> data
                </div>
            <?php endif; ?>
            
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
                            <?php $__currentLoopData = $transaksi->trxData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($index >= 5): ?>
                                    <tr class="align-middle">
                                        <td><?php echo e($index + 1); ?></td>
                                        <td><?php echo e($data->nama); ?></td>
                                        <td><?php echo e($data->kelas); ?></td>
                                        <td><?php echo e($data->tertarik_matana); ?></td>
                                        <td><?php echo e($data->biaya); ?></td>
                                        <td><?php echo e($data->fasilitas); ?></td>
                                        <td><?php echo e($data->prestasi); ?></td>
                                        <td><?php echo e($data->orang_tua); ?></td>
                                        <td><?php echo e($data->jarak); ?></td>
                                        <td><?php echo e($data->akreditasi); ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Modal Detail Data -->
            <?php $__currentLoopData = $transaksi->trxData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="modal fade" id="detailModal<?php echo e($data->id_data); ?>" tabindex="-1" aria-labelledby="detailModalLabel<?php echo e($data->id_data); ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailModalLabel<?php echo e($data->id_data); ?>">Detail Data: <?php echo e($data->nama); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <p><strong>Nama:</strong> <?php echo e($data->nama); ?></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p><strong>Kelas:</strong> <?php echo e($data->kelas); ?></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p><strong>Tertarik Berkuliah di Matana:</strong> <?php echo e($data->tertarik_matana); ?></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p><strong>Biaya:</strong> Rp <?php echo e($data->biaya); ?></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p><strong>Fasilitas:</strong> <?php echo e($data->fasilitas); ?></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p><strong>Prestasi:</strong> <?php echo e($data->prestasi); ?></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p><strong>Orang Tua:</strong> <?php echo e($data->orang_tua); ?></p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <p><strong>Jarak:</strong> <?php echo e($data->jarak); ?></p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <p><strong>Akreditasi:</strong> <?php echo e($data->akreditasi); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <!-- Tombol Lanjut untuk menuju page input perbandingan matrix -->
            <div class="text-end mt-4">
                <div class="text-center">
                <a href="<?php echo e(route('matrix.index', ['id_transaksi' => $transaksi->id_transaksi])); ?>" class="btn btn-primary">
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\spk-app\resources\views/detail.blade.php ENDPATH**/ ?>