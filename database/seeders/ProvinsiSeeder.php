<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('provinsis')->insert([
            ['kode_provinsi' => '10', 'nama_provinsi' => 'Aceh'],
            ['kode_provinsi' => '11', 'nama_provinsi' => 'Sumatera Utara'],
            ['kode_provinsi' => '12', 'nama_provinsi' => 'Sumatera Barat'],
            ['kode_provinsi' => '13', 'nama_provinsi' => 'Riau'],
            ['kode_provinsi' => '14', 'nama_provinsi' => 'Kepulauan Riau'],
            ['kode_provinsi' => '15', 'nama_provinsi' => 'Jambi'],
            ['kode_provinsi' => '16', 'nama_provinsi' => 'Bengkulu'],
            ['kode_provinsi' => '17', 'nama_provinsi' => 'Sumatera Selatan'],
            ['kode_provinsi' => '18', 'nama_provinsi' => 'Bangka Belitung'],
            ['kode_provinsi' => '19', 'nama_provinsi' => 'Lampung'],
            ['kode_provinsi' => '20', 'nama_provinsi' => 'Banten'],
            ['kode_provinsi' => '21', 'nama_provinsi' => 'Jawa Barat'],
            ['kode_provinsi' => '22', 'nama_provinsi' => 'Jakarta'],
            ['kode_provinsi' => '23', 'nama_provinsi' => 'Jawa Tengah'],
            ['kode_provinsi' => '24', 'nama_provinsi' => 'Yogyakarta'],
            ['kode_provinsi' => '25', 'nama_provinsi' => 'Jawa Timur'],
            ['kode_provinsi' => '26', 'nama_provinsi' => 'Bali'],
            ['kode_provinsi' => '27', 'nama_provinsi' => 'Nusa Tenggara Barat'],
            ['kode_provinsi' => '28', 'nama_provinsi' => 'Nusa Tenggara Timur'],
            ['kode_provinsi' => '30', 'nama_provinsi' => 'Kalimantan Barat'],
            ['kode_provinsi' => '31', 'nama_provinsi' => 'Kalimantan Tengah'],
            ['kode_provinsi' => '32', 'nama_provinsi' => 'Kalimantan Selatan'],
            ['kode_provinsi' => '33', 'nama_provinsi' => 'Kalimantan Timur'],
            ['kode_provinsi' => '34', 'nama_provinsi' => 'Kalimantan Utara'],
            ['kode_provinsi' => '40', 'nama_provinsi' => 'Sulawesi Barat'],
            ['kode_provinsi' => '41', 'nama_provinsi' => 'Sulawesi Selatan'],
            ['kode_provinsi' => '42', 'nama_provinsi' => 'Sulawesi Tengah'],
            ['kode_provinsi' => '43', 'nama_provinsi' => 'Sulawesi Tenggara'],
            ['kode_provinsi' => '44', 'nama_provinsi' => 'Gorontalo'],
            ['kode_provinsi' => '45', 'nama_provinsi' => 'Sulawesi Utara'],
            ['kode_provinsi' => '50', 'nama_provinsi' => 'Maluku Utara'],
            ['kode_provinsi' => '51', 'nama_provinsi' => 'Maluku'],
            ['kode_provinsi' => '52', 'nama_provinsi' => 'Sulawesi Utara'],
            ['kode_provinsi' => '53', 'nama_provinsi' => 'Papua Barat'],
            ['kode_provinsi' => '54', 'nama_provinsi' => 'Papua'],
            // lanjutkan sampai 38 provinsi
        ]);
    }
}
