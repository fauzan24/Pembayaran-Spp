<?php
require_once __DIR__.'/../vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf('L', 'A4', 'en',array(8,8,8,8));

include '../koneksi.php';
$no = 1;
$sql = "SELECT * FROM pembayaran, siswa, kelas, spp, petugas 
        WHERE pembayaran.nisn = siswa.nisn 
        AND siswa.id_kelas = kelas.id_kelas 
        AND pembayaran.id_spp = spp.id_spp 
        AND pembayaran.id_petugas = petugas.id_petugas 
        ORDER BY tgl_bayar DESC";
$query = mysqli_query($koneksi, $sql);

$html = '
<style>
    table {
        width: 100%;
        max-width: 100%; /* Membatasi lebar maksimal tabel */
        border-collapse: collapse;
        font-size: 10px; /* Mengecilkan ukuran font */
    }
    th, td {
        padding: 5px 8px; /* Mengurangi padding */
        text-align: left;
        border: 1px solid black;
    }
    th {
        background-color: #f2f2f2;
    }
</style>

<h5 style="text-align: center;">Laporan Pembayaran SPP</h5>
<hr>
<table>
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 5%;">NISN</th>
            <th style="width: 15%;">Nama</th>
            <th style="width: 7%;">Kelas</th>
            <th style="width: 10%;">Bulan Dibayar</th>
            <th style="width: 10%;">Tahun SPP</th>
            <th style="width: 10%;">Nominal Dibayar</th>
            <th style="width: 15%;">Sudah Dibayar</th>
            <th style="width: 10%;">Tanggal Bayar</th>
            <th style="width: 13%;">Petugas</th>
        </tr>
    </thead>
    <tbody>';

foreach ($query as $data) {
    $html .= '
    <tr>
        <td>' . $no++ . '</td>
        <td>' . $data['nisn'] . '</td>
        <td>' . $data['nama'] . '</td>
        <td>' . $data['nama_kelas'] . '</td>
        <td>' . $data['bulan_dibayar'] . '</td>
        <td>' . $data['tahun'] . '</td>
        <td>Rp ' . number_format($data['nominal'], 2, ',', '.') . '</td>
        <td>Rp ' . number_format($data['jumlah_bayar'], 2, ',', '.') . '</td>
        <td>' . $data['tgl_bayar'] . '</td>
        <td>' . $data['nama_petugas'] . '</td>
    </tr>';
}

$html .= '
    </tbody>
</table>';

$html2pdf->writeHTML($html);

$html2pdf->output('laporan_pembayaran_spp.pdf', 'I'); // Output ke browser
