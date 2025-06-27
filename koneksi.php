<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "infaq";

$conn = mysqli_connect($host, $user, $pass, $db) or die("Koneksi gagal");
mysqli_set_charset($conn, "utf8");
?>
