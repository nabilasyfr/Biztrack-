
<?php $__env->startSection('title','Input Modal & Jurnal Manual'); ?>
<?php $__env->startSection('page-title','Input Modal & Jurnal Manual'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Input Modal & Jurnal Manual</h1>
        <p>Catat modal awal, penyesuaian, atau transaksi akuntansi yang perlu diinput manual</p>
    </div>
    <a href="<?php echo e(route('accounting.journal')); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-journal-text me-1"></i> Lihat Jurnal
    </a>
</div>


<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;color:#16a34a"><i class="bi bi-bank"></i></div>
            <div>
                <div class="stat-label">Total Modal Pemilik (3100)</div>
                <div class="stat-value" style="font-size:18px; color:#16a34a">
                    Rp<?php echo e(number_format($totalModal, 0, ',', '.')); ?>

                </div>
                <div class="stat-sub">Saldo akun Modal Pemilik</div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="alert alert-info mb-0" style="border-radius:12px; font-size:13.5px; height:100%; display:flex; align-items:center; gap:12px;">
            <i class="bi bi-lightbulb-fill fs-4"></i>
            <div>
                <strong>Cara Input Modal Awal:</strong><br>
                Pilih <strong>Debit → Kas (1100)</strong> dan <strong>Kredit → Modal Pemilik (3100)</strong>.<br>
                Ini mencerminkan bahwa uang masuk ke kas berasal dari modal pemilik toko.
            </div>
        </div>
    </div>
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

    
    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-book me-2"></i>Panduan: Kombinasi Umum</div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    
                    <div class="list-group-item px-3 py-3" style="cursor:pointer"
                         onclick="fillTemplate('Modal Awal Toko', 1100, 3100)">
                        <div class="fw-bold mb-1" style="font-size:13px">
                            💰 Modal Awal / Setoran Modal
                        </div>
                        <div style="font-size:12px; color:#64748b">
                            <span class="badge bg-primary">Debit</span> Kas (1100)
                            &nbsp;←&nbsp;
                            <span class="badge bg-success">Kredit</span> Modal Pemilik (3100)
                        </div>
                        <div class="text-muted mt-1" style="font-size:11px">
                            Klik untuk isi otomatis →
                        </div>
                    </div>

                    
                    <div class="list-group-item px-3 py-3" style="cursor:pointer"
                         onclick="fillTemplate('Setoran Modal ke Dana Wallet', 1110, 3100)">
                        <div class="fw-bold mb-1" style="font-size:13px">
                            💙 Modal Masuk ke Dana Wallet
                        </div>
                        <div style="font-size:12px; color:#64748b">
                            <span class="badge bg-primary">Debit</span> Dana Wallet (1110)
                            &nbsp;←&nbsp;
                            <span class="badge bg-success">Kredit</span> Modal Pemilik (3100)
                        </div>
                        <div class="text-muted mt-1" style="font-size:11px">Klik untuk isi otomatis →</div>
                    </div>

                    
                    <div class="list-group-item px-3 py-3" style="cursor:pointer"
                         onclick="fillTemplate('Setoran Modal ke Rekening Bank', 1120, 3100)">
                        <div class="fw-bold mb-1" style="font-size:13px">
                            🏦 Modal Masuk ke Rekening Bank
                        </div>
                        <div style="font-size:12px; color:#64748b">
                            <span class="badge bg-primary">Debit</span> Rekening Bank (1120)
                            &nbsp;←&nbsp;
                            <span class="badge bg-success">Kredit</span> Modal Pemilik (3100)
                        </div>
                        <div class="text-muted mt-1" style="font-size:11px">Klik untuk isi otomatis →</div>
                    </div>

                    
                    <div class="list-group-item px-3 py-3" style="cursor:pointer"
                         onclick="fillTemplate('Pembelian Stok Barang Awal', 1200, 1100)">
                        <div class="fw-bold mb-1" style="font-size:13px">
                            📦 Pembelian Stok Barang (Tunai)
                        </div>
                        <div style="font-size:12px; color:#64748b">
                            <span class="badge bg-primary">Debit</span> Persediaan (1200)
                            &nbsp;←&nbsp;
                            <span class="badge bg-success">Kredit</span> Kas (1100)
                        </div>
                        <div class="text-muted mt-1" style="font-size:11px">Klik untuk isi otomatis →</div>
                    </div>

                    
                    <div class="list-group-item px-3 py-3" style="cursor:pointer"
                         onclick="fillTemplate('Penyesuaian Saldo Kas', 1100, 3100)">
                        <div class="fw-bold mb-1" style="font-size:13px">
                            🔧 Koreksi / Penyesuaian Saldo
                        </div>
                        <div style="font-size:12px; color:#64748b">
                            Bebas pilih akun debit & kredit sesuai kebutuhan
                        </div>
                        <div class="text-muted mt-1" style="font-size:11px">Klik untuk isi otomatis →</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning" style="border-radius:12px; font-size:13px">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Perhatian:</strong> Jurnal manual langsung mempengaruhi saldo Buku Besar.
            Pastikan akun Debit dan Kredit sudah benar sebelum menyimpan.
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