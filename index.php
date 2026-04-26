<?php
// index.php - Halaman Utama Sistem Manajemen Blog (CMS)
// UTS Pemrograman Web | Dosen: A'la Syauqi, M.Kom.
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Manajemen Blog (CMS)</title>
<style>
/* ── Reset ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; color: #333; font-size: 14px; }
a { text-decoration: none; color: inherit; }

/* ── Layout ── */
.wrapper { display: flex; flex-direction: column; min-height: 100vh; }

/* Header */
.header {
    background: #fff;
    border-bottom: 1px solid #e0e0e0;
    padding: 0 24px;
    height: 56px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
    position: sticky; top: 0; z-index: 100;
}
.header-icon { font-size: 22px; }
.header h1 { font-size: 16px; font-weight: 700; color: #1a1a2e; }
.header small { font-size: 12px; color: #888; }

/* Body layout */
.body-layout { display: flex; flex: 1; }

/* Sidebar */
.sidebar {
    width: 210px;
    background: #fff;
    border-right: 1px solid #e0e0e0;
    padding: 20px 0;
    min-height: calc(100vh - 56px);
    flex-shrink: 0;
}
.sidebar-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #aaa;
    padding: 0 18px 8px;
}
.nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 18px;
    cursor: pointer;
    border-left: 3px solid transparent;
    color: #555;
    transition: all .15s;
    font-size: 13.5px;
}
.nav-item:hover { background: #f5f7ff; color: #2563eb; }
.nav-item.active {
    background: #eff6ff;
    border-left-color: #2563eb;
    color: #2563eb;
    font-weight: 600;
}
.nav-item .nav-icon { font-size: 16px; width: 20px; text-align: center; }

/* Content */
.content { flex: 1; padding: 24px; overflow-x: auto; }

/* Section */
.section { display: none; }
.section.active { display: block; }

/* Card */
.card {
    background: #fff;
    border-radius: 8px;
    border: 1px solid #e8e8e8;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f0f0;
}
.card-header h2 { font-size: 15px; font-weight: 700; color: #1a1a2e; }

/* Table */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead { background: #f8f9fb; }
thead th {
    padding: 11px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .8px;
    text-transform: uppercase;
    color: #888;
    border-bottom: 1px solid #eee;
    white-space: nowrap;
}
tbody td {
    padding: 12px 16px;
    border-bottom: 1px solid #f3f3f3;
    vertical-align: middle;
    font-size: 13.5px;
}
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover { background: #fafbff; }

/* Foto thumbnail */
.thumb {
    width: 44px;
    height: 44px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #e0e0e0;
    background: #f0f0f0;
}

/* Kategori badge */
.badge-kategori {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: #dbeafe;
    color: #1d4ed8;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 14px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    font-family: inherit;
    transition: all .15s;
    white-space: nowrap;
}
.btn-primary { background: #2563eb; color: #fff; }
.btn-primary:hover { background: #1d4ed8; }
.btn-success { background: #16a34a; color: #fff; }
.btn-success:hover { background: #15803d; }
.btn-warning { background: #f59e0b; color: #fff; }
.btn-warning:hover { background: #d97706; }
.btn-danger  { background: #dc2626; color: #fff; }
.btn-danger:hover  { background: #b91c1c; }
.btn-secondary { background: #e5e7eb; color: #374151; }
.btn-secondary:hover { background: #d1d5db; }
.btn-sm { padding: 5px 10px; font-size: 12px; }

/* Loading state */
.loading-row td { text-align: center; padding: 32px; color: #aaa; }

/* Empty state */
.empty-row td { text-align: center; padding: 40px; color: #bbb; }

/* ── MODAL ── */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 500;
    align-items: center;
    justify-content: center;
}
.modal-overlay.show { display: flex; }

.modal {
    background: #fff;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 8px 40px rgba(0,0,0,.18);
    animation: modalIn .2s ease;
}
.modal-lg { max-width: 600px; }
.modal-sm { max-width: 380px; }

@keyframes modalIn { from { transform: scale(.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }

.modal-header {
    padding: 18px 22px 14px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.modal-header h3 { font-size: 15px; font-weight: 700; }
.modal-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #999;
    line-height: 1;
    padding: 0 4px;
}
.modal-close:hover { color: #333; }
.modal-body { padding: 20px 22px; }
.modal-footer {
    padding: 14px 22px;
    border-top: 1px solid #f0f0f0;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
}

/* Confirm modal */
.confirm-body {
    text-align: center;
    padding: 28px 22px 20px;
}
.confirm-icon {
    font-size: 40px;
    margin-bottom: 12px;
}
.confirm-body h3 { font-size: 16px; font-weight: 700; margin-bottom: 6px; }
.confirm-body p  { font-size: 13px; color: #888; }

/* Form */
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-group { margin-bottom: 14px; }
.form-group:last-child { margin-bottom: 0; }
.form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #555;
    margin-bottom: 5px;
}
.form-label .req { color: #dc2626; }
.form-control {
    width: 100%;
    padding: 8px 11px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-family: inherit;
    font-size: 13.5px;
    color: #333;
    outline: none;
    transition: border-color .15s, box-shadow .15s;
    background: #fff;
}
.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
}
textarea.form-control { resize: vertical; min-height: 80px; }
.form-hint { font-size: 11px; color: #aaa; margin-top: 4px; }

/* Foto preview */
.foto-preview {
    width: 56px;
    height: 56px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #e0e0e0;
    margin-top: 6px;
    display: block;
}

/* Alert */
.alert {
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 13px;
    margin-bottom: 14px;
    display: none;
}
.alert.show { display: block; }
.alert-danger  { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
.alert-success { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }

/* Toast */
.toast-container {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.toast {
    background: #1e293b;
    color: #fff;
    padding: 12px 18px;
    border-radius: 8px;
    font-size: 13px;
    box-shadow: 0 4px 16px rgba(0,0,0,.2);
    display: flex;
    align-items: center;
    gap: 8px;
    animation: toastIn .25s ease;
    max-width: 320px;
}
.toast.toast-success { background: #16a34a; }
.toast.toast-error   { background: #dc2626; }
@keyframes toastIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

/* Password mask */
.pass-mask { letter-spacing: 2px; font-family: monospace; color: #888; }

/* Spinner */
.spinner { display: inline-block; width: 16px; height: 16px; border: 2px solid #fff; border-top-color: transparent; border-radius: 50%; animation: spin .6s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>
</head>
<body>
<div class="wrapper">

  <!-- ── HEADER ────────────────────────────────────────────── -->
  <header class="header">
    <span class="header-icon">📰</span>
    <div>
      <h1>Sistem Manajemen Blog (CMS)</h1>
      <small>Blog Kami</small>
    </div>
  </header>

  <div class="body-layout">

    <!-- ── SIDEBAR ────────────────────────────────────────── -->
    <aside class="sidebar">
      <div class="sidebar-label">Menu Utama</div>
      <div class="nav-item active" data-section="penulis">
        <span class="nav-icon">👤</span> Kelola Penulis
      </div>
      <div class="nav-item" data-section="artikel">
        <span class="nav-icon">📄</span> Kelola Artikel
      </div>
      <div class="nav-item" data-section="kategori">
        <span class="nav-icon">🗂️</span> Kelola Kategori
      </div>
    </aside>

    <!-- ── MAIN CONTENT ───────────────────────────────────── -->
    <main class="content">

      <!-- ═══════ SECTION: PENULIS ═══════ -->
      <section id="section-penulis" class="section active">
        <div class="card">
          <div class="card-header">
            <h2>Data Penulis</h2>
            <button class="btn btn-primary" onclick="bukaTambahPenulis()">
              + Tambah Penulis
            </button>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Foto</th>
                  <th>Nama</th>
                  <th>Username</th>
                  <th>Password</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="tbody-penulis">
                <tr class="loading-row"><td colspan="5">⏳ Memuat data…</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- ═══════ SECTION: ARTIKEL ═══════ -->
      <section id="section-artikel" class="section">
        <div class="card">
          <div class="card-header">
            <h2>Data Artikel</h2>
            <button class="btn btn-primary" onclick="bukaTambahArtikel()">
              + Tambah Artikel
            </button>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Gambar</th>
                  <th>Judul</th>
                  <th>Kategori</th>
                  <th>Penulis</th>
                  <th>Tanggal</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="tbody-artikel">
                <tr class="loading-row"><td colspan="6">⏳ Memuat data…</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- ═══════ SECTION: KATEGORI ═══════ -->
      <section id="section-kategori" class="section">
        <div class="card">
          <div class="card-header">
            <h2>Data Kategori Artikel</h2>
            <button class="btn btn-primary" onclick="bukaTambahKategori()">
              + Tambah Kategori
            </button>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Nama Kategori</th>
                  <th>Keterangan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="tbody-kategori">
                <tr class="loading-row"><td colspan="3">⏳ Memuat data…</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

    </main>
  </div>
</div><!-- .wrapper -->

<!-- ══════════════════════════════════════════════════════════
     MODAL: TAMBAH PENULIS
══════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-tambah-penulis">
  <div class="modal">
    <div class="modal-header">
      <h3>Tambah Penulis</h3>
      <button class="modal-close" onclick="tutupModal('modal-tambah-penulis')">✕</button>
    </div>
    <div class="modal-body">
      <div id="alert-tambah-penulis" class="alert alert-danger"></div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Nama Depan <span class="req">*</span></label>
          <input type="text" class="form-control" id="tp-nama-depan" placeholder="Ahmad">
        </div>
        <div class="form-group">
          <label class="form-label">Nama Belakang <span class="req">*</span></label>
          <input type="text" class="form-control" id="tp-nama-belakang" placeholder="Fauzi">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Username <span class="req">*</span></label>
        <input type="text" class="form-control" id="tp-username" placeholder="ahmad_f">
      </div>
      <div class="form-group">
        <label class="form-label">Password <span class="req">*</span></label>
        <input type="password" class="form-control" id="tp-password" placeholder="••••••••••••">
      </div>
      <div class="form-group">
        <label class="form-label">Foto Profil</label>
        <input type="file" class="form-control" id="tp-foto" accept="image/*" onchange="previewFoto(this,'tp-preview')">
        <img id="tp-preview" class="foto-preview" src="" style="display:none" alt="preview">
        <div class="form-hint">Opsional. Maks 2 MB. Format: JPG, PNG, GIF, WEBP</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-tambah-penulis')">Batal</button>
      <button class="btn btn-success" id="btn-simpan-penulis" onclick="simpanPenulis()">Simpan Data</button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     MODAL: EDIT PENULIS
══════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-edit-penulis">
  <div class="modal">
    <div class="modal-header">
      <h3>Edit Penulis</h3>
      <button class="modal-close" onclick="tutupModal('modal-edit-penulis')">✕</button>
    </div>
    <div class="modal-body">
      <div id="alert-edit-penulis" class="alert alert-danger"></div>
      <input type="hidden" id="ep-id">
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Nama Depan <span class="req">*</span></label>
          <input type="text" class="form-control" id="ep-nama-depan">
        </div>
        <div class="form-group">
          <label class="form-label">Nama Belakang <span class="req">*</span></label>
          <input type="text" class="form-control" id="ep-nama-belakang">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Username <span class="req">*</span></label>
        <input type="text" class="form-control" id="ep-username">
      </div>
      <div class="form-group">
        <label class="form-label">Password Baru <span style="color:#aaa;font-weight:400">(kosongkan jika tidak diganti)</span></label>
        <input type="password" class="form-control" id="ep-password" placeholder="••••••••••••">
      </div>
      <div class="form-group">
        <label class="form-label">Foto Profil <span style="color:#aaa;font-weight:400">(kosongkan jika tidak diganti)</span></label>
        <input type="file" class="form-control" id="ep-foto" accept="image/*" onchange="previewFoto(this,'ep-preview')">
        <img id="ep-preview" class="foto-preview" src="" style="display:none" alt="preview">
        <div class="form-hint">Maks 2 MB. Format: JPG, PNG, GIF, WEBP</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-edit-penulis')">Batal</button>
      <button class="btn btn-success" id="btn-update-penulis" onclick="updatePenulis()">Simpan Perubahan</button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     MODAL: TAMBAH ARTIKEL
══════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-tambah-artikel">
  <div class="modal modal-lg">
    <div class="modal-header">
      <h3>Tambah Artikel</h3>
      <button class="modal-close" onclick="tutupModal('modal-tambah-artikel')">✕</button>
    </div>
    <div class="modal-body">
      <div id="alert-tambah-artikel" class="alert alert-danger"></div>
      <div class="form-group">
        <label class="form-label">Judul <span class="req">*</span></label>
        <input type="text" class="form-control" id="ta-judul" placeholder="Judul artikel...">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Penulis <span class="req">*</span></label>
          <select class="form-control" id="ta-penulis"></select>
        </div>
        <div class="form-group">
          <label class="form-label">Kategori <span class="req">*</span></label>
          <select class="form-control" id="ta-kategori"></select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Isi Artikel <span class="req">*</span></label>
        <textarea class="form-control" id="ta-isi" rows="4" placeholder="Tulis isi artikel di sini..."></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Gambar <span class="req">*</span></label>
        <input type="file" class="form-control" id="ta-gambar" accept="image/*" onchange="previewFoto(this,'ta-preview')">
        <img id="ta-preview" class="foto-preview" src="" style="display:none" alt="preview">
        <div class="form-hint">Wajib. Maks 2 MB. Format: JPG, PNG, GIF, WEBP</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-tambah-artikel')">Batal</button>
      <button class="btn btn-success" id="btn-simpan-artikel" onclick="simpanArtikel()">Simpan Data</button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     MODAL: EDIT ARTIKEL
══════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-edit-artikel">
  <div class="modal modal-lg">
    <div class="modal-header">
      <h3>Edit Artikel</h3>
      <button class="modal-close" onclick="tutupModal('modal-edit-artikel')">✕</button>
    </div>
    <div class="modal-body">
      <div id="alert-edit-artikel" class="alert alert-danger"></div>
      <input type="hidden" id="ea-id">
      <div class="form-group">
        <label class="form-label">Judul <span class="req">*</span></label>
        <input type="text" class="form-control" id="ea-judul">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Penulis <span class="req">*</span></label>
          <select class="form-control" id="ea-penulis"></select>
        </div>
        <div class="form-group">
          <label class="form-label">Kategori <span class="req">*</span></label>
          <select class="form-control" id="ea-kategori"></select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Isi Artikel <span class="req">*</span></label>
        <textarea class="form-control" id="ea-isi" rows="4"></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Gambar <span style="color:#aaa;font-weight:400">(kosongkan jika tidak diganti)</span></label>
        <input type="file" class="form-control" id="ea-gambar" accept="image/*" onchange="previewFoto(this,'ea-preview')">
        <img id="ea-preview" class="foto-preview" src="" style="display:none" alt="preview">
        <div class="form-hint">Maks 2 MB. Format: JPG, PNG, GIF, WEBP</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-edit-artikel')">Batal</button>
      <button class="btn btn-success" id="btn-update-artikel" onclick="updateArtikel()">Simpan Perubahan</button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     MODAL: TAMBAH KATEGORI
══════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-tambah-kategori">
  <div class="modal">
    <div class="modal-header">
      <h3>Tambah Kategori</h3>
      <button class="modal-close" onclick="tutupModal('modal-tambah-kategori')">✕</button>
    </div>
    <div class="modal-body">
      <div id="alert-tambah-kategori" class="alert alert-danger"></div>
      <div class="form-group">
        <label class="form-label">Nama Kategori <span class="req">*</span></label>
        <input type="text" class="form-control" id="tk-nama" placeholder="Nama kategori...">
      </div>
      <div class="form-group">
        <label class="form-label">Keterangan</label>
        <textarea class="form-control" id="tk-keterangan" rows="3" placeholder="Deskripsi kategori..."></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-tambah-kategori')">Batal</button>
      <button class="btn btn-success" id="btn-simpan-kategori" onclick="simpanKategori()">Simpan Data</button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     MODAL: EDIT KATEGORI
══════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-edit-kategori">
  <div class="modal">
    <div class="modal-header">
      <h3>Edit Kategori</h3>
      <button class="modal-close" onclick="tutupModal('modal-edit-kategori')">✕</button>
    </div>
    <div class="modal-body">
      <div id="alert-edit-kategori" class="alert alert-danger"></div>
      <input type="hidden" id="ek-id">
      <div class="form-group">
        <label class="form-label">Nama Kategori <span class="req">*</span></label>
        <input type="text" class="form-control" id="ek-nama">
      </div>
      <div class="form-group">
        <label class="form-label">Keterangan</label>
        <textarea class="form-control" id="ek-keterangan" rows="3"></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-edit-kategori')">Batal</button>
      <button class="btn btn-success" id="btn-update-kategori" onclick="updateKategori()">Simpan Perubahan</button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     MODAL: KONFIRMASI HAPUS
══════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-hapus">
  <div class="modal modal-sm">
    <div class="confirm-body">
      <div class="confirm-icon">🗑️</div>
      <h3>Hapus data ini?</h3>
      <p>Data yang dihapus tidak dapat dikembalikan.</p>
    </div>
    <div class="modal-footer" style="justify-content:center">
      <button class="btn btn-secondary" onclick="tutupModal('modal-hapus')">Batal</button>
      <button class="btn btn-danger" id="btn-konfirmasi-hapus">Ya, Hapus</button>
    </div>
  </div>
</div>

<!-- Toast container -->
<div class="toast-container" id="toast-container"></div>

<!-- ══════════════════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════════════════ -->
<script>
'use strict';

// ─── Navigasi ─────────────────────────────────────────────────────────
document.querySelectorAll('.nav-item[data-section]').forEach(el => {
    el.addEventListener('click', () => {
        const target = el.dataset.section;
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
        document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('section-' + target).classList.add('active');
        if (target === 'penulis')  muatPenulis();
        if (target === 'artikel')  muatArtikel();
        if (target === 'kategori') muatKategori();
    });
});

// ─── Tutup modal klik overlay ──────────────────────────────────────────
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => {
        if (e.target === overlay) tutupModal(overlay.id);
    });
});

// ─── Helpers ──────────────────────────────────────────────────────────
function bukaModal(id)  { document.getElementById(id).classList.add('show'); }
function tutupModal(id) { document.getElementById(id).classList.remove('show'); }

function tampilAlert(id, pesan) {
    const el = document.getElementById(id);
    el.textContent = pesan;
    el.classList.add('show');
}
function sembunyiAlert(id) {
    const el = document.getElementById(id);
    el.classList.remove('show');
}

function tampilToast(pesan, tipe = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast toast-${tipe}`;
    toast.innerHTML = (tipe === 'success' ? '✅ ' : '❌ ') + pesan;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function setLoading(btnId, loading) {
    const btn = document.getElementById(btnId);
    if (!btn) return;
    if (loading) {
        btn.dataset.originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner"></span> Menyimpan...';
        btn.disabled = true;
    } else {
        btn.innerHTML = btn.dataset.originalText || 'Simpan';
        btn.disabled = false;
    }
}

function previewFoto(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function esc(str) {
    const d = document.createElement('div');
    d.textContent = str || '';
    return d.innerHTML;
}

// ═══════════════════════════════════════════════════════════════
// PENULIS
// ═══════════════════════════════════════════════════════════════

async function muatPenulis() {
    document.getElementById('tbody-penulis').innerHTML =
        '<tr class="loading-row"><td colspan="5">⏳ Memuat data...</td></tr>';
    const res  = await fetch('ambil_penulis.php');
    const json = await res.json();
    const tbody = document.getElementById('tbody-penulis');

    if (!json.data || json.data.length === 0) {
        tbody.innerHTML = '<tr class="empty-row"><td colspan="5">Belum ada data penulis.</td></tr>';
        return;
    }

    tbody.innerHTML = json.data.map(p => `
        <tr>
            <td>
                <img src="uploads_penulis/${esc(p.foto)}"
                     onerror="this.src='uploads_penulis/default.png'"
                     class="thumb" alt="foto">
            </td>
            <td>${esc(p.nama_depan)} ${esc(p.nama_belakang)}</td>
            <td>${esc(p.user_name)}</td>
            <td><span class="pass-mask">${p.password.substring(0,18)}...</span></td>
            <td>
                <button class="btn btn-warning btn-sm" onclick="bukaEditPenulis(${p.id})">Edit</button>
                <button class="btn btn-danger btn-sm"  onclick="konfirmasiHapus('penulis',${p.id})">Hapus</button>
            </td>
        </tr>`).join('');
}

function bukaTambahPenulis() {
    sembunyiAlert('alert-tambah-penulis');
    document.getElementById('tp-nama-depan').value  = '';
    document.getElementById('tp-nama-belakang').value = '';
    document.getElementById('tp-username').value    = '';
    document.getElementById('tp-password').value    = '';
    document.getElementById('tp-foto').value        = '';
    document.getElementById('tp-preview').style.display = 'none';
    bukaModal('modal-tambah-penulis');
}

async function simpanPenulis() {
    sembunyiAlert('alert-tambah-penulis');
    const namaDepan   = document.getElementById('tp-nama-depan').value.trim();
    const namaBelakang= document.getElementById('tp-nama-belakang').value.trim();
    const username    = document.getElementById('tp-username').value.trim();
    const password    = document.getElementById('tp-password').value;

    if (!namaDepan || !namaBelakang || !username || !password) {
        tampilAlert('alert-tambah-penulis', 'Semua field wajib diisi.');
        return;
    }

    const fd = new FormData();
    fd.append('nama_depan',    namaDepan);
    fd.append('nama_belakang', namaBelakang);
    fd.append('user_name',     username);
    fd.append('password',      password);
    const fotoFile = document.getElementById('tp-foto').files[0];
    if (fotoFile) fd.append('foto', fotoFile);

    setLoading('btn-simpan-penulis', true);
    const res  = await fetch('simpan_penulis.php', { method: 'POST', body: fd });
    const json = await res.json();
    setLoading('btn-simpan-penulis', false);

    if (json.status === 'sukses') {
        tutupModal('modal-tambah-penulis');
        tampilToast(json.pesan);
        muatPenulis();
    } else {
        tampilAlert('alert-tambah-penulis', json.pesan);
    }
}

async function bukaEditPenulis(id) {
    sembunyiAlert('alert-edit-penulis');
    const res  = await fetch('ambil_satu_penulis.php?id=' + id);
    const json = await res.json();
    if (json.status !== 'sukses') { tampilToast(json.pesan, 'error'); return; }
    const p = json.data;
    document.getElementById('ep-id').value           = p.id;
    document.getElementById('ep-nama-depan').value   = p.nama_depan;
    document.getElementById('ep-nama-belakang').value= p.nama_belakang;
    document.getElementById('ep-username').value     = p.user_name;
    document.getElementById('ep-password').value     = '';
    document.getElementById('ep-foto').value         = '';
    document.getElementById('ep-preview').style.display = 'none';
    bukaModal('modal-edit-penulis');
}

async function updatePenulis() {
    sembunyiAlert('alert-edit-penulis');
    const id          = document.getElementById('ep-id').value;
    const namaDepan   = document.getElementById('ep-nama-depan').value.trim();
    const namaBelakang= document.getElementById('ep-nama-belakang').value.trim();
    const username    = document.getElementById('ep-username').value.trim();

    if (!namaDepan || !namaBelakang || !username) {
        tampilAlert('alert-edit-penulis', 'Nama depan, nama belakang, dan username wajib diisi.');
        return;
    }

    const fd = new FormData();
    fd.append('id',            id);
    fd.append('nama_depan',    namaDepan);
    fd.append('nama_belakang', namaBelakang);
    fd.append('user_name',     username);
    fd.append('password_baru', document.getElementById('ep-password').value);
    const fotoFile = document.getElementById('ep-foto').files[0];
    if (fotoFile) fd.append('foto', fotoFile);

    setLoading('btn-update-penulis', true);
    const res  = await fetch('update_penulis.php', { method: 'POST', body: fd });
    const json = await res.json();
    setLoading('btn-update-penulis', false);

    if (json.status === 'sukses') {
        tutupModal('modal-edit-penulis');
        tampilToast(json.pesan);
        muatPenulis();
    } else {
        tampilAlert('alert-edit-penulis', json.pesan);
    }
}

// ═══════════════════════════════════════════════════════════════
// ARTIKEL
// ═══════════════════════════════════════════════════════════════

async function muatArtikel() {
    document.getElementById('tbody-artikel').innerHTML =
        '<tr class="loading-row"><td colspan="6">⏳ Memuat data...</td></tr>';
    const res  = await fetch('ambil_artikel.php');
    const json = await res.json();
    const tbody = document.getElementById('tbody-artikel');

    if (!json.data || json.data.length === 0) {
        tbody.innerHTML = '<tr class="empty-row"><td colspan="6">Belum ada data artikel.</td></tr>';
        return;
    }

    tbody.innerHTML = json.data.map(a => `
        <tr>
            <td>
                <img src="uploads_artikel/${esc(a.gambar)}"
                     onerror="this.src='uploads_penulis/default.png'"
                     class="thumb" alt="gambar">
            </td>
            <td style="max-width:200px;white-space:normal">${esc(a.judul)}</td>
            <td><span class="badge-kategori">${esc(a.nama_kategori)}</span></td>
            <td>${esc(a.nama_penulis)}</td>
            <td style="font-size:12px;color:#666;white-space:nowrap">${esc(a.hari_tanggal)}</td>
            <td>
                <button class="btn btn-warning btn-sm" onclick="bukaEditArtikel(${a.id})">Edit</button>
                <button class="btn btn-danger btn-sm"  onclick="konfirmasiHapus('artikel',${a.id})">Hapus</button>
            </td>
        </tr>`).join('');
}

async function isiDropdown(selectId, url, valueKey, labelFn, selectedVal = '') {
    const select = document.getElementById(selectId);
    select.innerHTML = '<option value="">-- Pilih --</option>';
    const res  = await fetch(url);
    const json = await res.json();
    if (json.data) {
        json.data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item[valueKey];
            opt.textContent = labelFn(item);
            if (String(item[valueKey]) === String(selectedVal)) opt.selected = true;
            select.appendChild(opt);
        });
    }
}

async function bukaTambahArtikel() {
    sembunyiAlert('alert-tambah-artikel');
    document.getElementById('ta-judul').value = '';
    document.getElementById('ta-isi').value   = '';
    document.getElementById('ta-gambar').value = '';
    document.getElementById('ta-preview').style.display = 'none';
    await Promise.all([
        isiDropdown('ta-penulis',  'ambil_penulis.php',  'id', p => p.nama_depan + ' ' + p.nama_belakang),
        isiDropdown('ta-kategori', 'ambil_kategori.php', 'id', k => k.nama_kategori),
    ]);
    bukaModal('modal-tambah-artikel');
}

async function simpanArtikel() {
    sembunyiAlert('alert-tambah-artikel');
    const judul    = document.getElementById('ta-judul').value.trim();
    const penulis  = document.getElementById('ta-penulis').value;
    const kategori = document.getElementById('ta-kategori').value;
    const isi      = document.getElementById('ta-isi').value.trim();
    const gambar   = document.getElementById('ta-gambar').files[0];

    if (!judul || !penulis || !kategori || !isi) {
        tampilAlert('alert-tambah-artikel', 'Semua field wajib diisi.'); return;
    }
    if (!gambar) {
        tampilAlert('alert-tambah-artikel', 'Gambar artikel wajib diunggah.'); return;
    }

    const fd = new FormData();
    fd.append('judul',       judul);
    fd.append('id_penulis',  penulis);
    fd.append('id_kategori', kategori);
    fd.append('isi',         isi);
    fd.append('gambar',      gambar);

    setLoading('btn-simpan-artikel', true);
    const res  = await fetch('simpan_artikel.php', { method: 'POST', body: fd });
    const json = await res.json();
    setLoading('btn-simpan-artikel', false);

    if (json.status === 'sukses') {
        tutupModal('modal-tambah-artikel');
        tampilToast(json.pesan);
        muatArtikel();
    } else {
        tampilAlert('alert-tambah-artikel', json.pesan);
    }
}

async function bukaEditArtikel(id) {
    sembunyiAlert('alert-edit-artikel');
    const res  = await fetch('ambil_satu_artikel.php?id=' + id);
    const json = await res.json();
    if (json.status !== 'sukses') { tampilToast(json.pesan, 'error'); return; }
    const a = json.data;
    document.getElementById('ea-id').value    = a.id;
    document.getElementById('ea-judul').value = a.judul;
    document.getElementById('ea-isi').value   = a.isi;
    document.getElementById('ea-gambar').value = '';
    document.getElementById('ea-preview').style.display = 'none';
    await Promise.all([
        isiDropdown('ea-penulis',  'ambil_penulis.php',  'id', p => p.nama_depan + ' ' + p.nama_belakang, a.id_penulis),
        isiDropdown('ea-kategori', 'ambil_kategori.php', 'id', k => k.nama_kategori, a.id_kategori),
    ]);
    bukaModal('modal-edit-artikel');
}

async function updateArtikel() {
    sembunyiAlert('alert-edit-artikel');
    const id       = document.getElementById('ea-id').value;
    const judul    = document.getElementById('ea-judul').value.trim();
    const penulis  = document.getElementById('ea-penulis').value;
    const kategori = document.getElementById('ea-kategori').value;
    const isi      = document.getElementById('ea-isi').value.trim();

    if (!judul || !penulis || !kategori || !isi) {
        tampilAlert('alert-edit-artikel', 'Semua field wajib diisi.'); return;
    }

    const fd = new FormData();
    fd.append('id',          id);
    fd.append('judul',       judul);
    fd.append('id_penulis',  penulis);
    fd.append('id_kategori', kategori);
    fd.append('isi',         isi);
    const gambarFile = document.getElementById('ea-gambar').files[0];
    if (gambarFile) fd.append('gambar', gambarFile);

    setLoading('btn-update-artikel', true);
    const res  = await fetch('update_artikel.php', { method: 'POST', body: fd });
    const json = await res.json();
    setLoading('btn-update-artikel', false);

    if (json.status === 'sukses') {
        tutupModal('modal-edit-artikel');
        tampilToast(json.pesan);
        muatArtikel();
    } else {
        tampilAlert('alert-edit-artikel', json.pesan);
    }
}

// ═══════════════════════════════════════════════════════════════
// KATEGORI
// ═══════════════════════════════════════════════════════════════

async function muatKategori() {
    document.getElementById('tbody-kategori').innerHTML =
        '<tr class="loading-row"><td colspan="3">⏳ Memuat data...</td></tr>';
    const res  = await fetch('ambil_kategori.php');
    const json = await res.json();
    const tbody = document.getElementById('tbody-kategori');

    if (!json.data || json.data.length === 0) {
        tbody.innerHTML = '<tr class="empty-row"><td colspan="3">Belum ada data kategori.</td></tr>';
        return;
    }

    tbody.innerHTML = json.data.map(k => `
        <tr>
            <td><span class="badge-kategori">${esc(k.nama_kategori)}</span></td>
            <td>${esc(k.keterangan || '—')}</td>
            <td>
                <button class="btn btn-warning btn-sm" onclick="bukaEditKategori(${k.id})">Edit</button>
                <button class="btn btn-danger btn-sm"  onclick="konfirmasiHapus('kategori',${k.id})">Hapus</button>
            </td>
        </tr>`).join('');
}

function bukaTambahKategori() {
    sembunyiAlert('alert-tambah-kategori');
    document.getElementById('tk-nama').value       = '';
    document.getElementById('tk-keterangan').value = '';
    bukaModal('modal-tambah-kategori');
}

async function simpanKategori() {
    sembunyiAlert('alert-tambah-kategori');
    const nama = document.getElementById('tk-nama').value.trim();
    if (!nama) { tampilAlert('alert-tambah-kategori', 'Nama kategori wajib diisi.'); return; }

    const fd = new FormData();
    fd.append('nama_kategori', nama);
    fd.append('keterangan',    document.getElementById('tk-keterangan').value.trim());

    setLoading('btn-simpan-kategori', true);
    const res  = await fetch('simpan_kategori.php', { method: 'POST', body: fd });
    const json = await res.json();
    setLoading('btn-simpan-kategori', false);

    if (json.status === 'sukses') {
        tutupModal('modal-tambah-kategori');
        tampilToast(json.pesan);
        muatKategori();
    } else {
        tampilAlert('alert-tambah-kategori', json.pesan);
    }
}

async function bukaEditKategori(id) {
    sembunyiAlert('alert-edit-kategori');
    const res  = await fetch('ambil_satu_kategori.php?id=' + id);
    const json = await res.json();
    if (json.status !== 'sukses') { tampilToast(json.pesan, 'error'); return; }
    document.getElementById('ek-id').value         = json.data.id;
    document.getElementById('ek-nama').value       = json.data.nama_kategori;
    document.getElementById('ek-keterangan').value = json.data.keterangan || '';
    bukaModal('modal-edit-kategori');
}

async function updateKategori() {
    sembunyiAlert('alert-edit-kategori');
    const id   = document.getElementById('ek-id').value;
    const nama = document.getElementById('ek-nama').value.trim();
    if (!nama) { tampilAlert('alert-edit-kategori', 'Nama kategori wajib diisi.'); return; }

    const fd = new FormData();
    fd.append('id',            id);
    fd.append('nama_kategori', nama);
    fd.append('keterangan',    document.getElementById('ek-keterangan').value.trim());

    setLoading('btn-update-kategori', true);
    const res  = await fetch('update_kategori.php', { method: 'POST', body: fd });
    const json = await res.json();
    setLoading('btn-update-kategori', false);

    if (json.status === 'sukses') {
        tutupModal('modal-edit-kategori');
        tampilToast(json.pesan);
        muatKategori();
    } else {
        tampilAlert('alert-edit-kategori', json.pesan);
    }
}

// ═══════════════════════════════════════════════════════════════
// HAPUS (Universal)
// ═══════════════════════════════════════════════════════════════

function konfirmasiHapus(jenis, id) {
    bukaModal('modal-hapus');
    const urlMap = { penulis: 'hapus_penulis.php', artikel: 'hapus_artikel.php', kategori: 'hapus_kategori.php' };
    const muat   = { penulis: muatPenulis, artikel: muatArtikel, kategori: muatKategori };

    const btn = document.getElementById('btn-konfirmasi-hapus');
    // Clone to remove previous listener
    const btnBaru = btn.cloneNode(true);
    btn.parentNode.replaceChild(btnBaru, btn);

    btnBaru.addEventListener('click', async () => {
        const fd = new FormData();
        fd.append('id', id);
        const res  = await fetch(urlMap[jenis], { method: 'POST', body: fd });
        const json = await res.json();
        tutupModal('modal-hapus');
        if (json.status === 'sukses') {
            tampilToast(json.pesan);
            muat[jenis]();
        } else {
            tampilToast(json.pesan, 'error');
        }
    });
}

// ─── Init: muat data penulis saat halaman pertama kali dibuka ──────────
muatPenulis();
</script>
</body>
</html>
