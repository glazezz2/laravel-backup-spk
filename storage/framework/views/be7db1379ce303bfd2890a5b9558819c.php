

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h2>Riwayat Data Evaluasi One Day at Matana</h2>
    
    
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Data Terbaru</h4>
        
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
                        <?php if(isset($transaksi) && count($transaksi) > 0): ?>
                            <?php $__currentLoopData = $transaksi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
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
                                ?>
                                <tr class="align-middle">
                                    <td><?php echo e($trx->id_transaksi); ?></td>
                                    <td><?php echo e($trx->created_at->format('d-m-Y H:i')); ?></td>
                                    <td><?php echo e($trx->trxData->count()); ?> data</td>
                                    <td>
                                        <span class="badge badge-<?php echo e($statusClass); ?>"><?php echo e($statusText); ?></span>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('history.detail', $trx->id_transaksi)); ?>" class="btn btn-sm btn-info">Detail</a>
                                        <form action="<?php echo e(route('history.delete', $trx->id_transaksi)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus transaksi ini dan semua datanya?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data transaksi</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\spk-app\resources\views/history.blade.php ENDPATH**/ ?>