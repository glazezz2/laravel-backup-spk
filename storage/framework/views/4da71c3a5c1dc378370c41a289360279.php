

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Daftar Transaksi</h2>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Pilih Transaksi</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($transaction->id_transaksi); ?></td>
                            <td><?php echo e($transaction->created_at); ?></td>
                            <td>
                                <a href="<?php echo e(route('rankingPage', ['id_transaksi' => $transaction->id_transaksi])); ?>" 
                                   class="btn btn-primary btn-sm">
                                    Lihat Ranking
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data transaksi yang tersedia</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\spk-app\resources\views/transaction-list.blade.php ENDPATH**/ ?>