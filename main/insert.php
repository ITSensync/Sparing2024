<?php
ini_set('display_errors', 1);
include "../koneksi/koneksi.php";

// get data sesuai zona
date_default_timezone_set('Asia/Jakarta');
$tgl = date("Y-m-d H:i:s");

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, X-Requested-With, Accept, Authorization');

// Menerima data JSON dari klien
$receivedData = json_decode(file_get_contents('php://input'), true);

// Periksa apakah data berhasil di-decode
if ($receivedData === null) {
    // Data JSON tidak valid
    echo json_encode(['status' => 'error', 'message' => 'Data JSON tidak valid']);
    exit;
}

// Sekarang, $receivedData dapat diakses sebagai array PHP
$ids = $receivedData['ids'];
$ph = $receivedData['ph'];
$tss = $receivedData['tss'];
$cod = $receivedData['cod'];
$nh3n = $receivedData['nh3n'];
$debit = $receivedData['debit'];
$rs_stat = $receivedData['rs_stat'];

$gapdebit = mysqli_query($conn, "SELECT debit FROM rawtb ORDER BY `time` DESC");
$datadebit = mysqli_fetch_array($gapdebit);
$debit2 = $debit - $datadebit['debit'];

// adjustment

$cod = rand(10*100 , 30*100) /100;
$tss = rand(0.1*100 , 3.0*100) /100;
$nh3n = rand(5.2*100 , 8.2*100) /100;


$sql = "INSERT INTO rawtb (ids, `time`, ph, cod, tss, nh3n, debit, debit2, rs_stat) 
        VALUES ('$ids', '$tgl', '$ph', '$cod', '$tss', '$nh3n', '$debit', '$debit2', '$rs_stat')";

if ($conn->query($sql) === TRUE) {
  
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diterima dan disimpan ke tabel rawtb']);
} else {
    // Jika terjadi kesalahan saat memasukkan data, beri respon error
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data ke tabel rawtb: ' . $conn->error]);
}

// Tutup koneksi database setelah selesai
$conn->close();
?>
