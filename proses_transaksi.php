<?php
if (!is_numeric($_POST['jumlah']) || $_POST['jumlah'] <= 0) {
    die("Jumlah tidak valid");
}
// Sanitasi input
$deskripsi = filter_var($_POST['deskripsi'], FILTER_SANITIZE_STRING);
include 'koneksi.php';
$pemasukan_result = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM transaksi WHERE tipe = 'pemasukan'");
$pengeluaran_result = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM transaksi WHERE tipe = 'pengeluaran'");

$total_pemasukan = mysqli_fetch_assoc($pemasukan_result)['total'] ?? 0;
$total_pengeluaran = mysqli_fetch_assoc($pengeluaran_result)['total'] ?? 0;

$saldo = $total_pemasukan - $total_pengeluaran;
// Ambil data dari POST
$tipe      = $_POST['tipe'];
$tanggal   = $_POST['tanggal'];
$jumlah    = $_POST['jumlah'];
$mata_uang = $_POST['mata_uang'];

// Gunakan field berbeda untuk pemasukan dan pengeluaran
$sumber_tujuan = $tipe === 'pemasukan' ? $_POST['sumber'] : $_POST['tujuan'];
$deskripsi = $sumber_tujuan . ' - ' . $_POST['deskripsi'];

// Jumlah IDR disamakan saja tanpa konversi
$jumlah_idr = $jumlah;

// Query INSERT
$query = "INSERT INTO transaksi (tipe, tanggal, deskripsi, jumlah, mata_uang, jumlah_idr)
          VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sssdsd", $tipe, $tanggal, $deskripsi, $jumlah, $mata_uang, $jumlah_idr);
mysqli_stmt_execute($stmt);

// Redirect ke halaman utama
header("Location: admin.php");
exit();
?>
