$pesanan = \App\Models\Pesanan::where('id_pesanan', 'ORD-E7V3LNXOTT')->first();
if ($pesanan) {
    $pesanan->status_pesanan = 'diproses';
    $pesanan->save();
    
    $pembayaran = \App\Models\Pembayaran::where('id_pesanan', $pesanan->id_pesanan)->first();
    if ($pembayaran) {
        $pembayaran->status_validasi = 'valid';
        $pembayaran->tanggal_pembayaran = now();
        $pembayaran->save();
        echo 'Berhasil simulasi lunas!';
    } else {
        echo 'Pembayaran tidak ditemukan';
    }
} else {
    echo 'Pesanan tidak ditemukan';
}
