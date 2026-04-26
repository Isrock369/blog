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

// Cek apakah penulis masih memiliki artikel
$stmtCek = $koneksi->prepare("SELECT COUNT(*) AS total FROM artikel WHERE id_penulis = ?");
$stmtCek->bind_param('i', $id);
$stmtCek->execute();
$cek = $stmtCek->get_result()->fetch_assoc();
$stmtCek->close();

if ($cek['total'] > 0) {
    echo json_encode(['status' => 'error', 'pesan' => 'Penulis tidak dapat dihapus karena masih memiliki artikel.']);
    exit;
}

// Ambil nama foto sebelum dihapus
$stmtFoto = $koneksi->prepare("SELECT foto FROM penulis WHERE id = ?");
$stmtFoto->bind_param('i', $id);
$stmtFoto->execute();
$dataFoto = $stmtFoto->get_result()->fetch_assoc();
$stmtFoto->close();

$stmt = $koneksi->prepare("DELETE FROM penulis WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    hapusGambar($dataFoto['foto'], 'uploads_penulis');
    echo json_encode(['status' => 'sukses', 'pesan' => 'Penulis berhasil dihapus.']);
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Gagal menghapus data.']);
}

$stmt->close();
$koneksi->close();
