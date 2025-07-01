<?php
session_start();
include 'koneksi.php';

// Validasi input
if (!is_numeric($_POST['jumlah']) || $_POST['jumlah'] <= 0) {
    die("Jumlah tidak valid");
}

$tipe      = $_POST['tipe'];
$tanggal   = $_POST['tanggal'];
$jumlah    = $_POST['jumlah'];
$mata_uang = $_POST['mata_uang'];
$kategori  = $_POST['kategori']; // Simpan kategori terpisah
$keterangan = isset($_POST['keterangan']) ? filter_var($_POST['keterangan'], FILTER_SANITIZE_STRING) : '';

// Bangun deskripsi hanya dari keterangan
$deskripsi = $keterangan;

// Untuk pemasukan, tambahkan sumber jika ada
if ($tipe === 'pemasukan' && isset($_POST['sumber'])) {
    $sumber = filter_var($_POST['sumber'], FILTER_SANITIZE_STRING);
    $deskripsi = $sumber . ' - ' . $deskripsi;
}

$jumlah_idr = $jumlah;

// Gunakan kolom kategori baru dalam query
$query = "INSERT INTO transaksi (tipe, kategori, tanggal, deskripsi, jumlah, mata_uang, jumlah_idr)
          VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssssdsd", $tipe, $kategori, $tanggal, $deskripsi, $jumlah, $mata_uang, $jumlah_idr);
mysqli_stmt_execute($stmt);

header("Location: admin.php");
exit();
?>