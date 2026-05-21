<?php $__env->startSection('title','Jurnal Penyesuaian'); ?>
<?php $__env->startSection('page-title','Jurnal Penyesuaian (Adjusting Entries)'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Jurnal Penyesuaian</h1>
        <p>Koreksi akun akhir periode — beban akrual, prabayar, depresiasi, dll</p>
    </div>
</div>

<div class="row g-3">
    
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2"></i>Tambah Jurnal Penyesuaian</div>
            <div class="card-body p-4">

                <div class="alert alert-info" style="font-size:13px;border-radius:10px">
                    <i class="bi bi-info-circle me-2"></i>
                    Jurnal penyesuaian dicatat dengan prefix <strong>ADJ-</strong> dan digunakan
                    dalam <strong>Neraca Saldo Disesuaikan</strong> serta <strong>Kertas Kerja</strong>.
                </div>

                <form method="POST" action="<?php echo e(route('accounting.adjusting.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Tipe Penyesuaian <span class="text-danger">*</span></label>
                        <select name="adj_type" class="form-select <?php $__errorArgs = ['adj_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                onchange="setAdjTemplate(this.value)" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="prepaid"     <?php echo e(old('adj_type')=='prepaid'     ?'selected':''); ?>>Beban Dibayar di Muka (Prepaid)</option>
                            <option value="accrued"     <?php echo e(old('adj_type')=='accrued'     ?'selected':''); ?>>Beban Akrual / Terutang (Accrued)</option>
                            <option value="depreciation"<?php echo e(old('adj_type')=='depreciation'?'selected':''); ?>>Penyusutan Aset (Depreciation)</option>
                            <option value="inventory"   <?php echo e(old('adj_type')=='inventory'   ?'selected':''); ?>>Penyesuaian Persediaan (Inventory)</option>
                            <option value="other"       <?php echo e(old('adj_type')=='other'       ?'selected':''); ?>>Lainnya</option>
                        </select>
                        <?php $__errorArgs = ['adj_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div id="adjHint" class="form-text"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan <span class="text-danger">*</span></label>
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
                               placeholder="Contoh: Penyesuaian beban listrik akrual bulan Mei"
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
                        <label class="form-label">Tanggal Penyesuaian <span class="text-danger">*</span></label>
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
                        <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="amount"
                                   class="form-control <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('amount')); ?>" min="1" step="1"
                                   placeholder="0" required>
                            <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label"><span class="badge bg-primary">DEBIT</span> Akun</label>
                            <select name="debit_account" id="debitSel"
                                    class="form-select <?php $__errorArgs = ['debit_account'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">-- Pilih --</option>
                                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($acc->id); ?>" <?php echo e(old('debit_account')==$acc->id?'selected':''); ?>>
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
                        <div class="col-6">
                            <label class="form-label"><span class="badge bg-success">KREDIT</span> Akun</label>
                            <select name="credit_account" id="creditSel"
                                    class="form-select <?php $__errorArgs = ['credit_account'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">-- Pilih --</option>
                                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($acc->id); ?>" <?php echo e(old('credit_account')==$acc->id?'selected':''); ?>>
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

                    <?php $__errorArgs = ['error'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="alert alert-danger py-2" style="font-size:13px"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Simpan Jurnal Penyesuaian
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-journal-check me-2"></i>Daftar Jurnal Penyesuaian (<?php echo e($entries->total()); ?>)</div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border-bottom p-3 px-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-bold text-warning"><i class="bi bi-wrench me-1"></i><?php echo e($entry->reference); ?></span>
                        <span class="text-muted" style="font-size:12px"><?php echo e(\Carbon\Carbon::parse($entry->entry_date)->format('d M Y')); ?></span>
                    </div>
                    <p class="text-muted mb-2" style="font-size:12px"><?php echo e($entry->description); ?></p>
                    <table class="table table-sm mb-0" style="font-size:12px">
                        <tbody>
                            <?php $__currentLoopData = $entry->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td style="width:30px" class="ps-2">
                                    <?php if($line->debit > 0): ?><span class="badge bg-primary">Dr</span>
                                    <?php else: ?><span class="badge bg-success">Cr</span><?php endif; ?>
                                </td>
                                <td><?php echo e($line->account->code ?? '-'); ?> - <?php echo e($line->account->name ?? '-'); ?></td>
                                <td class="text-end <?php echo e($line->debit>0?'text-primary':'text-success'); ?> fw-bold">
                                    Rp<?php echo e(number_format($line->debit > 0 ? $line->debit : $line->credit, 0, ',', '.')); ?>

                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-journal-x" style="font-size:32px;display:block;margin-bottom:8px"></i>
                    Belum ada jurnal penyesuaian
                </div>
                <?php endif; ?>
                <?php if($entries->hasPages()): ?>
                <div class="d-flex justify-content-center py-3"><?php echo e($entries->links()); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
var adjHints = {
    prepaid:      'Debit: Beban Operasional | Kredit: Aset (biaya dibayar dimuka diakui sebagai beban)',
    accrued:      'Debit: Beban Operasional | Kredit: Utang Usaha (beban sudah terjadi tapi belum dibayar)',
    depreciation: 'Debit: Beban Operasional | Kredit: Aset (penyusutan nilai aset tetap)',
    inventory:    'Debit: Beban Operasional | Kredit: Persediaan Barang (selisih stok fisik vs buku)',
    other:        'Pilih akun Debit & Kredit sesuai kebutuhan penyesuaian',
};
function setAdjTemplate(type) {
    document.getElementById('adjHint').textContent = adjHints[type] || '';
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/accounting/adjusting.blade.php ENDPATH**/ ?>