<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'koneksi.php';

$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $koneksi->prepare("SELECT id, nama_depan, nama_belakang, user_name, foto FROM penulis WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$data   = $result->fetch_assoc();
$stmt->close();

if ($data) {
    echo json_encode(['status' => 'sukses', 'data' => $data]);
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Data tidak ditemukan.']);
}
$koneksi->close();
