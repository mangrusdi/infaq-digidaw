<?php
include 'koneksi.php';

$totalPemasukan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_idr) AS total FROM transaksi WHERE tipe='pemasukan'"))['total'] ?? 0;
$totalPengeluaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_idr) AS total FROM transaksi WHERE tipe='pengeluaran'"))['total'] ?? 0;
$saldo = $totalPemasukan - $totalPengeluaran;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Informasi Laporan Keuangan</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    body {
      background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://i.ibb.co/4nQJM3rk/Screenshot-2025-06-27-220504.png');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.85);
      border-radius: 16px;
      overflow: hidden;
    }
    
    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    }
    
    .info-box {
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.85);
      border-radius: 16px;
    }
    
    .logo-container {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 50%;
      width: 120px;
      height: 120px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    
    .pulse {
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7); }
      70% { box-shadow: 0 0 0 15px rgba(76, 175, 80, 0); }
      100% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0); }
    }
    
    .divider {
      height: 2px;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
      margin: 25px 0;
    }
  </style>
</head>
<body class="text-white">
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">

      <div class="text-center mb-12">
        <div class="logo-container mb-6 pulse">
          <i class="fas fa-mosque text-5xl text-green-600"></i>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold mb-3 text-white tracking-wide">Informasi Laporan Keuangan</h1>
        <p class="text-xl text-green-200 mb-2">Masjid Al-Falah</p>
        <div class="divider"></div>
        <p class="text-green-100 max-w-2xl mx-auto">Transparansi penuh dalam pengelolaan dana infaq untuk kemajuan masjid dan umat</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">

        <div class="card">
          <div class="p-6">
            <div class="flex items-center mb-4">
              <div class="p-3 bg-green-100 rounded-lg mr-4">
                <i class="fas fa-coins text-2xl text-green-600"></i>
              </div>
              <h2 class="text-lg font-semibold text-gray-800">Total Pemasukan</h2>
            </div>
            <p class="text-3xl font-bold text-green-600 mb-2">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></p>
            <div class="flex items-center mt-4">
            </div>
          </div>
          <div class="bg-green-50 px-4 py-3">
            <p class="text-sm text-green-700"><i class="fas fa-info-circle mr-2"></i>Dana yang terkumpul dari donatur</p>
          </div>
        </div>

        <div class="card">
          <div class="p-6">
            <div class="flex items-center mb-4">
              <div class="p-3 bg-red-100 rounded-lg mr-4">
                <i class="fas fa-hand-holding-usd text-2xl text-red-600"></i>
              </div>
              <h2 class="text-lg font-semibold text-gray-800">Total Pengeluaran</h2>
            </div>
            <p class="text-3xl font-bold text-red-600 mb-2">Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></p>
            <div class="flex items-center mt-4">
            </div>
          </div>
          <div class="bg-red-50 px-4 py-3">
            <p class="text-sm text-red-700"><i class="fas fa-info-circle mr-2"></i>Dana yang telah digunakan untuk kegiatan</p>
          </div>
        </div>

        <div class="card">
          <div class="p-6">
            <div class="flex items-center mb-4">
              <div class="p-3 bg-blue-100 rounded-lg mr-4">
                <i class="fas fa-piggy-bank text-2xl text-blue-600"></i>
              </div>
              <h2 class="text-lg font-semibold text-gray-800">Saldo Saat Ini</h2>
            </div>
            <p class="text-3xl font-bold text-blue-600 mb-2">Rp <?= number_format($saldo, 0, ',', '.') ?></p>
            <div class="flex items-center mt-4">
            </div>
          </div>
          <div class="bg-blue-50 px-4 py-3">
            <p class="text-sm text-blue-700"><i class="fas fa-info-circle mr-2"></i>Saldo terkini yang tersedia</p>
          </div>
        </div>
      </div>

      <div class="info-box p-6 mb-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Penggunaan Dana Terkini</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="text-center">
            <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-hands-helping text-3xl text-green-600"></i>
            </div>
            <h3 class="font-bold text-lg text-gray-800 mb-2">Bantuan Sosial</h3>
            <p class="text-gray-600">untuk bantuan keluarga dhuafa</p>
          </div>
          <div class="text-center">
            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-book-quran text-3xl text-blue-600"></i>
            </div>
            <h3 class="font-bold text-lg text-gray-800 mb-2">Pendidikan</h3>
            <p class="text-gray-600">untuk pembelian buku agama</p>
          </div>
          <div class="text-center">
            <div class="bg-yellow-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-tools text-3xl text-yellow-600"></i>
            </div>
            <h3 class="font-bold text-lg text-gray-800 mb-2">Renovasi</h3>
            <p class="text-gray-600">untuk perbaikan masjid </p>
          </div>
        </div>
      </div>

      <div class="text-center my-12 py-8 px-4 bg-black bg-opacity-30 rounded-xl">
        <i class="fas fa-quote-left text-4xl text-green-400 opacity-50 mb-4"></i>
        <p class="text-xl italic text-green-100 max-w-2xl mx-auto">
          "Sesungguhnya orang-orang yang membenarkan Allah dan Rasul-Nya, mereka itu akan bersama-sama dengan orang-orang yang dianugerahi nikmat oleh Allah, yaitu para nabi, shiddiqin, syuhada, dan orang-orang shalih. Mereka itulah sebaik-baik teman."
        </p>
        <p class="mt-4 text-green-300">QS. An-Nisa: 69</p>
      </div>

      <div class="text-center text-gray-500 text-sm">
        <p>© 2025 - D3 Teknik Informatika '24 - ULBI</p>

      </div>
