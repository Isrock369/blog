<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'koneksi.php';
require_once 'helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'pesan' => 'Method tidak diizinkan.']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if (!$id) {
    echo json_encode(['status' => 'error', 'pesan' => 'ID tidak valid.']);
    exit;
}

$stmtGambar = $koneksi->prepare("SELECT gambar FROM artikel WHERE id = ?");
$stmtGambar->bind_param('i', $id);
$stmtGambar->execute();
$data = $stmtGambar->get_result()->fetch_assoc();
$stmtGambar->close();

if (!$data) {
    echo json_encode(['status' => 'error', 'pesan' => 'Artikel tidak ditemukan.']);
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM artikel WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    hapusGambar($data['gambar'], 'uploads_artikel');
    echo json_encode(['status' => 'sukses', 'pesan' => 'Artikel berhasil dihapus.']);
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Gagal menghapus artikel.']);
}

$stmt->close();
$koneksi->close();
