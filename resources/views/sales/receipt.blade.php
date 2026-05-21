<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $sale->invoice_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', monospace;
            background: #f1f5f9;
            display: flex; align-items: flex-start; justify-content: center;
            padding: 20px;
        }
        .receipt {
            background: #fff;
            width: 300px;
            padding: 20px 16px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,.1);
        }
        .receipt-header { text-align: center; margin-bottom: 12px; }
        .store-name { font-size: 18px; font-weight: 800; }
        .store-sub { font-size: 11px; color: #64748b; }
        .divider { border: none; border-top: 1px dashed #cbd5e1; margin: 10px 0; }
        .info-row { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px; }
        .info-row .label { color: #64748b; }
        .item-row { display: flex; align-items: flex-start; font-size: 12px; margin-bottom: 8px; }
        .item-name { flex: 1; }
        .item-price { text-align: right; white-space: nowrap; }
        .item-detail { color: #64748b; font-size: 11px; }
        .total-row { display: flex; justify-content: space-between; font-size: 15px; font-weight: 800; margin: 8px 0 4px; }
        .change-row { display: flex; justify-content: space-between; font-size: 13px; color: #16a34a; font-weight: 700; }
        .footer { text-align: center; font-size: 11px; color: #94a3b8; margin-top: 12px; }
        .payment-badge {
            display: inline-block;
            padding: 2px 10px;
            background: #eff6ff;
            color: #1a56db;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
        @media print {
            body { background: none; padding: 0; }
            .receipt { box-shadow: none; border-radius: 0; width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div>
        <div class="receipt">
            <div class="receipt-header">
                <div class="store-name">🏪 BizTrack UMKM</div>
                <div class="store-sub">Toko Kelontong Sejahtera</div>
                <div class="store-sub">Jl. Contoh No. 1, Kota Anda</div>
            </div>

            <hr class="divider">

            <div class="info-row"><span class="label">No. Invoice</span><strong>{{ $sale->invoice_number }}</strong></div>
            <div class="info-row"><span class="label">Tanggal</span><span>{{ $sale->created_at->format('d/m/Y H:i') }}</span></div>
            <div class="info-row"><span class="label">Kasir</span><span>{{ $sale->user->name ?? '-' }}</span></div>
            <div class="info-row"><span class="label">Pembayaran</span><span class="payment-badge">{{ strtoupper($sale->payment_method) }}</span></div>

            <hr class="divider">

            @foreach($sale->items as $item)
            <div class="item-row">
                <div class="item-name">
                    <div>{{ $item->product->name ?? 'Produk' }}</div>
                    <div class="item-detail">{{ $item->quantity }} × Rp{{ number_format($item->unit_price,0,',','.') }}</div>
                </div>
                <div class="item-price">Rp{{ number_format($item->subtotal,0,',','.') }}</div>
            </div>
            @endforeach

            <hr class="divider">

            <div class="total-row">
                <span>TOTAL</span>
                <span>Rp{{ number_format($sale->total_amount,0,',','.') }}</span>
            </div>
            @if($sale->payment_method === 'cash')
            <div class="info-row"><span class="label">Diterima</span><span>Rp{{ number_format($sale->cash_received,0,',','.') }}</span></div>
            <div class="change-row"><span>Kembalian</span><span>Rp{{ number_format($sale->change_amount,0,',','.') }}</span></div>
            @endif

            <hr class="divider">

            <div class="footer">
                <p>Terima kasih telah berbelanja!</p>
                <p>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
                <p style="margin-top:8px; font-size:10px;">Powered by BizTrack UMKM</p>
            </div>
        </div>

        <div class="no-print text-center mt-3">
            <button onclick="window.print()" style="padding:10px 24px; background:#1a56db; color:#fff; border:none; border-radius:8px; font-weight:700; cursor:pointer; font-size:14px;">
                🖨️ Cetak Struk
            </button>
            <button onclick="window.close()" style="padding:10px 24px; background:#64748b; color:#fff; border:none; border-radius:8px; font-weight:700; cursor:pointer; font-size:14px; margin-left:8px;">
                Tutup
            </button>
        </div>
    </div>
</body>
</html>
