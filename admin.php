<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

include 'koneksi.php';

$totalPemasukan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_idr) AS total FROM transaksi WHERE tipe='pemasukan'"))['total'] ?? 0;
$totalPengeluaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_idr) AS total FROM transaksi WHERE tipe='pengeluaran'"))['total'] ?? 0;
$saldo = $totalPemasukan - $totalPengeluaran;
?>

<html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Pencatatan Infaq Digital</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-center text-green-700 mb-8">Sistem Pencatatan Infaq Digital</h1>

    <section class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
      <div class="bg-white shadow rounded-lg p-4">
        <h2 class="text-lg font-semibold text-gray-700">Total Pemasukan</h2>
        <p id="totalIncome" class="text-2xl text-green-600">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></p>
      </div>
      <div class="bg-white shadow rounded-lg p-4">
        <h2 class="text-lg font-semibold text-gray-700">Total Pengeluaran</h2>
        <p id="totalExpense" class="text-2xl text-red-600">Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></p>
      </div>
      <div class="bg-white shadow rounded-lg p-4">
        <h2 class="text-lg font-semibold text-gray-700">Saldo Saat Ini</h2>
        <p id="currentBalance" class="text-2xl text-blue-600">Rp <?= number_format($saldo, 0, ',', '.') ?></p>
      </div>
    </section>

    <section class="bg-white shadow rounded-lg p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">Pencatatan Pemasukan Infaq</h2>
    <form action="proses_transaksi.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input type="hidden" name="tipe" value="pemasukan">
        <input type="date" name="tanggal" required class="border p-2 rounded">
        <input type="text" name="sumber" required class="border p-2 rounded" placeholder="Sumber Infaq">
        
        <!-- Dropdown untuk kategori pemasukan -->
        <select name="kategori" class="border p-2 rounded" required>
            <option value="">Pilih Kategori</option>
            <option value="Donatur Tetap">Donatur Tetap</option>
            <option value="Donatur Spontan">Donatur Spontan</option>
            <option value="Kotak Amal">Kotak Amal</option>
            <option value="Donasi Online">Donasi Online</option>
        </select>
        
        <input type="number" name="jumlah" required class="border p-2 rounded" placeholder="Jumlah Uang">
        <select name="mata_uang" class="border p-2 rounded">
            <option value="IDR">IDR</option>
        </select>
        <input type="text" name="keterangan" class="border p-2 rounded" placeholder="Keterangan Tambahan">
        <div class=""></div>
        <button type="submit" class="bg-green-600 text-white p-2 rounded">Simpan Pemasukan</button>
    </form>
</section>

<!-- Form Pengeluaran Infaq -->
<section class="bg-white shadow rounded-lg p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">Pencatatan Pengeluaran Infaq</h2>
    <form action="proses_transaksi.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input type="hidden" name="tipe" value="pengeluaran">
        <input type="date" name="tanggal" required class="border p-2 rounded">
        
        <!-- Dropdown untuk tujuan pengeluaran -->
        <select name="kategori" class="border p-2 rounded" required>
            <option value="">Pilih Kategori</option>
            <option value="Bantuan Sosial">Bantuan Sosial</option>
            <option value="Pendidikan">Pendidikan</option>
            <option value="Renovasi">Renovasi</option>
            <option value="Operasional">Operasional</option>
        </select>
        
        <input type="number" name="jumlah" required class="border p-2 rounded" placeholder="Jumlah Uang">
        <select name="mata_uang" class="border p-2 rounded">
            <option value="IDR">IDR</option>
        </select>
        <input type="text" name="keterangan" class="border p-2 rounded" placeholder="Keterangan Tambahan">
        <button type="submit" class="bg-red-600 text-white p-2 rounded">Simpan Pengeluaran</button>
    </form>
</section>

    <section class="bg-white shadow rounded-lg p-6">
      <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-4">
        <h2 class="text-xl font-semibold text-gray-700">Histori Transaksi</h2>
        <form method="GET" class="flex flex-wrap gap-2">
          <input type="text" name="keyword" placeholder="Cari deskripsi..." class="p-2 border rounded">
          <select name="tipe" class="p-2 border rounded">
            <option value="">Semua</option>
            <option value="pemasukan">Pemasukan</option>
            <option value="pengeluaran">Pengeluaran</option>
          </select>
          <input type="date" name="start" class="p-2 border rounded">
          <input type="date" name="end" class="p-2 border rounded">
          <button type="submit" class="bg-blue-600 text-white p-2 rounded">Filter</button>
          <a href="export_excel.php" class="bg-green-600 text-white p-2 rounded">Unduh Excel</a>
        </form>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto text-sm">
          <thead>
            <tr class="bg-gray-200 text-gray-600">
              <<th class="px-4 py-2">Tanggal</th>
              <th class="px-4 py-2">Tipe</th>
              <th class="px-4 py-2">Kategori</th> <!-- Kolom baru -->
              <th class="px-4 py-2">Deskripsi</th>
              <th class="px-4 py-2">Jumlah (IDR)</th>
              <th class="px-4 py-2">Mata Uang</th>
            </tr>
          </thead>
          <tbody>
            <?php
              include 'koneksi.php';
              $where = [];
              if (!empty($_GET['keyword'])) {
                $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
                $where[] = "deskripsi LIKE '%$keyword%'";
              }
              if (!empty($_GET['tipe'])) {
                $tipe = mysqli_real_escape_string($conn, $_GET['tipe']);
                $where[] = "tipe = '$tipe'";
              }
              if (!empty($_GET['start']) && !empty($_GET['end'])) {
                $start = mysqli_real_escape_string($conn, $_GET['start']);
                $end = mysqli_real_escape_string($conn, $_GET['end']);
                $where[] = "tanggal BETWEEN '$start' AND '$end'";
              }
              $sql = "SELECT * FROM transaksi";
              if (count($where) > 0) {
                $sql .= " WHERE " . implode(" AND ", $where);
              }
              $sql .= " ORDER BY tanggal DESC";
              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr class='text-gray-700'>";
                echo "<td class='px-4 py-2'>" . htmlspecialchars($row['tanggal']) . "</td>";
                echo "<td class='px-4 py-2'>" . htmlspecialchars($row['tipe']) . "</td>";
                echo "<td class='px-4 py-2'>" . htmlspecialchars($row['kategori']) . "</td>"; 
                echo "<td class='px-4 py-2'>" . htmlspecialchars($row['deskripsi']) . "</td>";
                echo "<td class='px-4 py-2'>Rp " . number_format($row['jumlah_idr'], 0, ',', '.') . "</td>";
                echo "<td class='px-4 py-2'>" . htmlspecialchars($row['mata_uang']) . "</td>";
                echo "</tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
    </section>
  </div>
</body>
</html>
