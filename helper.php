<?php
function validasiGambar(array $file): array
{
    $maxSize  = 2 * 1024 * 1024; // 2 MB
    $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'pesan' => 'Terjadi kesalahan saat upload file.'];
    }

    if ($file['size'] > $maxSize) {
        return ['ok' => false, 'pesan' => 'Ukuran file melebihi batas 2 MB.'];
    }

    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if (!in_array($mimeType, $allowed, true)) {
        return ['ok' => false, 'pesan' => 'Tipe file tidak diizinkan. Hanya JPG, PNG, GIF, WEBP.'];
    }

    return ['ok' => true, 'mime' => $mimeType];
}

/**
 * Upload file gambar ke folder tujuan, kembalikan nama file baru
 */
function uploadGambar(array $file, string $folder): string
{
    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
    $namaFile = uniqid('img_', true) . '.' . strtolower($ext);
    $tujuan   = $folder . '/' . $namaFile;
    move_uploaded_file($file['tmp_name'], $tujuan);
    return $namaFile;
}

/**
 * Hapus file gambar dari server (tidak hapus default.png)
 */
function hapusGambar(string $namaFile, string $folder): void
{
    if ($namaFile === 'default.png' || $namaFile === 'default_artikel.png') return;
    $path = $folder . '/' . $namaFile;
    if (file_exists($path)) {
        unlink($path);
    }
}

/**
 * Format tanggal Indonesia dengan timezone Asia/Jakarta
 */
function formatHariTanggal(): string
{
    date_default_timezone_set('Asia/Jakarta');
    $hari   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $bulan  = [
        1=>'Januari', 2=>'Februari', 3=>'Maret',
        4=>'April',   5=>'Mei',      6=>'Juni',
        7=>'Juli',    8=>'Agustus',  9=>'September',
        10=>'Oktober',11=>'November',12=>'Desember'
    ];
    $sekarang   = new DateTime();
    $nama_hari  = $hari[$sekarang->format('w')];
    $tanggal    = $sekarang->format('j');
    $nama_bulan = $bulan[(int)$sekarang->format('n')];
    $tahun      = $sekarang->format('Y');
    $jam        = $sekarang->format('H:i');
    return "$nama_hari, $tanggal $nama_bulan $tahun | $jam";
}
