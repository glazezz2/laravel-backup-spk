

<?php $__env->startSection('content'); ?>
<style>
    /* Gaya untuk memastikan tabel memiliki ukuran kolom yang sama */
    .fixed-table {
        table-layout: fixed;
        width: 100%;
    }
    .fixed-table th, .fixed-table td {
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<div class="container">
    <h2 class="mb-4 text-center">Perangkingan Alternatif menggunakan WSM</h2>
      
    <!-- First Table Section - Always Show Top 5 -->
    <div class="card mb-4">
        <div class="card-header" id="rankingHeader">
            <h4 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#rankingTableCollapse" aria-expanded="false" aria-controls="rankingTableCollapse">
                    Ranking Alternatif <i class="fa fa-chevron-down"></i>
                </button>
            </h4>
        </div>
        
        <!-- Tabel untuk menyimpan lebar kolom referensi tapi tidak ditampilkan -->
        <div style="display: none;">
            <table id="columnReference" class="fixed-table">
                <colgroup>
                    <col style="width: 15%"> <!-- Nama Siswa -->
                    <col style="width: 7%"> <!-- Kelas -->
                    <col style="width: 12%"> <!-- Tertarik Kuliah di Matana -->
                    <col style="width: 8%"> <!-- Biaya -->
                    <col style="width: 10%"> <!-- Fasilitas -->
                    <col style="width: 10%"> <!-- Prestasi -->
                    <col style="width: 10%"> <!-- Orang Tua -->
                    <col style="width: 8%"> <!-- Jarak -->
                    <col style="width: 10%"> <!-- Akreditasi -->
                    <col style="width: 10%"> <!-- Total -->
                </colgroup>
            </table>
        </div>
        
        <!-- Top 5 entries always visible -->
        <div class="card-body pb-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0 text-center fixed-table">
                    <colgroup>
                        <col style="width: 15%"> <!-- Nama Siswa -->
                        <col style="width: 7%"> <!-- Kelas -->
                        <col style="width: 12%"> <!-- Tertarik Kuliah di Matana -->
                        <col style="width: 8%"> <!-- Biaya -->
                        <col style="width: 10%"> <!-- Fasilitas -->
                        <col style="width: 10%"> <!-- Prestasi -->
                        <col style="width: 10%"> <!-- Orang Tua -->
                        <col style="width: 8%"> <!-- Jarak -->
                        <col style="width: 10%"> <!-- Akreditasi -->
                        <col style="width: 10%"> <!-- Total -->
                    </colgroup>
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">Nama Siswa</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Tertarik Kuliah di Matana</th>
                            <th class="text-center">Biaya</th>
                            <th class="text-center">Fasilitas</th>
                            <th class="text-center">Prestasi</th>
                            <th class="text-center">Orang Tua</th>
                            <th class="text-center">Jarak</th>
                            <th class="text-center">Akreditasi</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $topFive = array_slice($rankings, 0, 5);
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $topFive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ranking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $formattedTotal = number_format($ranking->total_nilai, 2, '.', '');
                                $kelasValue = ($ranking->kelas == 11) ? '0' : '1';
                                $tertarikValue = ($ranking->tertarik_matana == 'Ya') ? '1' : '0';
                            ?>
                            <tr>
                                <td><?php echo e($ranking->nama); ?></td>
                                <td><?php echo e($kelasValue); ?></td>
                                <td><?php echo e($tertarikValue); ?></td>
                                <td><?php echo e($ranking->biaya == 1 ? '1' : '0'); ?></td>
                                <td><?php echo e($ranking->fasilitas == 1 ? '1' : '0'); ?></td>
                                <td><?php echo e($ranking->prestasi == 1 ? '1' : '0'); ?></td>
                                <td><?php echo e($ranking->orang_tua == 1 ? '1' : '0'); ?></td>
                                <td><?php echo e($ranking->jarak == 1 ? '1' : '0'); ?></td>
                                <td><?php echo e($ranking->akreditasi == 1 ? '1' : '0'); ?></td>
                                <td><?php echo e($formattedTotal); ?>%</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data ranking yang tersedia</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if(count($rankings) > 5): ?>
                <div class="text-center py-2">
                    <small class="text-muted">Menampilkan 5 data teratas dari <?php echo e(count($rankings)); ?> data</small>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Remaining entries in collapsible section -->
        <?php if(count($rankings) > 5): ?>
        <div id="rankingTableCollapse" class="collapse" aria-labelledby="rankingHeader">
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center fixed-table">
                        <colgroup>
                            <col style="width: 15%"> <!-- Nama Siswa -->
                            <col style="width: 7%"> <!-- Kelas -->
                            <col style="width: 12%"> <!-- Tertarik Kuliah di Matana -->
                            <col style="width: 8%"> <!-- Biaya -->
                            <col style="width: 10%"> <!-- Fasilitas -->
                            <col style="width: 10%"> <!-- Prestasi -->
                            <col style="width: 10%"> <!-- Orang Tua -->
                            <col style="width: 8%"> <!-- Jarak -->
                            <col style="width: 10%"> <!-- Akreditasi -->
                            <col style="width: 10%"> <!-- Total -->
                        </colgroup>
                        <tbody>
                            <?php
                                $remaining = array_slice($rankings, 5);
                            ?>
                            <?php $__currentLoopData = $remaining; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ranking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $formattedTotal = number_format($ranking->total_nilai, 2, '.', '');
                                    $kelasValue = ($ranking->kelas == 11) ? '0' : '1';
                                    $tertarikValue = ($ranking->tertarik_matana == 'Ya') ? '1' : '0';
                                ?>
                                <tr>
                                    <td><?php echo e($ranking->nama); ?></td>
                                    <td><?php echo e($kelasValue); ?></td>
                                    <td><?php echo e($tertarikValue); ?></td>
                                    <td><?php echo e($ranking->biaya == 1 ? '1' : '0'); ?></td>
                                    <td><?php echo e($ranking->fasilitas == 1 ? '1' : '0'); ?></td>
                                    <td><?php echo e($ranking->prestasi == 1 ? '1' : '0'); ?></td>
                                    <td><?php echo e($ranking->orang_tua == 1 ? '1' : '0'); ?></td>
                                    <td><?php echo e($ranking->jarak == 1 ? '1' : '0'); ?></td>
                                    <td><?php echo e($ranking->akreditasi == 1 ? '1' : '0'); ?></td>
                                    <td><?php echo e($formattedTotal); ?>%</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php if(count($rankings) > 0): ?>
    <!-- Second Table Section - Always Show Top 5 -->
    <div class="card mt-4">
        <div class="card-header" id="detailHeader">
            <h4 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#detailTableCollapse" aria-expanded="false" aria-controls="detailTableCollapse">
                    Detail Nilai Per Kriteria <i class="fa fa-chevron-down"></i>
                </button>
            </h4>
        </div>
        
        <!-- Top 5 entries always visible -->
        <div class="card-body pb-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0 text-center fixed-table">
                    <colgroup>
                        <col style="width: 15%"> <!-- Nama Siswa -->
                        <col style="width: 7%"> <!-- Kelas -->
                        <col style="width: 12%"> <!-- Tertarik Kuliah di Matana -->
                        <col style="width: 8%"> <!-- Biaya -->
                        <col style="width: 10%"> <!-- Fasilitas -->
                        <col style="width: 10%"> <!-- Prestasi -->
                        <col style="width: 10%"> <!-- Orang Tua -->
                        <col style="width: 8%"> <!-- Jarak -->
                        <col style="width: 10%"> <!-- Akreditasi -->
                        <col style="width: 10%"> <!-- Total -->
                    </colgroup>
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">Nama Siswa</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Tertarik Kuliah di Matana</th>
                            <th class="text-center">Biaya</th>
                            <th class="text-center">Fasilitas</th>
                            <th class="text-center">Prestasi</th>
                            <th class="text-center">Orang Tua</th>
                            <th class="text-center">Jarak</th>
                            <th class="text-center">Akreditasi</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $topFive = array_slice($rankings, 0, 5);
                        ?>
                        <?php $__currentLoopData = $topFive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ranking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $kelasValue = ($ranking->kelas == 12) ? '1' : '0';
                                $tertarikValue = ($ranking->tertarik_matana == 'Ya') ? '1' : '0';
                            ?>
                            <tr>
                                <td><?php echo e($ranking->nama); ?></td>
                                <td><?php echo e(number_format($ranking->nilai_kelas, 2, '.', '')); ?>%</td>
                                <td><?php echo e(number_format($ranking->nilai_tertarik_matana, 2, '.', '')); ?>%</td>
                                <td><?php echo e(number_format($ranking->nilai_biaya, 2, '.', '')); ?>%</td>
                                <td><?php echo e(number_format($ranking->nilai_fasilitas, 2, '.', '')); ?>%</td>
                                <td><?php echo e(number_format($ranking->nilai_prestasi, 2, '.', '')); ?>%</td>
                                <td><?php echo e(number_format($ranking->nilai_orang_tua, 2, '.', '')); ?>%</td>
                                <td><?php echo e(number_format($ranking->nilai_jarak, 2, '.', '')); ?>%</td>
                                <td><?php echo e(number_format($ranking->nilai_akreditasi, 2, '.', '')); ?>%</td>
                                <td><?php echo e(number_format($ranking->total_nilai, 2, '.', '')); ?>%</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <?php if(count($rankings) > 5): ?>
                <div class="text-center py-2">
                    <small class="text-muted">Menampilkan 5 data teratas dari <?php echo e(count($rankings)); ?> data</small>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Remaining entries in collapsible section -->
        <?php if(count($rankings) > 5): ?>
        <div id="detailTableCollapse" class="collapse" aria-labelledby="detailHeader">
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center fixed-table">
                        <colgroup>
                            <col style="width: 15%"> <!-- Nama Siswa -->
                            <col style="width: 7%"> <!-- Kelas -->
                            <col style="width: 12%"> <!-- Tertarik Kuliah di Matana -->
                            <col style="width: 8%"> <!-- Biaya -->
                            <col style="width: 10%"> <!-- Fasilitas -->
                            <col style="width: 10%"> <!-- Prestasi -->
                            <col style="width: 10%"> <!-- Orang Tua -->
                            <col style="width: 8%"> <!-- Jarak -->
                            <col style="width: 10%"> <!-- Akreditasi -->
                            <col style="width: 10%"> <!-- Total -->
                        </colgroup>
                        <tbody>
                            <?php
                                $remaining = array_slice($rankings, 5);
                            ?>
                            <?php $__currentLoopData = $remaining; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ranking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $kelasValue = ($ranking->kelas == 12) ? '1' : '0';
                                    $tertarikValue = ($ranking->tertarik_matana == 'Ya') ? '1' : '0';
                                ?>
                                <tr>
                                    <td><?php echo e($ranking->nama); ?></td>
                                    <td><?php echo e(number_format($ranking->nilai_kelas, 2, '.', '')); ?>%</td>
                                    <td><?php echo e(number_format($ranking->nilai_tertarik_matana, 2, '.', '')); ?>%</td>
                                    <td><?php echo e(number_format($ranking->nilai_biaya, 2, '.', '')); ?>%</td>
                                    <td><?php echo e(number_format($ranking->nilai_fasilitas, 2, '.', '')); ?>%</td>
                                    <td><?php echo e(number_format($ranking->nilai_prestasi, 2, '.', '')); ?>%</td>
                                    <td><?php echo e(number_format($ranking->nilai_orang_tua, 2, '.', '')); ?>%</td>
                                    <td><?php echo e(number_format($ranking->nilai_jarak, 2, '.', '')); ?>%</td>
                                    <td><?php echo e(number_format($ranking->nilai_akreditasi, 2, '.', '')); ?>%</td>
                                    <td><?php echo e(number_format($ranking->total_nilai, 2, '.', '')); ?>%</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Add toggle icon functionality
        $('.btn-link').on('click', function() {
            const icon = $(this).find('i');
            if (icon.hasClass('fa-chevron-down')) {
                icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            } else {
                icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\spk-app\resources\views/ranking.blade.php ENDPATH**/ ?>