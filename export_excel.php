<?php
include 'koneksi.php';
ob_clean();
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_infaq_" . date("Ymd") . ".xls");

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

echo "Tanggal\tTipe\tDeskripsi\tJumlah (IDR)\tMata Uang\n";
while ($row = mysqli_fetch_assoc($result)) {
    echo "{$row['tanggal']}\t{$row['tipe']}\t{$row['deskripsi']}\t{$row['jumlah_idr']}\t{$row['mata_uang']}\n";
}
?>
