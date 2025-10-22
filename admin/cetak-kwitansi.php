<?php
require_once __DIR__.'/../vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf('P', 'A4', 'en');

include '../koneksi.php';
$nisn = $_GET['nisn'];

$sql = "SELECT pembayaran.*, siswa.nama, kelas.nama_kelas, spp.tahun, spp.nominal, petugas.nama_petugas 
        FROM siswa
        LEFT JOIN pembayaran ON pembayaran.nisn = siswa.nisn
        LEFT JOIN kelas ON siswa.id_kelas = kelas.id_kelas
        LEFT JOIN spp ON pembayaran.id_spp = spp.id_spp
        LEFT JOIN petugas ON pembayaran.id_petugas = petugas.id_petugas
        WHERE siswa.nisn = '$nisn'";

$query = mysqli_query($koneksi, $sql);
$data_siswa = mysqli_fetch_assoc($query);

$html = '
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    th, td {
        padding: 5px;
        border: 1px solid black;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
</style>

<h4 style="text-align: center;">Kwitansi Pembayaran SPP</h4>
<hr>

<h5>Data Siswa</h5>
<table>
    <tr>
        <td><strong>NISN</strong></td>
        <td>' . $data_siswa['nisn'] . '</td>
    </tr>
    <tr>
        <td><strong>Nama</strong></td>
        <td>' . $data_siswa['nama'] . '</td>
    </tr>
    <tr>
        <td><strong>Kelas</strong></td>
        <td>' . $data_siswa['nama_kelas'] . '</td>
    </tr>
    <tr>
        <td><strong>Tahun SPP</strong></td>
        <td>' . $data_siswa['tahun'] . '</td>
    </tr>
    <tr>
        <td><strong>Nominal SPP</strong></td>
        <td>Rp ' . number_format($data_siswa['nominal'], 2, ',', '.') . '</td>
    </tr>
</table>

<h5>History Pembayaran</h5>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Bulan Dibayar</th>
            <th>Jumlah Bayar</th>
            <th>Tanggal Bayar</th>
            <th>Petugas</th>
        </tr>
    </thead>
    <tbody>';

$no = 1;

if (!$data_siswa['jumlah_bayar']) {
    $html .= '<tr><td colspan="5">Tidak ada pembayaran yang ditemukan.</td></tr>';
} else {
    mysqli_data_seek($query, 0);
    while ($data = mysqli_fetch_assoc($query)) {
        $html .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . $data['bulan_dibayar'] . '</td>
            <td>Rp ' . number_format($data['jumlah_bayar'], 2, ',', '.') . '</td>
            <td>' . $data['tgl_bayar'] . '</td>
            <td>' . $data['nama_petugas'] . '</td>
        </tr>';
    }
}

$html .= '
    </tbody>
</table>

<div style="text-align: right;">
    <p><strong>Tanggal Cetak:</strong> ' . date('d-m-Y') . '</p>
</div>';

$html2pdf->writeHTML($html);

$html2pdf->output('kwitansi_pembayaran_spp.pdf', 'I');
?>
