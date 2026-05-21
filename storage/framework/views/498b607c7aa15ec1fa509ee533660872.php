<?php $__env->startSection('title','Kasir / POS'); ?>
<?php $__env->startSection('page-title','Kasir / Point of Sale'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    #page-content { padding: 16px; }
    .pos-layout { display: grid; grid-template-columns: 1fr 380px; gap: 16px; min-height: calc(100vh - 110px); }
    @media(max-width:991px){ .pos-layout { grid-template-columns:1fr; } }

    /* Product grid */
    .product-search-bar { margin-bottom: 12px; }
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; }
    .product-card {
        background: #fff; border-radius: 12px; padding: 12px;
        cursor: pointer; border: 2px solid transparent;
        transition: all .2s; box-shadow: 0 1px 3px rgba(0,0,0,.06);
        user-select: none;
    }
    .product-card:hover { border-color: #1a56db; box-shadow: 0 4px 12px rgba(26,86,219,.15); }
    .product-card.out-of-stock { opacity: .5; cursor: not-allowed; }
    .product-card .p-name { font-size: 13px; font-weight: 600; color: #0f172a; line-height: 1.3; }
    .product-card .p-price { font-size: 14px; font-weight: 800; color: #1a56db; margin-top: 6px; }
    .product-card .p-stock { font-size: 11px; color: #94a3b8; }
    .product-card .p-cat { font-size: 10px; background: #f1f5f9; color: #64748b; border-radius: 4px; padding: 1px 6px; display: inline-block; }

    /* Cart */
    .cart-panel {
        background: #fff; border-radius: 16px;
        display: flex; flex-direction: column;
        box-shadow: 0 1px 3px rgba(0,0,0,.08);
        height: fit-content; position: sticky; top: 80px;
    }
    .cart-header { padding: 16px 20px; border-bottom: 1px solid #f1f5f9; font-weight: 700; font-size: 15px; }
    .cart-items { flex: 1; max-height: 300px; overflow-y: auto; padding: 8px 0; }
    .cart-item { display: flex; align-items: center; gap: 10px; padding: 10px 16px; border-bottom: 1px solid #f8fafc; }
    .cart-item-name { flex: 1; font-size: 13px; font-weight: 600; }
    .cart-item-price { font-size: 12px; color: #64748b; }
    .qty-btn { width: 28px; height: 28px; border-radius: 8px; border: 1.5px solid #e2e8f0; background: #f8fafc; cursor: pointer; font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center; }
    .qty-btn:hover { background: #e2e8f0; }
    .qty-num { width: 32px; text-align: center; font-weight: 700; font-size: 14px; }
    .cart-footer { padding: 16px 20px; border-top: 1px solid #f1f5f9; }
    .cart-total { font-size: 22px; font-weight: 800; color: #0f172a; }
    .empty-cart { text-align: center; color: #94a3b8; padding: 32px 20px; }
    .payment-btn { padding: 12px 8px; border-radius: 10px; border: 2px solid #e2e8f0; cursor: pointer; text-align: center; transition: all .2s; background: #fff; }
    .payment-btn:hover, .payment-btn.selected { border-color: #1a56db; background: #eff6ff; }
    .payment-btn .pm-icon { font-size: 20px; display: block; }
    .payment-btn .pm-label { font-size: 11px; font-weight: 700; color: #374151; }

    /* Modal */
    .receipt-modal .modal-content { border-radius: 16px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="pos-layout">
    
    <div>
        <div class="product-search-bar">
            <input type="text" id="searchProduct" class="form-control"
                   placeholder="&#xF52A; Cari produk..." style="font-size:14px; border-radius:12px; padding:12px 16px;">
        </div>

        
        <div class="d-flex gap-2 flex-wrap mb-3" id="catFilters">
            <button class="btn btn-sm btn-primary cat-btn active" data-cat="">Semua</button>
            <?php $cats = $products->pluck('category')->unique()->sort()->values(); ?>
            <?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button class="btn btn-sm btn-outline-secondary cat-btn" data-cat="<?php echo e($cat); ?>"><?php echo e($cat); ?></button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="product-grid" id="productGrid">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="product-card <?php echo e($product->stock <= 0 ? 'out-of-stock' : ''); ?>"
                 onclick="addToCart(<?php echo e($product->id); ?>, '<?php echo e(addslashes($product->name)); ?>', <?php echo e($product->selling_price); ?>, <?php echo e($product->stock); ?>)"
                 data-name="<?php echo e(strtolower($product->name)); ?>"
                 data-cat="<?php echo e($product->category); ?>">
                <span class="p-cat"><?php echo e($product->category); ?></span>
                <div class="p-name mt-1"><?php echo e($product->name); ?></div>
                <div class="p-price">Rp<?php echo e(number_format($product->selling_price,0,',','.')); ?></div>
                <div class="p-stock">Stok: <strong id="stock-<?php echo e($product->id); ?>"><?php echo e($product->stock); ?></strong></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="cart-panel">
        <div class="cart-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-cart3 me-2"></i>Keranjang</span>
            <button class="btn btn-sm btn-outline-danger" onclick="clearCart()">
                <i class="bi bi-trash"></i>
            </button>
        </div>

        <div class="cart-items" id="cartItems">
            <div class="empty-cart" id="emptyCart">
                <i class="bi bi-cart-x" style="font-size:36px; display:block; margin-bottom:8px;"></i>
                Keranjang kosong.<br>Pilih produk untuk memulai.
            </div>
        </div>

        <div class="cart-footer">
            
            <div class="mb-3">
                <label class="form-label">Metode Pembayaran</label>
                <div class="row g-2" id="paymentMethods">
                    <div class="col-3">
                        <div class="payment-btn selected" data-pm="cash" onclick="selectPayment('cash', this)">
                            <span class="pm-icon">💵</span>
                            <span class="pm-label">Tunai</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="payment-btn" data-pm="dana" onclick="selectPayment('dana', this)">
                            <span class="pm-icon">💙</span>
                            <span class="pm-label">Dana</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="payment-btn" data-pm="qris" onclick="selectPayment('qris', this)">
                            <span class="pm-icon">📱</span>
                            <span class="pm-label">QRIS</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="payment-btn" data-pm="transfer" onclick="selectPayment('transfer', this)">
                            <span class="pm-icon">🏦</span>
                            <span class="pm-label">Transfer</span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div id="cashRow" class="mb-3">
                <label class="form-label">Uang Diterima</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" id="cashReceived" class="form-control" placeholder="0" min="0" step="500"
                           oninput="calcChange()">
                </div>
                <div id="changeDisplay" class="mt-1" style="font-size:13px; display:none;">
                    Kembalian: <strong id="changeAmount" class="text-success"></strong>
                </div>
            </div>

            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-semibold text-muted">TOTAL</span>
                <span class="cart-total" id="cartTotal">Rp 0</span>
            </div>

            <button class="btn btn-success w-100" style="padding:14px; font-size:15px; border-radius:12px; font-weight:700;"
                    onclick="checkout()" id="checkoutBtn" disabled>
                <i class="bi bi-check-circle me-2"></i>Proses Pembayaran
            </button>
        </div>
    </div>
</div>


<div class="modal fade receipt-modal" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <div style="font-size:56px; margin-bottom:8px;">✅</div>
            <h4 class="fw-bold mb-1">Pembayaran Berhasil!</h4>
            <p class="text-muted mb-2" id="successInvoice"></p>
            <div class="bg-light rounded-3 p-3 mb-3 text-start">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Total</span>
                    <strong id="successTotal"></strong>
                </div>
                <div class="d-flex justify-content-between mb-1" id="successChangeRow">
                    <span class="text-muted">Kembalian</span>
                    <strong class="text-success" id="successChange"></strong>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a id="receiptLink" href="#" target="_blank" class="btn btn-outline-primary flex-fill">
                    <i class="bi bi-printer me-1"></i>Cetak Struk
                </a>
                <button class="btn btn-success flex-fill" onclick="closeSuccess()">
                    <i class="bi bi-plus-circle me-1"></i>Transaksi Baru
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ─── State ───────────────────────────────────────────────
let cart           = {};  // { id: { id, name, price, qty, maxStock } }
let selectedPayment = 'cash';
let currentTotal   = 0;

// ─── Tambah produk ke keranjang ──────────────────────────
function addToCart(id, name, price, stock) {
    if (stock <= 0) return;
    var inCart = cart[id] ? cart[id].qty : 0;
    if (inCart >= stock) {
        alert('Stok tidak cukup! Stok tersedia: ' + stock);
        return;
    }
    if (cart[id]) {
        cart[id].qty++;
    } else {
        cart[id] = { id: id, name: name, price: price, qty: 1, maxStock: stock };
    }
    renderCart();
}

// ─── Ubah qty dari tombol +/- ────────────────────────────
function updateQty(id, delta) {
    id = parseInt(id);
    if (!cart[id]) return;
    cart[id].qty += delta;
    if (cart[id].qty <= 0) {
        delete cart[id];
    }
    renderCart();
}

// ─── Render ulang keranjang (tanpa referensi DOM lama) ───
function renderCart() {
    var container   = document.getElementById('cartItems');
    var checkoutBtn = document.getElementById('checkoutBtn');
    var keys        = Object.keys(cart);

    // Keranjang kosong
    if (keys.length === 0) {
        container.innerHTML =
            '<div style="text-align:center;color:#94a3b8;padding:32px 20px;">' +
            '<i class="bi bi-cart-x" style="font-size:36px;display:block;margin-bottom:8px;"></i>' +
            'Keranjang kosong.<br>Pilih produk untuk memulai.' +
            '</div>';
        currentTotal = 0;
        document.getElementById('cartTotal').textContent = 'Rp 0';
        document.getElementById('changeDisplay').style.display = 'none';
        checkoutBtn.disabled = true;
        return;
    }

    // Bangun HTML semua item sekaligus
    currentTotal = 0;
    var html = '';
    for (var i = 0; i < keys.length; i++) {
        var item     = cart[keys[i]];
        var subtotal = item.price * item.qty;
        currentTotal += subtotal;
        var plusDisabled = item.qty >= item.maxStock ? ' disabled' : '';
        html +=
            '<div class="cart-item">' +
              '<div class="flex-fill">' +
                '<div class="cart-item-name">' + escHtml(item.name) + '</div>' +
                '<div class="cart-item-price">Rp' + fmtNum(item.price) +
                  ' &times; ' + item.qty +
                  ' = <strong>Rp' + fmtNum(subtotal) + '</strong></div>' +
              '</div>' +
              '<div class="d-flex align-items-center gap-1">' +
                '<button class="qty-btn" type="button" onclick="updateQty(' + item.id + ',-1)">&#8722;</button>' +
                '<span class="qty-num">' + item.qty + '</span>' +
                '<button class="qty-btn" type="button" onclick="updateQty(' + item.id + ',1)"' + plusDisabled + '>+</button>' +
              '</div>' +
            '</div>';
    }

    container.innerHTML = html;
    document.getElementById('cartTotal').textContent = 'Rp ' + fmtNum(currentTotal);
    checkoutBtn.disabled = false;
    calcChange();
}

// ─── Format angka ribuan ─────────────────────────────────
function fmtNum(n) {
    return Number(n).toLocaleString('id-ID');
}

// ─── Escape HTML ─────────────────────────────────────────
function escHtml(s) {
    return String(s)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;');
}

// ─── Kosongkan keranjang ──────────────────────────────────
function clearCart() {
    if (Object.keys(cart).length === 0) return;
    if (confirm('Kosongkan keranjang?')) {
        cart = {};
        renderCart();
    }
}

// ─── Pilih metode pembayaran ──────────────────────────────
function selectPayment(pm, el) {
    selectedPayment = pm;
    document.querySelectorAll('.payment-btn').forEach(function(b) {
        b.classList.remove('selected');
    });
    el.classList.add('selected');
    var cashRow = document.getElementById('cashRow');
    cashRow.style.display = (pm === 'cash') ? 'block' : 'none';
    if (pm !== 'cash') {
        document.getElementById('changeDisplay').style.display = 'none';
        document.getElementById('cashReceived').value = '';
    }
}

// ─── Hitung kembalian real-time ───────────────────────────
function calcChange() {
    if (selectedPayment !== 'cash') return;
    var val      = document.getElementById('cashReceived').value.trim();
    var received = parseFloat(val);
    var disp     = document.getElementById('changeDisplay');

    if (val !== '' && !isNaN(received) && received > 0 && currentTotal > 0) {
        var change = received - currentTotal;
        disp.style.display = 'block';
        document.getElementById('changeAmount').textContent =
            'Rp ' + fmtNum(Math.max(0, change));
        document.getElementById('changeAmount').style.color =
            change < 0 ? '#dc2626' : '#16a34a';
    } else {
        disp.style.display = 'none';
    }
}

// ─── Checkout ─────────────────────────────────────────────
async function checkout() {
    if (Object.keys(cart).length === 0) return;

    var cashReceived = currentTotal; // default untuk non-tunai

    // Validasi khusus tunai
    if (selectedPayment === 'cash') {
        var rawVal = document.getElementById('cashReceived').value.trim();
        cashReceived = parseFloat(rawVal);

        if (rawVal === '' || isNaN(cashReceived) || cashReceived <= 0) {
            alert('Masukkan jumlah uang yang diterima terlebih dahulu!');
            document.getElementById('cashReceived').focus();
            return;
        }
        if (cashReceived < currentTotal) {
            alert(
                'Uang yang diterima KURANG!\n\n' +
                'Total belanja  : Rp ' + fmtNum(currentTotal) + '\n' +
                'Uang diterima  : Rp ' + fmtNum(cashReceived) + '\n' +
                'Kekurangan     : Rp ' + fmtNum(currentTotal - cashReceived)
            );
            document.getElementById('cashReceived').focus();
            return;
        }
    }

    var items = Object.values(cart).map(function(i) {
        return { id: i.id, qty: i.qty };
    });

    var btn = document.getElementById('checkoutBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

    try {
        var res = await fetch('/pos/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
            body: JSON.stringify({
                items:          items,
                payment_method: selectedPayment,
                cash_received:  cashReceived,
            })
        });

        var data = await res.json();

        if (data.success) {
            document.getElementById('successInvoice').textContent  = data.invoice_number;
            document.getElementById('successTotal').textContent    = 'Rp ' + fmtNum(data.total);
            document.getElementById('successChange').textContent   = 'Rp ' + fmtNum(data.change);
            document.getElementById('successChangeRow').style.display =
                selectedPayment === 'cash' ? 'flex' : 'none';
            document.getElementById('receiptLink').href = '/sales/' + data.sale_id + '/receipt';

            new bootstrap.Modal(document.getElementById('successModal')).show();

            // Reset keranjang & form
            cart = {};
            renderCart();
            document.getElementById('cashReceived').value = '';
            document.getElementById('changeDisplay').style.display = 'none';
        } else {
            alert('Gagal: ' + data.message);
        }
    } catch (e) {
        alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
        console.error(e);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Proses Pembayaran';
    }
}

// ─── Tutup modal sukses ───────────────────────────────────
function closeSuccess() {
    var modal = bootstrap.Modal.getInstance(document.getElementById('successModal'));
    if (modal) modal.hide();
}

// ─── Pencarian produk ─────────────────────────────────────
document.getElementById('searchProduct').addEventListener('input', function() {
    filterProducts(this.value.toLowerCase(), activeCat);
});

// ─── Filter kategori ─────────────────────────────────────
var activeCat = '';
document.querySelectorAll('.cat-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.cat-btn').forEach(function(b) {
            b.classList.remove('active', 'btn-primary');
            b.classList.add('btn-outline-secondary');
        });
        this.classList.remove('btn-outline-secondary');
        this.classList.add('active', 'btn-primary');
        activeCat = this.dataset.cat;
        filterProducts(
            document.getElementById('searchProduct').value.toLowerCase(),
            activeCat
        );
    });
});

function filterProducts(q, cat) {
    document.querySelectorAll('.product-card').forEach(function(card) {
        var matchQ   = !q   || (card.dataset.name || '').includes(q);
        var matchCat = !cat || card.dataset.cat === cat;
        card.style.display = (matchQ && matchCat) ? '' : 'none';
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/sales/pos.blade.php ENDPATH**/ ?>