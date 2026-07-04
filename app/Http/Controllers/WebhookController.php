<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleCallback(Request $request)
    {
        $pengaturan = \App\Models\PengaturanPembayaran::first();
        $serverKey = $pengaturan ? $pengaturan->midtrans_server_key : '';

        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$isProduction = false;
        
        try {
            $notification = new \Midtrans\Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error parsing notification'], 500);
        }

        $transaction = $notification->transaction_status;
        $type = $notification->payment_type;
        $order_id = $notification->order_id;
        $fraud = $notification->fraud_status;

        $pembayaran = DB::table('pembayarans')->where('id_pesanan', $order_id)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        DB::beginTransaction();

        try {
            $statusValidasi = $pembayaran->status_validasi;
            $statusPesanan = '';
            
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $statusValidasi = 'pending';
                    } else {
                        $statusValidasi = 'dibayar';
                        $statusPesanan = 'Menunggu konfirmasi penjual';
                    }
                }
            } else if ($transaction == 'settlement') {
                $statusValidasi = 'dibayar';
                $statusPesanan = 'Menunggu konfirmasi penjual';
            } else if ($transaction == 'pending') {
                $statusValidasi = 'pending';
            } else if ($transaction == 'deny') {
                $statusValidasi = 'dibatalkan';
                $statusPesanan = 'Dibatalkan';
            } else if ($transaction == 'expire') {
                $statusValidasi = 'expired';
                $statusPesanan = 'Dibatalkan';
            } else if ($transaction == 'cancel') {
                $statusValidasi = 'dibatalkan';
                $statusPesanan = 'Dibatalkan';
            }

            // Update status pembayaran
            DB::table('pembayarans')->where('id_pesanan', $order_id)->update([
                'status_validasi' => $statusValidasi,
                'midtrans_transaction_id' => $notification->transaction_id,
                'tanggal_pembayaran' => ($statusValidasi == 'dibayar') ? now() : $pembayaran->tanggal_pembayaran,
                'tgl_validasi' => ($statusValidasi == 'dibayar') ? now() : $pembayaran->tgl_validasi,
                'updated_at' => now()
            ]);

            // Update status pesanan jika lunas atau batal
            if ($statusPesanan) {
                DB::table('pesanans')->where('id_pesanan', $order_id)->update([
                    'status_pesanan' => $statusPesanan,
                    'updated_at' => now()
                ]);

                \App\Models\RiwayatPesanan::create([
                    'id_pesanan' => $order_id,
                    'status' => $statusPesanan,
                    'deskripsi' => 'Status diupdate otomatis oleh sistem Midtrans. (' . $transaction . ')'
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Webhook received successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook Update Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating database'], 500);
        }
    }

    // Fungsi khusus untuk Testing di Localhost (karena localhost tidak bisa terima webhook Midtrans)
    public function cekStatusManual($order_id)
    {
        $pengaturan = \App\Models\PengaturanPembayaran::first();
        \Midtrans\Config::$serverKey = $pengaturan ? $pengaturan->midtrans_server_key : '';
        \Midtrans\Config::$isProduction = false;
        
        try {
            // Ambil status langsung dari API Midtrans
            $status = \Midtrans\Transaction::status($order_id);
            
            $transaction = $status->transaction_status;
            $type = $status->payment_type;
            $fraud = $status->fraud_status ?? '';

            $pembayaran = DB::table('pembayarans')->where('id_pesanan', $order_id)->first();
            if (!$pembayaran) return back()->with('error', 'Pesanan tidak ditemukan.');

            $statusValidasi = $pembayaran->status_validasi;
            $statusPesanan = '';
            
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $statusValidasi = 'pending';
                    } else {
                        $statusValidasi = 'dibayar';
                        $statusPesanan = 'Menunggu konfirmasi penjual';
                    }
                }
            } else if ($transaction == 'settlement') {
                $statusValidasi = 'dibayar';
                $statusPesanan = 'Menunggu konfirmasi penjual';
            } else if ($transaction == 'pending') {
                $statusValidasi = 'pending';
            } else if ($transaction == 'deny') {
                $statusValidasi = 'dibatalkan';
                $statusPesanan = 'Dibatalkan';
            } else if ($transaction == 'expire') {
                $statusValidasi = 'expired';
                $statusPesanan = 'Dibatalkan';
            } else if ($transaction == 'cancel') {
                $statusValidasi = 'dibatalkan';
                $statusPesanan = 'Dibatalkan';
            }

            if ($statusValidasi != $pembayaran->status_validasi) {
                DB::table('pembayarans')->where('id_pesanan', $order_id)->update([
                    'status_validasi' => $statusValidasi,
                    'tanggal_pembayaran' => ($statusValidasi == 'dibayar') ? now() : null,
                    'updated_at' => now()
                ]);

                if ($statusPesanan) {
                    DB::table('pesanans')->where('id_pesanan', $order_id)->update([
                        'status_pesanan' => $statusPesanan,
                        'updated_at' => now()
                    ]);

                    \App\Models\RiwayatPesanan::create([
                        'id_pesanan' => $order_id,
                        'status' => $statusPesanan,
                        'deskripsi' => 'Status diupdate manual via API Midtrans. (' . $transaction . ')'
                    ]);
                }
                return redirect()->route('marketplace.pesanan.saya')->with('success', 'Status pesanan berhasil diperbarui (Disinkronkan dengan Midtrans).');
            }

            return redirect()->route('marketplace.pesanan.saya')->with('info', 'Status di Midtrans saat ini masih: ' . $transaction);

        } catch (\Exception $e) {
            Log::error('Midtrans API Cek Status Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengecek status ke Midtrans. Pastikan Server Key valid. Error: ' . $e->getMessage());
        }
    }
}
