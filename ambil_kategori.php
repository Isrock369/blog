<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'koneksi.php';

$result = $koneksi->query("SELECT id, nama_kategori, keterangan FROM kategori_artikel ORDER BY id ASC");
$data   = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['status' => 'sukses', 'data' => $data]);
$koneksi->close();
