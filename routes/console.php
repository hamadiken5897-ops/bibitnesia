<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Models\Produk;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

\Illuminate\Support\Facades\Schedule::call(function () {
    $expiredProducts = Produk::where('status', 'dihapus_admin')
        ->whereNotNull('tgl_dihapus_admin')
        ->where('tgl_dihapus_admin', '<=', now()->subDays(7))
        ->get();

    foreach ($expiredProducts as $produk) {
        // Hapus foto jika ada
        $fotos = ['foto_produk1', 'foto_produk2', 'foto_produk3'];
        foreach ($fotos as $foto) {
            if ($produk->$foto && Storage::disk('public')->exists($produk->$foto)) {
                Storage::disk('public')->delete($produk->$foto);
            }
        }

        Log::info('Produk otomatis terhapus permanen setelah 7 hari', ['id' => $produk->id_produk]);
        $produk->delete();
    }

    // Bersihkan peringatan kuning yang sudah lebih dari 5 hari
    $expiredWarnings = \App\Models\User::whereNotNull('tgl_peringatan')
        ->where('tgl_peringatan', '<', now()->subDays(5))
        ->update([
            'peringatan_teks' => null,
            'tgl_peringatan' => null
        ]);
        
    if ($expiredWarnings > 0) {
        Log::info("Peringatan kuning otomatis dihapus untuk {$expiredWarnings} pengguna.");
    }
})->daily();
