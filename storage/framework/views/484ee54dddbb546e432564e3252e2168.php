

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h2>Data Alternatif</h2>
    <?php if($alternatives->count() > 0): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Alternatif</th>
                <th>Nama</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $alternatives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alternative): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($alternative->id_alternatif); ?></td>
                <td><?php echo e($alternative->nama); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>Tidak ada data alternatif.</p>
    <?php endif; ?>

    <h2 class="mt-5">Data Kriteria</h2>
    <?php if($criteria->count() > 0): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Kriteria</th>
                <th>Kelas</th>
                <th>Tertarik Matana</th>
                <th>Biaya</th>
                <th>Fasilitas</th>
                <th>Prestasi</th>
                <th>Orang Tua</th>
                <th>Jarak</th>
                <th>Akreditasi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($item->id_kriteria); ?></td>
                <td><?php echo e($item->kelas); ?></td>
                <td><?php echo e($item->tertarik_matana); ?></td>
                <td><?php echo e($item->biaya); ?></td>
                <td><?php echo e($item->fasilitas); ?></td>
                <td><?php echo e($item->prestasi); ?></td>
                <td><?php echo e($item->orang_tua); ?></td>
                <td><?php echo e($item->jarak); ?></td>
                <td><?php echo e($item->akreditasi); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>Tidak ada data kriteria.</p>
    <?php endif; ?>
</div>

<div class="text-right mt-3">
    <a href="<?php echo e(route('matrix')); ?>" class="btn btn-primary">Lanjut ke Matrix</a>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\spk-app\resources\views/upload_result.blade.php ENDPATH**/ ?>