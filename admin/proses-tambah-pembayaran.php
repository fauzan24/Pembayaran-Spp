proses tambah pembayaran
<?php
session_start();

$id_petugas  = $_SESSION['id_petugas'];
$nisn = $_POST['nisn'];
$tgl_bayar = $_POST['tgl_bayar'];
$bulan_dibayar = $_POST['bulan_dibayar'];
$tahun_dibayar = $_POST['tahun_dibayar'];
$id_spp = $_POST['id_spp'];
$jumlah_bayar = $_POST['jumlah_bayar'];

include '../koneksi.php';

// Mendapatkan nominal SPP per bulan
$sql = "SELECT nominal FROM spp WHERE id_spp = '$id_spp'";
$query = mysqli_query($koneksi, $sql);
$data_spp = mysqli_fetch_array($query);
$nominal_bulan = $data_spp['nominal'] / 12; // Nominal SPP bulanan

// Proses pembayaran berlebih
while ($jumlah_bayar > 0) {
    // Cek total pembayaran untuk bulan yang dipilih
    $sisa = "SELECT SUM(jumlah_bayar) AS total FROM pembayaran WHERE nisn = '$nisn' AND bulan_dibayar = '$bulan_dibayar' AND tahun_dibayar = '$tahun_dibayar'";
    $query_sisa = mysqli_query($koneksi, $sisa);
    $data_sisa = mysqli_fetch_array($query_sisa);
    $total_dibayar = isset($data_sisa['total']) ? $data_sisa['total'] : 0;

    // Hitung sisa yang harus dibayar untuk bulan ini
    $sisa_bayar = $nominal_bulan - $total_dibayar;

    if ($jumlah_bayar >= $sisa_bayar) {
        // Bayar sisa bulan ini
        $sql = "INSERT INTO pembayaran(id_petugas, nisn, tgl_bayar, bulan_dibayar, tahun_dibayar, id_spp, jumlah_bayar) 
                VALUES('$id_petugas', '$nisn', '$tgl_bayar', '$bulan_dibayar', '$tahun_dibayar', '$id_spp', '$sisa_bayar')";
        mysqli_query($koneksi, $sql);

        // Kurangi jumlah_bayar dengan sisa yang dibayarkan
        $jumlah_bayar -= $sisa_bayar;

        // Pindah ke bulan berikutnya
        $bulan_dibayar = next_bulan($bulan_dibayar);
    } else {
        // Jika tidak cukup untuk lunasi bulan ini, bayar sebagian
        $sql = "INSERT INTO pembayaran(id_petugas, nisn, tgl_bayar, bulan_dibayar, tahun_dibayar, id_spp, jumlah_bayar) 
                VALUES('$id_petugas', '$nisn', '$tgl_bayar', '$bulan_dibayar', '$tahun_dibayar', '$id_spp', '$jumlah_bayar')";
        mysqli_query($koneksi, $sql);
        $jumlah_bayar = 0; // Pembayaran selesai
    }
}

if ($query) {
    header("Location:?url=pembayaran");
} else {
    echo "<script>alert('Maaf, data tidak tersimpan'); window.location.assign('?url=pembayaran');</script>";
}

function next_bulan($current_bulan) {
    $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    $index = array_search($current_bulan, $bulan);
    return $bulan[($index + 1) % 12];
}
?>