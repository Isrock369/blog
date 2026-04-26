<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'koneksi.php';
require_once 'helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'pesan' => 'Method tidak diizinkan.']);
    exit;
}

$id            = (int)($_POST['id'] ?? 0);
$nama_depan    = trim(htmlspecialchars($_POST['nama_depan'] ?? '', ENT_QUOTES, 'UTF-8'));
$nama_belakang = trim(htmlspecialchars($_POST['nama_belakang'] ?? '', ENT_QUOTES, 'UTF-8'));
$user_name     = trim(htmlspecialchars($_POST['user_name'] ?? '', ENT_QUOTES, 'UTF-8'));
$password_baru = $_POST['password_baru'] ?? '';

if (!$id || !$nama_depan || !$nama_belakang || !$user_name) {
    echo json_encode(['status' => 'error', 'pesan' => 'Field wajib tidak boleh kosong.']);
    exit;
}

// Ambil data lama untuk mendapatkan foto saat ini
$stmtLama = $koneksi->prepare("SELECT foto FROM penulis WHERE id = ?");
$stmtLama->bind_param('i', $id);
$stmtLama->execute();
$result   = $stmtLama->get_result();
$dataLama = $result->fetch_assoc();
$stmtLama->close();

if (!$dataLama) {
    echo json_encode(['status' => 'error', 'pesan' => 'Data penulis tidak ditemukan.']);
    exit;
}

// Proses foto (opsional saat edit)
$namaFoto = $dataLama['foto'];
if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
    $validasi = validasiGambar($_FILES['foto']);
    if (!$validasi['ok']) {
        echo json_encode(['status' => 'error', 'pesan' => $validasi['pesan']]);
        exit;
    }
    hapusGambar($namaFoto, 'uploads_penulis');
    $namaFoto = uploadGambar($_FILES['foto'], 'uploads_penulis');
}

// Update password hanya jika diisi
if (!empty($password_baru)) {
    $password_hash = password_hash($password_baru, PASSWORD_BCRYPT);
    $stmt = $koneksi->prepare(
        "UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, password=?, foto=? WHERE id=?"
    );
    $stmt->bind_param('sssssi', $nama_depan, $nama_belakang, $user_name, $password_hash, $namaFoto, $id);
} else {
    $stmt = $koneksi->prepare(
        "UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, foto=? WHERE id=?"
    );
    $stmt->bind_param('ssssi', $nama_depan, $nama_belakang, $user_name, $namaFoto, $id);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'sukses', 'pesan' => 'Data penulis berhasil diperbarui.']);
} else {
    if ($koneksi->errno === 1062) {
        echo json_encode(['status' => 'error', 'pesan' => 'Username sudah digunakan oleh penulis lain.']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal memperbarui data: ' . $koneksi->error]);
    }
}

$stmt->close();
$koneksi->close();
