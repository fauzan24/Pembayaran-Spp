<?php
$nisn = $_GET['nisn'];
$kekurangan = $_GET['kekurangan'];
$thn_spp = $_GET['tahun'];
include '../koneksi.php';

$sql = "SELECT * FROM siswa, spp, kelas WHERE siswa.id_kelas = kelas.id_kelas AND siswa.id_spp = spp.id_spp AND nisn = '$nisn'";
$query = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_array($query);

$tanggal = date('Y/m/d');
$tanggal_v = date('d/m/Y');
$krg_bln = $data['nominal'] / 12; // Nominal SPP dibagi 12 bulan

// Daftar bulan
$bulan = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni", 
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];
?>
<h5>Halaman Pembayaran SPP</h5>
<a href="?url=pembayaran" class="btn btn-primary">Kembali</a>
<hr>
<form action="?url=proses-tambah-pembayaran&nisn=<?= $nisn; ?>" method="post">
    <input name="id_spp" value="<?= $data['id_spp'] ?>" type="hidden" class="form-control" required>
    
    <div class="form-group mb-2">
        <label>Nama Petugas</label>
        <input value="<?= $_SESSION['nama_petugas'] ?>" disabled class="form-control" required>
    </div>
    <div class="form-group mb-2">
        <label>NISN</label>
        <input readonly name="nisn" value="<?= $data['nisn'] ?>" type="text" class="form-control" required>
    </div>
    <div class="form-group mb-2">
        <label>Nama Siswa</label>
        <input disabled value="<?= $data['nama'] ?>" type="text" class="form-control" required>
    </div>
    <div class="form-group mb-2">
        <input name="tgl_bayar" value="<?= $tanggal ?>" type="hidden" class="form-control" required>
        <label>Tanggal Bayar</label>
        <input value='<?= $tanggal_v ?>' readonly class="form-control" required>
    </div>
    <div class="form-group mb-2">
        <label>Bulan Bayar</label>
        <select name="bulan_dibayar" class="form-control" required>
            <option value="">Pilih bulan dibayar</option>
            <?php
            for ($i = 0; $i < count($bulan); $i++) {
                $bln = $bulan[$i];

                // Query untuk mengecek apakah bulan sudah dibayar
                $sisa = "SELECT SUM(jumlah_bayar) AS total FROM pembayaran WHERE bulan_dibayar = '$bln' AND nisn = '$nisn' AND tahun_dibayar = '$thn_spp'";
                $query_bln = mysqli_query($koneksi, $sisa);
                $ttl_bln = mysqli_fetch_array($query_bln);

                // Total pembayaran untuk bulan tersebut
                $total_dibayar = isset($ttl_bln['total']) ? $ttl_bln['total'] : 0;

                // Hitung sisa yang harus dibayar untuk bulan ini
                $sisa_bayar = $krg_bln - $total_dibayar;

                if ($sisa_bayar <= 0) {
                    echo "<option value='$bln' disabled>$bln - Lunas</option>";
                } else {
                    echo "<option value='$bln'>$bln - Sisa: " . number_format($sisa_bayar, 2, ',', '.') . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group mb-2">
        <label>Tahun Bayar</label>
        <input value="<?= $thn_spp ?>" name="tahun_dibayar" type="text" class="form-control" readonly>
    </div>
    <div class="form-group mb-2">
        <label>Jumlah Bayar (Jumlah Maksimal bayar Adalah <b><?= number_format($kekurangan, 2, ',', '.') ?></b>)</label>
        <input type="number" name="jumlah_bayar" max="<?= $kekurangan ?>" class="form-control" required>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-success">Simpan</button>
        <button type="reset" class="btn btn-warning">Kosongkan</button>
    </div>
</form>