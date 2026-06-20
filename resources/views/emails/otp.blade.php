<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP Anda</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h2 style="color: #333333; text-align: center;">Bibitnesia</h2>
        <p style="font-size: 16px; color: #555555;">Halo,</p>
        <p style="font-size: 16px; color: #555555;">Berikut adalah kode OTP Anda untuk melanjutkan proses autentikasi. Kode ini berlaku selama 10 menit.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span style="display: inline-block; font-size: 32px; font-weight: bold; color: #28a745; background-color: #e9f7ef; padding: 10px 20px; border-radius: 5px; letter-spacing: 5px;">
                {{ $otp }}
            </span>
        </div>
        
        <p style="font-size: 14px; color: #777777;">Jika Anda tidak merasa melakukan permintaan ini, harap abaikan email ini.</p>
        <p style="font-size: 14px; color: #777777;">Terima kasih,<br>Tim Bibitnesia</p>
    </div>
</body>
</html>
