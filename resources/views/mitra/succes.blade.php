<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Berhasil</title>

    <style>
        body {
            background: #f3f8f5;
            font-family: "Segoe UI", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .success-card {
            background: white;
            padding: 40px 50px;
            border-radius: 18px;
            text-align: center;
            max-width: 450px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .checkmark {
            font-size: 70px;
            color: #41A67E;
            animation: pop 0.6s ease-out;
        }

        @keyframes pop {
            0% { transform: scale(0); opacity: 0; }
            70% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }

        .btn-main {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 22px;
            background: #41A67E;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-main:hover {
            background: #318564;
        }
    </style>
</head>
<body>

    <div class="success-card">
        <div class="checkmark">✔</div>
        <h2>Pengajuan Berhasil Dikirim</h2>
        <p>Terima kasih! Pengajuan Anda sudah masuk ke antrian verifikasi admin.</p>

        <a href="{{ route('portal') }}" class="btn-main">Kembali Halaman</a>
    </div>

</body>
</html>
