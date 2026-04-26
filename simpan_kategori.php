<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'pesan' => 'Method tidak diizinkan.']);
    exit;
}

$nama_kategori = trim(htmlspecialchars($_POST['nama_kategori'] ?? '', ENT_QUOTES, 'UTF-8'));
$keterangan    = trim(htmlspecialchars($_POST['keterangan'] ?? '', ENT_QUOTES, 'UTF-8'));

if (!$nama_kategori) {
    echo json_encode(['status' => 'error', 'pesan' => 'Nama kategori wajib diisi.']);
    exit;
}

$stmt = $koneksi->prepare("INSERT INTO kategori_artikel (nama_kategori, keterangan) VALUES (?, ?)");
$stmt->bind_param('ss', $nama_kategori, $keterangan);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Kategori berhasil ditambahkan.']);
} else {
    if ($koneksi->errno === 1062) {
        echo json_encode(['status' => 'error', 'pesan' => 'Nama kategori sudah ada.']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal menyimpan: ' . $koneksi->error]);
    }
}

$stmt->close();
$koneksi->close();
