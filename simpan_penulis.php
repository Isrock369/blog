<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'koneksi.php';
require_once 'helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'pesan' => 'Method tidak diizinkan.']);
    exit;
}

$nama_depan   = trim(htmlspecialchars($_POST['nama_depan'] ?? '', ENT_QUOTES, 'UTF-8'));
$nama_belakang= trim(htmlspecialchars($_POST['nama_belakang'] ?? '', ENT_QUOTES, 'UTF-8'));
$user_name    = trim(htmlspecialchars($_POST['user_name'] ?? '', ENT_QUOTES, 'UTF-8'));
$password_raw = $_POST['password'] ?? '';

if (!$nama_depan || !$nama_belakang || !$user_name || !$password_raw) {
    echo json_encode(['status' => 'error', 'pesan' => 'Semua field wajib diisi.']);
    exit;
}

// Enkripsi password dengan bcrypt
$password_hash = password_hash($password_raw, PASSWORD_BCRYPT);

// Proses foto profil
$namaFoto = 'default.png';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
    $validasi = validasiGambar($_FILES['foto']);
    if (!$validasi['ok']) {
        echo json_encode(['status' => 'error', 'pesan' => $validasi['pesan']]);
        exit;
    }
    $namaFoto = uploadGambar($_FILES['foto'], 'uploads_penulis');
}

$stmt = $koneksi->prepare(
    "INSERT INTO penulis (nama_depan, nama_belakang, user_name, password, foto) VALUES (?, ?, ?, ?, ?)"
);
$stmt->bind_param('sssss', $nama_depan, $nama_belakang, $user_name, $password_hash, $namaFoto);

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Penulis berhasil ditambahkan.']);
} else {
    // Cek duplicate username
    if ($koneksi->errno === 1062) {
        echo json_encode(['status' => 'error', 'pesan' => 'Username sudah digunakan.']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal menyimpan data: ' . $koneksi->error]);
    }
}

$stmt->close();
$koneksi->close();
