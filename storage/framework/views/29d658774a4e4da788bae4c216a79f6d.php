
<?php $__env->startSection('title','Input Jurnal Manual'); ?>
<?php $__env->startSection('page-title','Input Jurnal Manual'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Input Jurnal Manual</h1>
        <p>Catat Penyesuaian, atau transaksi akuntansi yang perlu diinput manual</p>
    </div>
    <a href="<?php echo e(route('accounting.journal')); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-journal-text me-1"></i> Lihat Jurnal
    </a>
</div>



<div class="row g-3">
    
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2"></i>Form Jurnal Manual</div>
            <div class="card-body p-4">
                <form method="POST" action="<?php echo e(route('accounting.modal.store')); ?>" id="journalForm">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label">Keterangan / Deskripsi <span class="text-danger">*</span></label>
                        <input type="text" name="description"
                               class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('description')); ?>"
                               placeholder="Contoh: Modal Awal Toko, Penyesuaian Kas, Setoran Dana..."
                               required>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="entry_date"
                               class="form-control <?php $__errorArgs = ['entry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('entry_date', now()->format('Y-m-d'))); ?>" required>
                        <?php $__errorArgs = ['entry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="amount" id="amountInput"
                                   class="form-control <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('amount')); ?>"
                                   min="1" step="1"
                                   placeholder="Contoh: 20000000"
                                   oninput="updatePreview()" required>
                            <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div id="amountFormatted" class="form-text text-primary fw-semibold"></div>
                    </div>

                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="badge bg-primary me-1">DEBIT</span>
                                Akun yang Bertambah <span class="text-danger">*</span>
                            </label>
                            <select name="debit_account"
                                    class="form-select <?php $__errorArgs = ['debit_account'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required onchange="updatePreview()">
                                <option value="">-- Pilih Akun Debit --</option>
                                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($acc->id); ?>"
                                    <?php echo e(old('debit_account') == $acc->id ? 'selected' : ''); ?>>
                                    <?php echo e($acc->code); ?> - <?php echo e($acc->name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['debit_account'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="badge bg-success me-1">KREDIT</span>
                                Akun Sumber Dana <span class="text-danger">*</span>
                            </label>
                            <select name="credit_account"
                                    class="form-select <?php $__errorArgs = ['credit_account'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required onchange="updatePreview()">
                                <option value="">-- Pilih Akun Kredit --</option>
                                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($acc->id); ?>"
                                    <?php echo e(old('credit_account') == $acc->id ? 'selected' : ''); ?>>
                                    <?php echo e($acc->code); ?> - <?php echo e($acc->name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['credit_account'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    
                    <div id="journalPreview" class="p-3 mb-3" style="background:#f8fafc; border-radius:10px; border:1px solid #e2e8f0; display:none;">
                        <div class="fw-bold mb-2" style="font-size:13px; color:#374151">
                            <i class="bi bi-eye me-1"></i> Preview Jurnal:
                        </div>
                        <table class="table table-sm mb-0" style="font-size:12px;">
                            <thead>
                                <tr>
                                    <th>Akun</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="prev_debit_name" class="text-primary fw-semibold">-</td>
                                    <td class="text-end text-primary fw-bold" id="prev_debit_amt">-</td>
                                    <td class="text-end text-muted">-</td>
                                </tr>
                                <tr>
                                    <td id="prev_credit_name" class="text-success fw-semibold ps-3">-</td>
                                    <td class="text-end text-muted">-</td>
                                    <td class="text-end text-success fw-bold" id="prev_credit_amt">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <?php $__errorArgs = ['error'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="alert alert-danger" style="border-radius:8px; font-size:13px"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?php echo e(route('accounting.coa')); ?>" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Jurnal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Data akun untuk preview
var accountNames = {
    <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php echo e($acc->id); ?>: '<?php echo e($acc->code); ?> - <?php echo e(addslashes($acc->name)); ?>',
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
};

function fmtRp(n) {
    return 'Rp ' + Number(n).toLocaleString('id-ID');
}

// Update preview jurnal secara real-time
function updatePreview() {
    var amount  = parseFloat(document.getElementById('amountInput').value) || 0;
    var debitId = document.querySelector('[name=debit_account]').value;
    var credId  = document.querySelector('[name=credit_account]').value;
    var preview = document.getElementById('journalPreview');

    // Format jumlah terbilang
    var fmtDiv = document.getElementById('amountFormatted');
    if (amount > 0) {
        fmtDiv.textContent = '= ' + fmtRp(amount);
    } else {
        fmtDiv.textContent = '';
    }

    if (amount > 0 && debitId && credId) {
        preview.style.display = 'block';
        document.getElementById('prev_debit_name').textContent  = accountNames[debitId] || '-';
        document.getElementById('prev_credit_name').textContent = '    ' + (accountNames[credId] || '-');
        document.getElementById('prev_debit_amt').textContent   = fmtRp(amount);
        document.getElementById('prev_credit_amt').textContent  = fmtRp(amount);
    } else {
        preview.style.display = 'none';
    }
}

// Isi form otomatis dari template panduan
function fillTemplate(desc, debitCode, creditCode) {
    // Set deskripsi
    document.querySelector('[name=description]').value = desc;

    // Cari ID akun berdasarkan kode
    var debitSel  = document.querySelector('[name=debit_account]');
    var creditSel = document.querySelector('[name=credit_account]');

    // Map kode akun ke account_id
    var codeToId = {
        <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        '<?php echo e($acc->code); ?>': <?php echo e($acc->id); ?>,
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    };

    var debitId  = codeToId[String(debitCode).padStart(4,'0')] || codeToId[debitCode];
    var creditId = codeToId[String(creditCode).padStart(4,'0')] || codeToId[creditCode];

    if (debitId)  debitSel.value  = debitId;
    if (creditId) creditSel.value = creditId;

    // Focus ke field jumlah
    document.getElementById('amountInput').focus();
    updatePreview();

    // Scroll ke form
    document.getElementById('journalForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Jalankan preview saat load jika ada old value
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/accounting/modal.blade.php ENDPATH**/ ?>