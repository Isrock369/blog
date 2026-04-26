<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'koneksi.php';
require_once 'helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'pesan' => 'Method tidak diizinkan.']);
    exit;
}

$id_penulis  = (int)($_POST['id_penulis'] ?? 0);
$id_kategori = (int)($_POST['id_kategori'] ?? 0);
$judul       = trim(htmlspecialchars($_POST['judul'] ?? '', ENT_QUOTES, 'UTF-8'));
$isi         = trim(htmlspecialchars($_POST['isi'] ?? '', ENT_QUOTES, 'UTF-8'));

if (!$id_penulis || !$id_kategori || !$judul || !$isi) {
    echo json_encode(['status' => 'error', 'pesan' => 'Semua field wajib diisi.']);
    exit;
}

// Gambar wajib diunggah untuk artikel baru
if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] === UPLOAD_ERR_NO_FILE) {
    echo json_encode(['status' => 'error', 'pesan' => 'Gambar artikel wajib diunggah.']);
    exit;
}

$validasi = validasiGambar($_FILES['gambar']);
if (!$validasi['ok']) {
    echo json_encode(['status' => 'error', 'pesan' => $validasi['pesan']]);
    exit;
}

$namaGambar   = uploadGambar($_FILES['gambar'], 'uploads_artikel');
$hari_tanggal = formatHariTanggal();

$stmt = $koneksi->prepare(
    "INSERT INTO artikel (id_penulis, id_kategori, judul, isi, gambar, hari_tanggal) VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param('iissss', $id_penulis, $id_kategori, $judul, $isi, $namaGambar, $hari_tanggal);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Artikel berhasil ditambahkan.']);
} else {
    hapusGambar($namaGambar, 'uploads_artikel');
    echo json_encode(['status' => 'error', 'pesan' => 'Gagal menyimpan artikel: ' . $koneksi->error]);
}

$stmt->close();
$koneksi->close();
