<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'koneksi.php';

$id   = (int)($_GET['id'] ?? 0);
$stmt = $koneksi->prepare(
    "SELECT id, id_penulis, id_kategori, judul, isi, gambar, hari_tanggal FROM artikel WHERE id = ?"
);
$stmt->bind_param('i', $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($data) {
    echo json_encode(['status' => 'sukses', 'data' => $data]);
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Data tidak ditemukan.']);
}
$koneksi->close();
