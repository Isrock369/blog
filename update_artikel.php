<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'koneksi.php';
require_once 'helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'pesan' => 'Method tidak diizinkan.']);
    exit;
}

$id          = (int)($_POST['id'] ?? 0);
$id_penulis  = (int)($_POST['id_penulis'] ?? 0);
$id_kategori = (int)($_POST['id_kategori'] ?? 0);
$judul       = trim(htmlspecialchars($_POST['judul'] ?? '', ENT_QUOTES, 'UTF-8'));
$isi         = trim(htmlspecialchars($_POST['isi'] ?? '', ENT_QUOTES, 'UTF-8'));

if (!$id || !$id_penulis || !$id_kategori || !$judul || !$isi) {
    echo json_encode(['status' => 'error', 'pesan' => 'Semua field wajib diisi.']);
    exit;
}

$stmtLama = $koneksi->prepare("SELECT gambar FROM artikel WHERE id = ?");
$stmtLama->bind_param('i', $id);
$stmtLama->execute();
$dataLama = $stmtLama->get_result()->fetch_assoc();
$stmtLama->close();

if (!$dataLama) {
    echo json_encode(['status' => 'error', 'pesan' => 'Artikel tidak ditemukan.']);
    exit;
}

$namaGambar = $dataLama['gambar'];


if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
    $validasi = validasiGambar($_FILES['gambar']);
    if (!$validasi['ok']) {
        echo json_encode(['status' => 'error', 'pesan' => $validasi['pesan']]);
        exit;
    }
    hapusGambar($namaGambar, 'uploads_artikel');
    $namaGambar = uploadGambar($_FILES['gambar'], 'uploads_artikel');
}

$stmt = $koneksi->prepare(
    "UPDATE artikel SET id_penulis=?, id_kategori=?, judul=?, isi=?, gambar=? WHERE id=?"
);
$stmt->bind_param('iisssi', $id_penulis, $id_kategori, $judul, $isi, $namaGambar, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Artikel berhasil diperbarui.']);
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Gagal memperbarui: ' . $koneksi->error]);
}

$stmt->close();
$koneksi->close();
