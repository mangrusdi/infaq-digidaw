<?php
// File: dashboard.php
include 'koneksi.php';

// Hitung total pemasukan, pengeluaran, dan saldo
$totalPemasukan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_idr) AS total FROM transaksi WHERE tipe='pemasukan'"))['total'] ?? 0;
$totalPengeluaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_idr) AS total FROM transaksi WHERE tipe='pengeluaran'"))['total'] ?? 0;
$saldo = $totalPemasukan - $totalPengeluaran;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Infaq Digital</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen flex items-center justify-center">
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-12">
        <img src="https://cdn-icons-png.flaticon.com/512/3131/3131634.png" alt="Logo Masjid" class="w-24 h-24 mx-auto mb-4">
        <h1 class="text-3xl md:text-4xl font-bold text-green-700 mb-2">Laporan Infaq Digital</h1>
        <p class="text-gray-600">Ringkasan Keuangan Masjid Al-Falah</p>
      </div>

      <!-- Cards Section -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <!-- Pemasukan Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 card">
          <div class="flex items-center mb-4">
            <div class="p-3 bg-green-100 rounded-lg mr-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-700">Total Pemasukan</h2>
          </div>
          <p class="text-2xl md:text-3xl font-bold text-green-600">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></p>
          <p class="text-sm text-gray-500 mt-2">Dana yang terkumpul dari donatur</p>
        </div>

        <!-- Pengeluaran Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 card">
          <div class="flex items-center mb-4">
            <div class="p-3 bg-red-100 rounded-lg mr-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-700">Total Pengeluaran</h2>
          </div>
          <p class="text-2xl md:text-3xl font-bold text-red-600">Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></p>
          <p class="text-sm text-gray-500 mt-2">Dana yang telah digunakan untuk kegiatan</p>
        </div>

        <!-- Saldo Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 card">
          <div class="flex items-center mb-4">
            <div class="p-3 bg-blue-100 rounded-lg mr-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-700">Saldo Saat Ini</h2>
          </div>
          <p class="text-2xl md:text-3xl font-bold text-blue-600">Rp <?= number_format($saldo, 0, ',', '.') ?></p>
          <p class="text-sm text-gray-500 mt-2">Saldo terkini yang tersedia</p>
        </div>
      </div>

      <!-- Info Box -->
      <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Terkini</h2>
        <div class="space-y-4">
          <div class="flex items-start">
            <div class="flex-shrink-0 mt-1">
              <div class="w-3 h-3 bg-green-500 rounded-full"></div>
            </div>
            <p class="ml-3 text-gray-600">Dana infaq digunakan untuk pembangunan fasilitas masjid</p>
          </div>
          <div class="flex items-start">
            <div class="flex-shrink-0 mt-1">
              <div class="w-3 h-3 bg-green-500 rounded-full"></div>
            </div>
            <p class="ml-3 text-gray-600">Laporan keuangan diperbarui setiap hari Jumat</p>
          </div>
          <div class="flex items-start">
            <div class="flex-shrink-0 mt-1">
              <div class="w-3 h-3 bg-green-500 rounded-full"></div>
            </div>
            <p class="ml-3 text-gray-600">Transparansi 100% untuk semua penggunaan dana</p>
          </div>
        </div>
      </div>

      <div class="text-center text-gray-500 text-sm">
        <p>© <?= date('Y') ?> Sistem Pencatatan Infaq Digital - Masjid Al-Falah</p>
      </div>
    </div>
  </div>
</body>
</html>