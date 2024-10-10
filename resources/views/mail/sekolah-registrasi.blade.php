<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Template</title>
</head>

<body>

    <h2>Registrasi Sekolah Baru - Perhatian Diperlukan</h2>
    <p>Hai Admin,</p>
    <p>
        Kami ingin memberitahukan bahwa telah dilakukan registrasi data sekolah baru yang belum terdaftar dalam sistem.
        Mohon untuk segera meninjau dan mengonfirmasi kelengkapan data berikut sebelum melanjutkan proses registrasi:
    </p>

    <table>
        <tr>
            <td><strong>NPSN:</strong></td>
            <td>{{ $npsn }}</td>
        </tr>
        <tr>
            <td><strong>Nama Sekolah:</strong></td>
            <td>{{ $name }}</td>
        </tr>
        <tr>
            <td><strong>Email Sekolah:</strong></td>
            <td>{{ $email }}</td>
        </tr>
        <tr>
            <td><strong>Status Sekolah:</strong></td>
            <td>{{ $status == 'N' ? 'Negeri' : 'Swasta' }}</td>
        </tr>
        <tr>
            <td><strong>Alamat Sekolah:</strong></td>
            <td>{{ $address }}</td>
        </tr>
        <tr>
            <td><strong>No Telepon Sekolah:</strong></td>
            <td>{{ $phone }}</td>
        </tr>
        <tr>
            <td><strong>Provinsi:</strong></td>
            <td>{{ $dp_provinsi }}</td>
        </tr>
        <tr>
            <td><strong>Kota:</strong></td>
            <td>{{ $dp_kota }}</td>
        </tr>
        <tr>
            <td><strong>Kecamatan:</strong></td>
            <td>{{ $dp_kecamatan }}</td>
        </tr>
        <tr>
            <td><strong>Kelurahan:</strong></td>
            <td>{{ $dp_kelurahan }}</td>
        </tr>
    </table>

    <p>
        Jika sekolah tersebut tidak terdaftar dalam database kami, silakan tambahkan datanya atau hubungi pihak terkait
        untuk konfirmasi lebih lanjut.
    </p>

    <p>Terima kasih atas perhatiannya.</p>

    <p>Hormat Kami,<br>Tim Registrasi Sekolah</p>

</body>

</html>
