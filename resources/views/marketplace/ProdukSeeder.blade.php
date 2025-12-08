<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produk = [
            [
                'id_produk' => 'PRD-0001',
                'id_user' => 'USR001', // Sesuaikan dengan user ID yang ada
                'nama_produk' => 'Monstera Deliciosa Variegata',
                'kategori' => 'Tanaman_hias',
                'deskripsi' => 'Tanaman hias indoor premium dengan variegata putih. Sangat cocok untuk dekorasi ruangan. Ukuran pot 20cm, tinggi tanaman 40-50cm. Perawatan mudah, cukup siram 2-3 kali seminggu.',
                'harga' => 450000,
                'stok' => 15,
                'nama_penjual' => 'Green Paradise Store',
                'kontak_penjual' => '081234567890',
                'lokasi_penjual' => 'Jakarta Selatan',
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PRD-0002',
                'id_user' => 'USR002',
                'nama_produk' => 'Bibit Mangga Gedong Gincu',
                'kategori' => 'buah',
                'deskripsi' => 'Bibit mangga unggul varietas Gedong Gincu. Sudah berumur 8 bulan, tinggi 60cm. Siap tanam di lahan atau pot besar. Buah manis dan harum khas Gedong Gincu.',
                'harga' => 175000,
                'stok' => 25,
                'nama_penjual' => 'Tani Makmur Nursery',
                'kontak_penjual' => '082345678901',
                'lokasi_penjual' => 'Bogor',
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PRD-0003',
                'id_user' => 'USR003',
                'nama_produk' => 'Benih Cabai Rawit Hibrida',
                'kategori' => 'sayur',
                'deskripsi' => 'Benih cabai rawit hibrida produktif. 1 pack isi 50 benih. Masa panen 75-85 hari. Tahan penyakit, cocok untuk budidaya organik. Tingkat kepedasan tinggi.',
                'harga' => 35000,
                'stok' => 100,
                'nama_penjual' => 'Berkebun Organik',
                'kontak_penjual' => '083456789012',
                'lokasi_penjual' => 'Bandung',
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PRD-0004',
                'id_user' => 'USR001',
                'nama_produk' => 'Aglonema Red Siam Aurora',
                'kategori' => 'Tanaman_hias',
                'deskripsi' => 'Aglonema premium dengan warna merah menyala. Tanaman indoor yang tahan banting dan mudah perawatan. Ukuran pot 15cm, tinggi 30-35cm. Cocok untuk meja kantor atau rumah.',
                'harga' => 125000,
                'stok' => 30,
                'nama_penjual' => 'Green Paradise Store',
                'kontak_penjual' => '081234567890',
                'lokasi_penjual' => 'Jakarta Selatan',
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PRD-0005',
                'id_user' => 'USR004',
                'nama_produk' => 'Bibit Jeruk Santang Madu',
                'kategori' => 'buah',
                'deskripsi' => 'Bibit jeruk santang varietas madu. Umur bibit 1 tahun, tinggi 70cm. Buah sangat manis tanpa biji. Cocok untuk tabulampot atau ditanam di lahan.',
                'harga' => 95000,
                'stok' => 40,
                'nama_penjual' => 'Buah Nusantara',
                'kontak_penjual' => '084567890123',
                'lokasi_penjual' => 'Pontianak',
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PRD-0006',
                'id_user' => 'USR003',
                'nama_produk' => 'Bibit Tomat Cherry Organik',
                'kategori' => 'sayur',
                'deskripsi' => 'Bibit tomat cherry siap tanam. Tinggi 20cm, sudah mulai berbunga. Tanpa pestisida kimia. Produktif dan tahan penyakit. Cocok untuk pot atau polybag.',
                'harga' => 25000,
                'stok' => 75,
                'nama_penjual' => 'Berkebun Organik',
                'kontak_penjual' => '083456789012',
                'lokasi_penjual' => 'Bandung',
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PRD-0007',
                'id_user' => 'USR005',
                'nama_produk' => 'Sansevieria Black Gold',
                'kategori' => 'Tanaman_hias',
                'deskripsi' => 'Lidah mertua varietas Black Gold. Warna hitam keemasan yang eksotis. Tanaman pembersih udara terbaik. Pot diameter 12cm, tinggi tanaman 25-30cm.',
                'harga' => 85000,
                'stok' => 50,
                'nama_penjual' => 'Flora Indah Garden',
                'kontak_penjual' => '085678901234',
                'lokasi_penjual' => 'Surabaya',
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PRD-0008',
                'id_user' => 'USR002',
                'nama_produk' => 'Bibit Alpukat Mentega',
                'kategori' => 'buah',
                'deskripsi' => 'Bibit alpukat mentega unggul. Umur 10 bulan, tinggi 80cm. Cepat berbuah, produktif. Daging buah tebal dan pulen seperti mentega. Sudah dicangkok dari pohon produktif.',
                'harga' => 150000,
                'stok' => 20,
                'nama_penjual' => 'Tani Makmur Nursery',
                'kontak_penjual' => '082345678901',
                'lokasi_penjual' => 'Bogor',
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PRD-0009',
                'id_user' => 'USR003',
                'nama_produk' => 'Benih Kangkung Organik',
                'kategori' => 'sayur',
                'deskripsi' => 'Benih kangkung organik non-GMO. 1 pack isi 100 benih. Panen cepat 25-30 hari. Cocok untuk hidroponik atau tanah. Tumbuh subur dan daun lebar.',
                'harga' => 15000,
                'stok' => 150,
                'nama_penjual' => 'Berkebun Organik',
                'kontak_penjual' => '083456789012',
                'lokasi_penjual' => 'Bandung',
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PRD-0010',
                'id_user' => 'USR006',
                'nama_produk' => 'Philodendron Pink Princess',
                'kategori' => 'Tanaman_hias',
                'deskripsi' => 'Philodendron langka dengan variegata pink. Tanaman hias kolektor dengan harga terjangkau. Pot 12cm, 3-4 daun. Perawatan mudah, tahan indoor.',
                'harga' => 350000,
                'stok' => 8,
                'nama_penjual' => 'Rare Plants Indonesia',
                'kontak_penjual' => '086789012345',
                'lokasi_penjual' => 'Jakarta Barat',
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PRD-0011',
                'id_user' => 'USR004',
                'nama_produk' => 'Bibit Durian Musang King',
                'kategori' => 'buah',
                'deskripsi' => 'Bibit durian Musang King premium. Umur 1,5 tahun, tinggi 1 meter. Hasil okulasi dari pohon induk produktif. Buah berdaging tebal, manis legit khas Musang King.',
                'harga' => 275000,
                'stok' => 12,
                'nama_penjual' => 'Buah Nusantara',
                'kontak_penjual' => '084567890123',
                'lokasi_penjual' => 'Pontianak',
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PRD-0012',
                'id_user' => 'USR003',
                'nama_produk' => 'Bibit Sawi Hijau Organik',
                'kategori' => 'sayur',
                'deskripsi' => 'Bibit sawi hijau siap tanam. Tinggi 15cm, sehat dan bebas hama. Ditanam secara organik tanpa pestisida. Panen dalam 20-25 hari. Cocok untuk pemula.',
                'harga' => 18000,
                'stok' => 90,
                'nama_penjual' => 'Berkebun Organik',
                'kontak_penjual' => '083456789012',
                'lokasi_penjual' => 'Bandung',
                'status' => 'aktif',
            ],
        ];

        foreach ($produk as $item) {
            DB::table('produk')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
        
        $this->command->info('✅ Seeded ' . count($produk) . ' produk successfully!');
    }
}