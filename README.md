# Sistem Manajemen Blog (CMS)

> UTS Pemrograman Web — Semester Genap 2025/2026
> Dosen: A'la Syauqi, M.Kom.

---

## Deskripsi

Aplikasi web berbasis PHP untuk mengelola data penulis, artikel, dan kategori artikel secara lengkap. Seluruh operasi CRUD berjalan secara **asynchronous** menggunakan **Fetch API** tanpa reload halaman.

---

## Teknologi yang Digunakan

- **PHP** — Backend & logika server
- **MySQL / MariaDB** — Database
- **JavaScript (Fetch API)** — Operasi asynchronous
- **HTML & CSS** — Tampilan antarmuka
- **XAMPP** — Local development server

---

## Fitur

### Kelola Penulis
- Menampilkan data penulis dalam format tabel
- Tambah penulis baru dengan foto profil
- Edit data penulis (password & foto opsional)
- Hapus penulis (tidak bisa dihapus jika masih memiliki artikel)
- Password dienkripsi menggunakan `password_hash()` dengan algoritma `PASSWORD_BCRYPT`
- Foto default otomatis jika tidak mengunggah foto

### Kelola Artikel
- Menampilkan data artikel lengkap dengan penulis dan kategori
- Tambah artikel baru dengan upload gambar
- Edit data artikel (gambar opsional)
- Hapus artikel beserta file gambarnya dari server
- Tanggal otomatis dari server dengan format Indonesia dan timezone Asia/Jakarta
- Dropdown penulis dan kategori diambil dari database

### Kelola Kategori Artikel
- Menampilkan data kategori dalam format tabel
- Tambah kategori baru
- Edit data kategori
- Hapus kategori (tidak bisa dihapus jika masih digunakan artikel)

---

## Struktur Folder

```
blog/
├── index.php
├── koneksi.php
├── helper.php
│
├── ambil_penulis.php
├── simpan_penulis.php
├── ambil_satu_penulis.php
├── update_penulis.php
├── hapus_penulis.php
│
├── ambil_kategori.php
├── simpan_kategori.php
├── ambil_satu_kategori.php
├── update_kategori.php
├── hapus_kategori.php
│
├── ambil_artikel.php
├── simpan_artikel.php
├── ambil_satu_artikel.php
├── update_artikel.php
├── hapus_artikel.php
│
├── uploads_penulis/
│   ├── .htaccess
│   └── default.png
│
└── uploads_artikel/
    └── .htaccess
```

---

## Cara Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/Isrock369/blog.git
```

### 2. Pindahkan ke htdocs XAMPP
```bash
cp -r blog/ /Applications/XAMPP/xamppfiles/htdocs/
```

### 3. Import Database
Jalankan perintah berikut di terminal:
```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root blog < blog/db_blog.sql
```

Atau buat tabel manual melalui phpMyAdmin dengan menjalankan file `db_blog.sql`.

### 4. Sesuaikan Koneksi Database
Edit file `koneksi.php`:
```php
$host     = 'localhost';
$user     = 'root';
$password = '';       // sesuaikan password MySQL
$database = 'blog';  // sesuaikan nama database
```

### 5. Jalankan Aplikasi
Pastikan Apache dan MySQL sudah aktif di XAMPP, lalu buka browser:
```
http://localhost/blog/
```

---

## Keamanan

- Seluruh query database menggunakan **Prepared Statements** dengan `mysqli`
- Validasi tipe file menggunakan fungsi `finfo` (bukan `$_FILES['type']`)
- Ukuran file maksimal **2 MB**
- Output di-sanitasi menggunakan `htmlspecialchars()`
- Folder `uploads_penulis/` dan `uploads_artikel/` dilindungi `.htaccess` untuk mencegah eksekusi file PHP

---

## Database

Nama database: `blog`

| Tabel | Keterangan |
|-------|-----------|
| `penulis` | Data penulis blog |
| `kategori_artikel` | Data kategori artikel |
| `artikel` | Data artikel blog |

---

## Demo

- 🎥 **Video Demo:** [Link YouTube](#)
- 💻 **Repository:** [[Link GitHub]](https://github.com/Isrock369/blog.git)(#)
