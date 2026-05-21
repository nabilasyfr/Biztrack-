# 🏪 BizTrack UMKM
**Smart Retail, Inventory & Accounting System for UMKM**

Sistem ERP mini berbasis web untuk toko kelontong / UMKM retail Indonesia.
Dibangun dengan **Laravel 11**, **Bootstrap 5**, dan **MySQL**.

---

## 🚀 Cara Install & Menjalankan

### Prasyarat
Pastikan sudah terinstall:
- **PHP** >= 8.2 (dengan extension: mbstring, xml, curl, zip, mysql, bcmath, pdo)
- **Composer** >= 2.x
- **MySQL** >= 8.0 (atau MariaDB >= 10.6)
- **Git** (opsional)

---

### Langkah 1 — Clone / Extract Project

```bash
# Jika dari zip, extract ke folder htdocs/www
# Masuk ke folder project
cd biztrack
```

---

### Langkah 2 — Install Dependencies

```bash
composer install
```

---

### Langkah 3 — Buat File .env

```bash
cp .env.example .env
php artisan key:generate
```

---

### Langkah 4 — Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biztrack_umkm
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

Buat database di MySQL:

```sql
CREATE DATABASE biztrack_umkm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

### Langkah 5 — Migrasi & Seed Data

```bash
php artisan migrate --seed
```

Perintah ini akan membuat semua tabel dan mengisi data demo:
- ✅ 2 akun user (owner & cashier)
- ✅ 22 produk kelontong demo
- ✅ 16 akun Chart of Accounts (CoA)

---

### Langkah 6 — Jalankan Server

```bash
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## 🔐 Akun Demo

| Role    | Email                   | Password |
|---------|-------------------------|----------|
| Owner   | owner@biztrack.com      | password |
| Cashier | cashier@biztrack.com    | password |

**Perbedaan akses:**
- **Owner** → Full access (semua modul termasuk keuangan & laporan)
- **Kasir** → Hanya POS, produk, dan riwayat penjualan

---

## 📋 Fitur Lengkap

### 🏠 Dashboard
- Total penjualan hari ini
- Omset & laba bulan ini
- Alert stok rendah
- Transaksi terbaru
- Produk terlaris

### 📦 Produk & Inventori
- CRUD produk lengkap
- Kode produk unik
- Harga beli & jual
- Manajemen stok minimum
- Log pergerakan inventori otomatis

### 🛒 POS / Kasir
- Pilih produk dengan grid interaktif
- Keranjang belanja real-time
- Cari & filter produk per kategori
- 4 metode pembayaran: **Tunai, Dana, QRIS, Transfer**
- Hitung kembalian otomatis
- Struk digital (dapat dicetak)

### 📊 Akuntansi (AIS Core)
- **Bagan Akun (CoA)** — Asset, Kewajiban, Modal, Pendapatan, Beban
- **Jurnal Umum** — Otomatis dibuat setiap transaksi penjualan & pengeluaran
- **Buku Besar** — Per akun, dengan filter tanggal
- Jurnal otomatis sesuai metode bayar:
  - Tunai → Debit Kas / Kredit Pendapatan
  - Dana → Debit Dana Wallet / Kredit Pendapatan
  - QRIS/Transfer → Debit Bank / Kredit Pendapatan

### 💸 Pengeluaran
- Catat pengeluaran operasional
- Jurnal akuntansi otomatis
- Filter per bulan

### 📈 Laporan
- **Laporan Penjualan** — Harian / Bulanan dengan breakdown profit
- **Laporan Inventori** — Semua stok, stok rendah, nilai inventori
- **Laporan Keuangan** — Laba-Rugi bulanan dengan grafik harian
- Semua laporan bisa **dicetak**

---

## 🗂️ Struktur Database

```
users           — Akun owner & kasir
products        — Data produk & harga
sales           — Header transaksi penjualan
sale_items      — Detail item per transaksi
inventory_logs  — Log pergerakan stok
accounts        — Chart of Accounts (CoA)
journal_entries — Header jurnal akuntansi
journal_lines   — Baris debit/kredit jurnal
expenses        — Pengeluaran operasional
```

---

## 🏗️ Struktur Project

```
app/
├── Http/
│   ├── Controllers/     — AuthController, DashboardController, dll
│   └── Middleware/      — AuthBiztrack, RoleOwner
├── Models/              — User, Product, Sale, Account, dll
└── Providers/           — AppServiceProvider

database/
├── migrations/          — Skema tabel
└── seeders/             — Data demo

resources/views/
├── layouts/             — app.blade.php (master layout)
├── auth/                — login.blade.php
├── dashboard/           — index.blade.php
├── products/            — CRUD + inventory log
├── sales/               — POS, index, show, receipt
├── accounting/          — coa, journal, ledger
├── expenses/            — CRUD
└── reports/             — sales, inventory, financial

routes/
└── web.php              — Semua route aplikasi
```

---

## ⚙️ Konfigurasi XAMPP / Laragon

### XAMPP
1. Letakkan folder `biztrack` di `C:\xampp\htdocs\`
2. Buka `http://localhost/biztrack/public`
3. Atau gunakan `php artisan serve`

### Laragon
1. Letakkan di `C:\laragon\www\biztrack`
2. Laragon otomatis buat virtual host: `http://biztrack.test`

---

## 🔧 Troubleshooting

**Error: Key not generated**
```bash
php artisan key:generate
```

**Error: Storage not writable**
```bash
chmod -R 775 storage bootstrap/cache
```

**Error: Class not found**
```bash
composer dump-autoload
```

**Session error di login**
```bash
php artisan config:clear
php artisan cache:clear
```

---

## 📝 Catatan Teknis

- Autentikasi menggunakan **session custom** (bukan Laravel Auth bawaan) agar sederhana
- Setiap penjualan **otomatis** membuat inventory log + jurnal akuntansi
- Setiap pengeluaran **otomatis** membuat jurnal akuntansi
- Sistem menggunakan **Bootstrap 5 CDN** (tidak perlu npm/vite)
- Pagination menggunakan **Bootstrap 5** style

---

## 🧑‍💻 Tech Stack

| Komponen     | Versi        |
|-------------|-------------|
| PHP          | >= 8.2      |
| Laravel      | 11.x        |
| MySQL        | >= 8.0      |
| Bootstrap    | 5.3.3       |
| Bootstrap Icons | 1.11.3   |
| Chart.js     | 4.4.0       |

---

**BizTrack UMKM** — Dibuat untuk membantu UMKM Indonesia berkembang 🇮🇩
