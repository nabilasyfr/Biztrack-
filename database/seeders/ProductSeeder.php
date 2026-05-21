<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Minuman
            ['code'=>'MNM001','name'=>'Aqua Botol 600ml','category'=>'Minuman','supplier'=>'PT Danone','selling_price'=>4000,'cost_price'=>2800,'stock'=>120,'min_stock'=>20],
            ['code'=>'MNM002','name'=>'Teh Botol Sosro 350ml','category'=>'Minuman','supplier'=>'PT Sosro','selling_price'=>5000,'cost_price'=>3500,'stock'=>96,'min_stock'=>15],
            ['code'=>'MNM003','name'=>'Indomilk UHT 250ml','category'=>'Minuman','supplier'=>'PT Indomilk','selling_price'=>5500,'cost_price'=>4000,'stock'=>48,'min_stock'=>10],
            ['code'=>'MNM004','name'=>'Coca Cola Kaleng 330ml','category'=>'Minuman','supplier'=>'PT Coca Cola','selling_price'=>8000,'cost_price'=>6000,'stock'=>60,'min_stock'=>12],
            ['code'=>'MNM005','name'=>'Pocari Sweat 500ml','category'=>'Minuman','supplier'=>'PT Amerta','selling_price'=>9000,'cost_price'=>7000,'stock'=>36,'min_stock'=>10],

            // Makanan
            ['code'=>'MKN001','name'=>'Indomie Goreng','category'=>'Makanan','supplier'=>'PT Indofood','selling_price'=>3500,'cost_price'=>2500,'stock'=>200,'min_stock'=>30],
            ['code'=>'MKN002','name'=>'Indomie Kuah','category'=>'Makanan','supplier'=>'PT Indofood','selling_price'=>3500,'cost_price'=>2500,'stock'=>180,'min_stock'=>30],
            ['code'=>'MKN003','name'=>'Mie Sedaap Goreng','category'=>'Makanan','supplier'=>'PT Wings','selling_price'=>3500,'cost_price'=>2500,'stock'=>150,'min_stock'=>25],
            ['code'=>'MKN004','name'=>'Beng-Beng','category'=>'Makanan','supplier'=>'PT Mayora','selling_price'=>5000,'cost_price'=>3500,'stock'=>72,'min_stock'=>15],
            ['code'=>'MKN005','name'=>'Chitato 68g','category'=>'Makanan','supplier'=>'PT Indofood','selling_price'=>10000,'cost_price'=>7500,'stock'=>48,'min_stock'=>10],
            ['code'=>'MKN006','name'=>'Roti Tawar Sari Roti','category'=>'Makanan','supplier'=>'PT Nippon Indosari','selling_price'=>18000,'cost_price'=>14000,'stock'=>20,'min_stock'=>5],

            // Kebutuhan Dapur
            ['code'=>'DPR001','name'=>'Gula Pasir 1kg','category'=>'Dapur','supplier'=>'UD Sembako Jaya','selling_price'=>14000,'cost_price'=>11500,'stock'=>50,'min_stock'=>10],
            ['code'=>'DPR002','name'=>'Minyak Goreng Bimoli 1L','category'=>'Dapur','supplier'=>'PT Salim','selling_price'=>20000,'cost_price'=>17000,'stock'=>40,'min_stock'=>8],
            ['code'=>'DPR003','name'=>'Beras Ramos 5kg','category'=>'Dapur','supplier'=>'UD Sembako Jaya','selling_price'=>68000,'cost_price'=>60000,'stock'=>30,'min_stock'=>5],
            ['code'=>'DPR004','name'=>'Tepung Terigu Segitiga 1kg','category'=>'Dapur','supplier'=>'PT Bogasari','selling_price'=>13000,'cost_price'=>10500,'stock'=>35,'min_stock'=>8],
            ['code'=>'DPR005','name'=>'Garam Refina 250g','category'=>'Dapur','supplier'=>'PT Garindo','selling_price'=>3500,'cost_price'=>2500,'stock'=>60,'min_stock'=>10],

            // Kebersihan
            ['code'=>'KBR001','name'=>'Sabun Lifebuoy 80g','category'=>'Kebersihan','supplier'=>'PT Unilever','selling_price'=>4500,'cost_price'=>3200,'stock'=>48,'min_stock'=>10],
            ['code'=>'KBR002','name'=>'Shampoo Sunsilk Sachet','category'=>'Kebersihan','supplier'=>'PT Unilever','selling_price'=>1500,'cost_price'=>900,'stock'=>100,'min_stock'=>20],
            ['code'=>'KBR003','name'=>'Rinso Detergen 800g','category'=>'Kebersihan','supplier'=>'PT Unilever','selling_price'=>22000,'cost_price'=>18000,'stock'=>24,'min_stock'=>6],
            ['code'=>'KBR004','name'=>'Softener Molto 900ml','category'=>'Kebersihan','supplier'=>'PT Unilever','selling_price'=>19000,'cost_price'=>15000,'stock'=>18,'min_stock'=>5],

            // Rokok (stok rendah untuk demo low stock alert)
            ['code'=>'RKK001','name'=>'Gudang Garam Merah 12','category'=>'Rokok','supplier'=>'PT Gudang Garam','selling_price'=>26000,'cost_price'=>23000,'stock'=>3,'min_stock'=>5],
            ['code'=>'RKK002','name'=>'Sampoerna Mild 16','category'=>'Rokok','supplier'=>'PT Sampoerna','selling_price'=>34000,'cost_price'=>30000,'stock'=>4,'min_stock'=>5],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
