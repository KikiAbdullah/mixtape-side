<?php

use App\Models\Customer;
use App\Models\FotoMobil;
use App\Models\KategoriService;
use App\Models\Maintenance;
use App\Models\MetodePembayaran;
use App\Models\Mobil;
use App\Models\Payment;
use App\Models\Pemesanan;
use App\Models\Queue;
use App\Models\QueueService;
use App\Models\Rental;
use App\Models\Service;
use App\Models\Ulasan;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::insert([
            ['name' => 'Budi Santoso', 'nomor_hp' => '081234567890'],
            ['name' => 'Rina Andayani', 'nomor_hp' => '082112345678'],
            ['name' => 'Agus Wijaya', 'nomor_hp' => '081356789012'],
        ]);

        KategoriService::insert([
            ['name' => 'Cuci Mobil'],
            ['name' => 'Perawatan Interior'],
            ['name' => 'Detailing'],
        ]);

        $kategori = KategoriService::pluck('id', 'name');

        $services = [
            [
                'name' => 'Cuci Eksterior',
                'kategori_id' => $kategori['Cuci Mobil'],
                'harga' => 30000,
                'estimasi_waktu' => 15,
                'text' => 'Cuci bagian luar mobil',
                'is_active' => 1,
                'created_by' => 1,
            ],
            [
                'name' => 'Cuci Lengkap + Vakum',
                'kategori_id' => $kategori['Cuci Mobil'],
                'harga' => 50000,
                'estimasi_waktu' => 25,
                'text' => 'Termasuk interior & vakum',
                'is_active' => 1,
                'created_by' => 1,
            ],
            [
                'name' => 'Salon Mobil Ringan',
                'kategori_id' => $kategori['Detailing'],
                'harga' => 150000,
                'estimasi_waktu' => 60,
                'text' => 'Poles dan pembersihan menyeluruh',
                'is_active' => 1,
                'created_by' => 1,
            ],
        ];

        Service::insert($services);

        $mobils = [
            [
                'name' => 'Toyota Avanza',
                'nomor_plat' => 'N 1234 AB',
                'merk' => 'Toyota',
                'tipe' => 'MPV',
                'tahun' => '2022',
                'kapasitas' => 7,
                'transmisi' => 'Automatic',
                'fitur' => 'AC, Audio, Safety Airbag',
                'text' => 'Kondisi sangat baik',
                'harga_sewa_per_hari' => 350000,
                'status' => 'tersedia',
                'created_by' => 1,
            ],
            [
                'name' => 'Honda Brio',
                'nomor_plat' => 'N 5678 CD',
                'merk' => 'Honda',
                'tipe' => 'Hatchback',
                'tahun' => '2021',
                'kapasitas' => 5,
                'transmisi' => 'Manual',
                'fitur' => 'AC, Bluetooth',
                'text' => 'Efisien dan nyaman',
                'harga_sewa_per_hari' => 250000,
                'status' => 'tersedia',
                'created_by' => 1,
            ],
        ];

        Mobil::insert($mobils);

        FotoMobil::insert([
            ['mobil_id' => 1, 'url' => 'https://d1g6w7sntckt92.cloudfront.net/public/images/car_gallery_images/vzDbum6Xs4Ib8gmmVus7nahli4V3QSjkMdPxWfUY.jpg'],
            ['mobil_id' => 1, 'url' => 'https://d1g6w7sntckt92.cloudfront.net/public/images/car_gallery_images/MaRMywp78VK8Ag6YzHZzvLnTwTVl7Emj2byRFHfr.jpg'],
            ['mobil_id' => 2, 'url' => 'https://asset.honda-indonesia.com/colors/euqS00voL7Bf85GtPK8vkjFV1PzmzlGIMBKlOswi.png'],
        ]);

        Queue::insert([
            [
                'customer_id' => 1,
                'no' => 'A001',
                'status' => 'waiting',
                'payment_status' => 'unpaid',
                'total_harga' => 0,
                'created_by' => 1,
            ],
            [
                'customer_id' => 2,
                'no' => 'A002',
                'status' => 'washing',
                'payment_status' => 'unpaid',
                'total_harga' => 0,
                'created_by' => 1,
            ],
        ]);

        $queues = Queue::all();
        $service = Service::first();

        foreach ($queues as $queue) {
            QueueService::create([
                'queue_id' => $queue->id,
                'service_id' => $service->id,
                'harga' => $service->harga,
            ]);

            $queue->update(['total_harga' => $service->harga]);
        }

        Rental::insert([
            [
                'customer_id' => 3,
                'mobil_id' => 1,
                'no' => 'RNT-001',
                'start_at' => now()->subDays(2),
                'end_at' => now()->addDays(3),
                'total_harga' => 1750000,
                'deposit' => 500000,
                'status' => 'ongoing',
                'text' => 'Disewa untuk liburan keluarga',
                'created_by' => 1,
            ],
        ]);

        Payment::insert([
            [
                'reference_type' => 'queue',
                'reference_id' => 1,
                'total' => 30000,
                'method' => 'cash',
                'status' => 'paid',
                'created_by' => 1,
            ],
            [
                'reference_type' => 'rental',
                'reference_id' => 1,
                'total' => 1750000,
                'method' => 'transfer',
                'status' => 'paid',
                'created_by' => 1,
            ],
        ]);
    }
}
