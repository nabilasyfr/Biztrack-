<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            // Assets
            ['code' => '1000', 'name' => 'Aset',            'type' => 'asset',   'description' => 'Akun induk aset'],
            ['code' => '1100', 'name' => 'Kas',              'type' => 'asset',   'description' => 'Uang tunai di tangan'],
            ['code' => '1110', 'name' => 'Dana Wallet',      'type' => 'asset',   'description' => 'Saldo dompet digital Dana'],
            ['code' => '1120', 'name' => 'Rekening Bank',    'type' => 'asset',   'description' => 'Saldo rekening bank (QRIS/Transfer)'],
            ['code' => '1200', 'name' => 'Persediaan Barang','type' => 'asset',   'description' => 'Nilai stok barang dagangan'],

            // Liabilities
            ['code' => '2000', 'name' => 'Kewajiban',        'type' => 'liability','description' => 'Akun induk kewajiban'],
            ['code' => '2100', 'name' => 'Utang Usaha',      'type' => 'liability','description' => 'Utang kepada supplier'],

            // Equity
            ['code' => '3000', 'name' => 'Modal',            'type' => 'equity',  'description' => 'Modal pemilik'],
            ['code' => '3100', 'name' => 'Modal Pemilik',    'type' => 'equity',  'description' => 'Investasi pemilik'],

            // Revenue
            ['code' => '4000', 'name' => 'Pendapatan',       'type' => 'revenue', 'description' => 'Akun induk pendapatan'],
            ['code' => '4100', 'name' => 'Pendapatan Penjualan', 'type' => 'revenue', 'description' => 'Pendapatan dari penjualan barang'],

            // Expenses
            ['code' => '5000', 'name' => 'Beban Operasional','type' => 'expense', 'description' => 'Beban operasional usaha'],
            ['code' => '5100', 'name' => 'Beban Gaji',       'type' => 'expense', 'description' => 'Beban gaji karyawan'],
            ['code' => '5200', 'name' => 'Beban Sewa',       'type' => 'expense', 'description' => 'Beban sewa tempat usaha'],
            ['code' => '5300', 'name' => 'Beban Listrik',    'type' => 'expense', 'description' => 'Beban tagihan listrik'],
            ['code' => '5400', 'name' => 'Beban Lainnya',    'type' => 'expense', 'description' => 'Beban operasional lainnya'],
        ];

        foreach ($accounts as $account) {
            Account::create($account);
        }
    }
}
