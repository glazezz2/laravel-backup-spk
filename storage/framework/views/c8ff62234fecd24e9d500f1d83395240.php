

<?php $__env->startSection('content'); ?>
<?php
    // Definisikan kriteria sesuai dengan kolom di data_uploads (kecuali "nama")
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
    
    // Jika ada data matrix sebelumnya, gunakan untuk mengisi nilai default
    $existingMatrix = $matrixData ?? null;
    
    // Tentukan apakah dalam mode view-only
    $viewOnly = $viewOnly ?? false;
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">
        <?php if($viewOnly): ?>
            Nilai Matriks Perbandingan (View Only)
        <?php else: ?>
            Input Nilai Matriks Perbandingan
        <?php endif; ?>
    </h2>
    
    <?php if($viewOnly): ?>
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle"></i> Proses sudah selesai. Matriks ditampilkan dalam mode view-only.
    </div>
    <?php endif; ?>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if(!$viewOnly): ?>
                <!-- Form untuk mode edit -->
                <form id="matrixForm" action="<?php echo e(route('matrix.process')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id_transaksi" value="<?php echo e($idTransaksi ?? session('active_transaction')); ?>">
            <?php endif; ?>
            
            <div class="table-responsive">
                <table class="table table-sm table-striped table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th class="align-middle">Kriteria</th>
                            <?php $__currentLoopData = $criteriaLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th class="align-middle"><?php echo e(ucfirst($label)); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($i = 0; $i < $criteriaCount; $i++): ?>
                            <tr>
                                <th scope="row" class="align-middle bg-dark text-white"><?php echo e(ucfirst($criteriaLabels[$i])); ?></th>
                                <?php for($j = 0; $j < $criteriaCount; $j++): ?>
                                    <td>
                                        <?php if($viewOnly): ?>
                                            <!-- Mode view-only: tampilkan nilai sebagai text -->
                                            <span class="form-control-plaintext text-center">
                                                <?php
                                                    $value = isset($existingMatrix[$i][$j]) ? $existingMatrix[$i][$j] : '-';
                                                    // Tampilkan nilai yang lebih mudah dibaca untuk nilai khusus
                                                    if ($value == 0.333 || abs($value - 0.333) < 0.001) {
                                                        echo '0.333';
                                                    } elseif ($value == 0.167 || abs($value - 0.167) < 0.001) {
                                                        echo '0.167';
                                                    } elseif ($value == 0.143 || abs($value - 0.143) < 0.001) {
                                                        echo '0.143';
                                                    } elseif ($value == 0.111 || abs($value - 0.111) < 0.001) {
                                                        echo '0.111';
                                                    } else {
                                                        echo $value;
                                                    }
                                                ?>
                                            </span>
                                        <?php else: ?>
                                            <!-- Mode edit -->
                                            <?php if($i == $j): ?>
                                                <!-- Diagonal: selalu 1 -->
                                                <input type="number" name="matrix[<?php echo e($i); ?>][<?php echo e($j); ?>]" value="1" class="form-control text-center" readonly>
                                            <?php elseif($i > $j): ?>
                                                <!-- Bagian bawah diagonal: input user sebagai dropdown -->
                                                <?php
                                                    // Ambil nilai yang sudah disubmit atau dari existingMatrix (jika ada)
                                                    $currentVal = old("matrix.$i.$j", isset($existingMatrix[$i][$j]) ? $existingMatrix[$i][$j] : '');
                                                ?>
                                                <select name="matrix[<?php echo e($i); ?>][<?php echo e($j); ?>]" class="form-control text-center matrix-input" 
                                                        data-row="<?php echo e($i); ?>" data-col="<?php echo e($j); ?>" required>
                                                    <option value="">-- Pilih --</option>
                                                    <!-- Opsi standar 1-9 dengan deskripsi -->
                                                    <?php
                                                    $importanceLevels = [
                                                        1 => ['label' => '1', 'desc' => 'Kedua elemen sama penting'],
                                                        2 => ['label' => '2', 'desc' => 'Satu elemen sedikit lebih penting dari yang lain'],
                                                        3 => ['label' => '3', 'desc' => 'Satu elemen lebih penting dari yang lain'],
                                                        4 => ['label' => '4', 'desc' => 'Tingkat kepentingannya cukup tinggi'],
                                                        5 => ['label' => '5', 'desc' => 'Satu elemen jelas lebih penting dari yang lain'],
                                                        6 => ['label' => '6', 'desc' => 'Lebih dari penting, mendekati sangat penting'],
                                                        7 => ['label' => '7', 'desc' => 'Satu elemen sangat penting dibanding lainnya'],
                                                        8 => ['label' => '8', 'desc' => 'Tingkat kepentingannya sangat tinggi'],
                                                        9 => ['label' => '9', 'desc' => 'Sangat mutlak lebih penting']
                                                    ];
                                                    ?>

                                                    <?php $__currentLoopData = $importanceLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $isSelected = ($currentVal !== '' && 
                                                                number_format((float)$currentVal, 3) == number_format((float)$val, 3)
                                                            ) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?php echo e($val); ?>" title="<?php echo e($item['desc']); ?>" <?php echo e($isSelected); ?>>
                                                            <?php echo e($item['label']); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                    <!-- Nilai invers 1/2 hingga 1/9 -->
                                                    <optgroup label="Nilai Invers (Kurang Penting)">
                                                        <?php for($k = 2; $k <= 9; $k++): ?>
                                                            <?php 
                                                                $invValue = number_format(1/$k, 3);
                                                                $labels = [
                                                                    2 => ['label' => '1/2', 'desc' => 'Satu elemen sedikit kurang penting dari yang lain'],
                                                                    3 => ['label' => '1/3', 'desc' => 'Satu elemen kurang penting dari yang lain'],
                                                                    4 => ['label' => '1/4', 'desc' => 'Tingkat ketidakpentingannya cukup tinggi'],
                                                                    5 => ['label' => '1/5', 'desc' => 'Satu elemen cukup tidak penting'],
                                                                    6 => ['label' => '1/6', 'desc' => 'Lebih dari tidak penting'],
                                                                    7 => ['label' => '1/7', 'desc' => 'Satu elemen sangat tidak penting dibanding lainnya'],
                                                                    8 => ['label' => '1/8', 'desc' => 'Tingkat ketidakpentingannya sangat tinggi'],
                                                                    9 => ['label' => '1/9', 'desc' => 'Sangat mutlak tidak penting']
                                                                ];
                                                                $isSelected = ($currentVal !== '' && number_format((float)$currentVal, 3) == $invValue) 
                                                                    ? 'selected' : '';
                                                            ?>
                                                            <option value="<?php echo e($invValue); ?>" title="<?php echo e($labels[$k]['desc']); ?>" <?php echo e($isSelected); ?>>
                                                                <?php echo e($labels[$k]['label']); ?> (<?php echo e($invValue); ?>)
                                                            </option>
                                                        <?php endfor; ?>
                                                    </optgroup>
                                                    
                                                </select>
                                            <?php else: ?>
                                                <!-- Bagian atas diagonal: otomatis sebagai invers, tidak bisa diedit -->
                                                <input type="text" name="matrix[<?php echo e($i); ?>][<?php echo e($j); ?>]" 
                                                class="form-control text-center inverse-value" 
                                                data-row="<?php echo e($i); ?>" data-col="<?php echo e($j); ?>"
                                                value="<?php echo e(isset($existingMatrix[$i][$j]) ? $existingMatrix[$i][$j] : ''); ?>" 
                                                data-raw="<?php echo e(isset($existingMatrix[$i][$j]) ? $existingMatrix[$i][$j] : ''); ?>"
                                                readonly>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if(!$viewOnly): ?>
                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-primary">Submit Matriks</button>
                </div>
                </form>
            <?php else: ?>
                    <div class="text-center">
                    <a href="<?php echo e(route('matrix.result', ['id_transaksi' => $idTransaksi])); ?>" class="btn btn-info">
                        Lihat Hasil Perhitungan
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if(!$viewOnly): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Special fractions that should be rounded to whole numbers when inverted
    const specialFractions = {
        '0.333': 3,
        '0.167': 6,
        '0.143': 7,
        '0.111': 9
    };
    
    // Special values and their display format
    const specialValues = {
        '3': '3',
        '6': '6',
        '7': '7',
        '9': '9',
        '0.333': '0.333',
        '0.167': '0.167',
        '0.143': '0.143',
        '0.111': '0.111'
    };
    
    // Function to format a value for display
    function formatValueForDisplay(value) {
        let formattedValue = parseFloat(value).toFixed(3);
        if (specialValues[formattedValue]) {
            return specialValues[formattedValue];
        }
        if (Math.floor(value) === parseFloat(value)) {
            return Math.floor(value).toString();
        }
        return formattedValue;
    }
    
    // Function to calculate and format inverse value
    function calculateInverse(value) {
        if (!value || value === '0') return '';
        let numValue = parseFloat(value);
        let roundedValue = numValue.toFixed(3);
        if (roundedValue in specialFractions) {
            return specialFractions[roundedValue];
        }
        let inverse = 1 / numValue;
        if (Number.isInteger(inverse) || Math.abs(inverse - Math.round(inverse)) < 0.001) {
            return Math.round(inverse);
        }
        return inverse.toFixed(3);
    }
    
    // Function to update the inverse value
    function updateInverse(row, col, value) {
        let inverseRow = col;
        let inverseCol = row;
        let inverseValue = calculateInverse(value);
        let inverseInput = document.querySelector(`input[data-row="${inverseRow}"][data-col="${inverseCol}"]`);
        if (inverseInput) {
            const disp = formatValueForDisplay(inverseValue);
            inverseInput.value = disp;
            inverseInput.dataset.raw = inverseValue;
            const key = parseFloat(inverseValue).toFixed(3);
            if (specialValues[key]) {
                inverseInput.title = specialValues[key];
            }
        }
    }
    
    // Initialize the matrix dengan nilai yang ada
    document.querySelectorAll('.matrix-input').forEach(function(select) {
        let row = select.getAttribute('data-row');
        let col = select.getAttribute('data-col');
        let value = select.value;
        if (value) {
            updateInverse(row, col, value);
        }
    });
    
    // Tambahkan event listener untuk tiap input
    document.querySelectorAll('.matrix-input').forEach(function(select) {
        select.addEventListener('change', function() {
            let row = this.getAttribute('data-row');
            let col = this.getAttribute('data-col');
            let value = this.value;
            updateInverse(row, col, value);
        });
    });

    // Sebelum submit, pastikan inverse-value diupdate menggunakan nilai raw
    document.querySelectorAll('.inverse-value').forEach(inv => {
        if (inv.dataset.raw) {
            inv.value = inv.dataset.raw;
        }
    });
});
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\spk-app\resources\views/matrix.blade.php ENDPATH**/ ?>