<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        }

        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Verifikasi Akun Anda</h2>
        <p>Halo, {{ $user->name }}! Terima kasih telah mendaftar di aplikasi kami.</p>
        <p>Silakan klik tombol di bawah ini untuk memverifikasi akun Anda:</p>
        <a href="{{ $verificationUrl }}" class="button">Verifikasi Akun</a>
        <p class="footer">Jika Anda tidak mendaftar di aplikasi kami, abaikan email ini.</p>
    </div>
</body>

</html>
