WEBSITE PEMBAYARAN SPP

Program ini adalah aplikasi web untuk mengelola pembayaran SPP (Sumbangan Pembinaan Pendidikan) sekolah. Dibangun untuk memudahkan petugas sekolah, bendahara, dan admin dalam mencatat, melacak, dan melaporkan pembayaran siswa secara terstruktur dan aman.

Siapa yang memakai dan kenapa
Admin sekolah: mengelola data master (siswa, kelas, ongkos SPP, petugas), melihat laporan keseluruhan.
Petugas/bendahara: mencatat pembayaran harian, memberikan bukti pembayaran, melihat riwayat transaksi.
Siswa/Orangtua (opsional): melihat status pembayaran jika disediakan modul akses siswa.
Manfaat: mengurangi kesalahan manual, mempercepat proses pembayaran, memudahkan audit dan pelaporan.
Fitur utama (dengan penjelasan sederhana)
Manajemen Data Master

Input dan edit data Siswa (nama, NISN/NIS, kelas, kontak).
Data Kelas: atur tingkat/rombel untuk klasifikasi siswa.
Data SPP: atur nominal SPP per periode/tahun ajaran.
Data Petugas/User: akun untuk staff yang akan mencatat pembayaran.
Autentikasi dan Hak Akses

Login untuk petugas/admin.
Hak akses berbeda: contoh admin bisa tambah user & melihat semua laporan, petugas hanya input pembayaran.
Pencatatan Pembayaran

Form pencatatan pembayaran per siswa: pilih siswa, pilih periode SPP, masukkan jumlah yang dibayar, tanggal, dan petugas.
Sistem mencatat sisa kewajiban (kalau pembayaran cicilan atau pembayaran sebagian).
Riwayat & Pencarian Transaksi

Tabel riwayat pembayaran yang bisa dicari berdasarkan nama siswa, tanggal, atau nomor transaksi.
Memudahkan penelusuran pembayaran terdahulu.
Cetak Bukti Pembayaran / Nota

Setelah input pembayaran, bisa cetak bukti/struk yang berisi detail: nama siswa, jumlah, tanggal, petugas, dan nomor transaksi.
Laporan & Rekap

Laporan penerimaan per periode (harian/bulanan/tahunan).
Rekap per kelas atau per siswa untuk memudahkan rekonsiliasi.
Validasi & Keamanan Dasar

Validasi input agar data tidak kosong atau format salah (mis. angka untuk nominal).
Penyimpanan di basis data lokal (MySQL melalui XAMPP) sehingga data tersentral.
Alur penggunaan sederhana (contoh untuk petugas)
Petugas login ke aplikasi.
Buka menu "Pembayaran" → cari siswa dengan nama atau NIS.
Pilih periode SPP (mis. Januari 2025), masukkan nominal yang dibayar.
Klik "Simpan" → sistem menyimpan transaksi dan menampilkan/menyediakan tombol cetak bukti.
Jika perlu, admin bisa menarik laporan akhir bulan untuk rekonsiliasi.
Struktur data (konsep umum)
Biasanya aplikasi seperti ini memiliki tabel:

users / petugas (id, nama, username, password, role)
siswa (id, nama, nis, kelas_id, kontak)
kelas (id, nama_kelas)
spp (id, tahun, nominal)
pembayaran (id, siswa_id, spp_id, tgl_bayar, jumlah_bayar, petugas_id, keterangan)
Instalasi singkat (untuk lingkungan XAMPP pada Windows)
Jalankan XAMPP: aktifkan Apache dan MySQL.
Letakkan folder proyek di c:\xampp\htdocs\Pembayaran_Spp.
Buat database MySQL (mis. nama db: pembayaran_spp) dan impor struktur SQL jika ada file .sql.
Sesuaikan konfigurasi koneksi database di file konfigurasi (biasanya config/database.php atau koneksi di file utama) dengan host=localhost, user=root, password kosong, database=pembayaran_spp.
Akses aplikasi lewat browser: http://localhost/Pembayaran_Spp
Tips penggunaan dan perawatan
Lakukan backup database secara berkala.
Gunakan akun admin yang aman (password kuat) dan buat akun petugas terpisah.
Rekonsiliasi laporan bulanan dengan bukti fisik/catatan apabila diperlukan.
Tambahan untuk developer / teknis singkat
Proyek kemungkinan menggunakan PHP (karena jalur XAMPP) dan MySQL.
Untuk fitur cetak biasanya digunakan library print/convert HTML ke PDF.
Untuk ekspor laporan bisa ditambahkan fitur export CSV/Excel


