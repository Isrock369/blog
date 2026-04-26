<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'pesan' => 'Method tidak diizinkan.']);
    exit;
}

$id            = (int)($_POST['id'] ?? 0);
$nama_kategori = trim(htmlspecialchars($_POST['nama_kategori'] ?? '', ENT_QUOTES, 'UTF-8'));
$keterangan    = trim(htmlspecialchars($_POST['keterangan'] ?? '', ENT_QUOTES, 'UTF-8'));

if (!$id || !$nama_kategori) {
    echo json_encode(['status' => 'error', 'pesan' => 'ID dan nama kategori wajib diisi.']);
    exit;
}

$stmt = $koneksi->prepare("UPDATE kategori_artikel SET nama_kategori=?, keterangan=? WHERE id=?");
$stmt->bind_param('ssi', $nama_kategori, $keterangan, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Kategori berhasil diperbarui.']);
} else {
    if ($koneksi->errno === 1062) {
        echo json_encode(['status' => 'error', 'pesan' => 'Nama kategori sudah digunakan.']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal memperbarui: ' . $koneksi->error]);
    }
}

$stmt->close();
$koneksi->close();
