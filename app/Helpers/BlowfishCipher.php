<?php

namespace App\Helpers;

use phpseclib3\Crypt\Blowfish;

class BlowfishCipher
{
    /**
     * Enkripsi data menggunakan algoritma Blowfish (bf-cbc) via phpseclib
     */
    public static function encrypt($textAsli)
    {
        if (empty($textAsli)) return $textAsli;

        $kunci = env('BLOWFISH_KEY', 'fallback_key_for_safety');
        
        $cipher = new Blowfish('cbc');
        $cipher->setKey($kunci);
        
        // Blowfish butuh IV (Initialization Vector) sebesar 8 byte (64-bit)
        try {
            $iv = random_bytes(8);
        } catch (\Exception $e) {
            $iv = substr(md5(uniqid()), 0, 8); // fallback
        }
        
        $cipher->setIV($iv);
        
        // Proses Enkripsi
        $hasilEnkripsi = $cipher->encrypt($textAsli);
        
        // Gabungkan IV dan Hasil Enkripsi lalu ubah ke Base64 agar aman disimpan di DB
        return base64_encode($iv . $hasilEnkripsi);
    }

    /**
     * Dekripsi data menggunakan algoritma Blowfish (bf-cbc) via phpseclib
     */
    public static function decrypt($textAcak)
    {
        if (empty($textAcak)) return $textAcak;

        $kunci = env('BLOWFISH_KEY', 'fallback_key_for_safety');
        
        $data = base64_decode($textAcak);
        
        // Pastikan panjang data minimal lebih dari panjang IV (8 byte)
        if (strlen($data) <= 8) return $textAcak;

        // Pisahkan 8 byte pertama (sebagai IV) dan sisanya (sebagai Ciphertext)
        $iv = substr($data, 0, 8);
        $hasilEnkripsi = substr($data, 8);
        
        $cipher = new Blowfish('cbc');
        $cipher->setKey($kunci);
        $cipher->setIV($iv);
        
        try {
            // Proses Dekripsi
            $decrypted = $cipher->decrypt($hasilEnkripsi);
            return $decrypted !== false ? $decrypted : $textAcak;
        } catch (\Exception $e) {
            // Jika gagal dekripsi (kunci salah/data rusak)
            return $textAcak;
        }
    }
}
