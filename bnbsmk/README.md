# Website Profil SMK Bangun Nusa Bangsa (BNB) & CMS Management Artikel 3D

Website resmi dan portal publikasi warta untuk **SMK Bangun Nusa Bangsa**, dibangun dengan **PHP Native**, **MySQL**, **Bootstrap 5.3**, dan efek visual **3D Interaktif (Three.js & CSS 3D Tilt)**.

---

## 🌟 Fitur Utama

### 1. Frontend & Visual 3D Interaktif
- **Interactive Three.js 3D Hero Canvas**: Objek 3D polihedral & partikel bintang hologram yang merespons pergerakan kursor mouse secara dinamis.
- **CSS 3D Tilt Parallax**: Efek kartu miring 3D pada Program Keahlian, Statistik, dan Artikel berita.
- **Glassmorphism & Micro-animations**: Desain premium modern dengan Google Fonts (*Outfit* & *Plus Jakarta Sans*).
- **Halaman Lengkap**:
  - `index.php`: Beranda (3D Hero, Sambutan Kepala Sekolah, Jurusan 3D, Warta Terkini, PPDB Banner).
  - `profil.php`: Sejarah, Visi, Misi, Nilai-nilai, dan Fasilitas Kampus.
  - `jurusan.php`: 5 Program Keahlian (RPL, TKJ, DKV, MPLB, AKL) lengkap dengan materi & prospek karir.
  - `artikel.php`: Indeks warta dengan pencarian cepat, filter kategori, widget terpopuler, dan paginasi.
  - `artikel-detail.php`: Baca artikel lengkap, estimasi waktu baca, counter views, tombol share medsos, artikel terkait, dan sistem komentar.
  - `kontak.php`: Formulir pesan & konsultasi, peta Google Maps, dan kontak WhatsApp.

### 2. Sistem Komentar Artikel Fleksibel
- **Mode Identitas Lengkap**: Pengunjung mengisi Nama Lengkap & Email aktif.
- **Mode Anonim**: Pengunjung mencentang opsi *"Kirim sebagai Anonim"*, nama otomatis disamarkan dan alamat email tidak wajib diisi.
- **Moderasi & Keamanan**:
  - Filter CSRF Token & Anti-spam Honeypot trap.
  - Pengaturan mode penerbitan komentar (Auto-Approve atau Menunggu Moderasi Admin).
  - Fitur balasan resmi (*Admin Reply*) langsung dari dashboard.

### 3. Dashboard Web Management Artikel (CMS Admin)
- **Ringkasan Analitik**: Grafik tren pembaca mingguan (Chart.js), total artikel, total views, dan status komentar.
- **Kelola Artikel**: Tambah, edit, draft/publish, filter kategori, status, dan hapus artikel dengan konfirmasi interaktif (SweetAlert2).
- **Rich Text Editor**: Editor naskah WYSIWYG berbasis Quill.js dan upload gambar sampul thumbnail.
- **Kelola Kategori**: Tambah & edit kategori artikel berita.
- **Moderasi Komentar**: Filter komentar (Pending, Approved, Spam), setujui dalam 1 klik, dan balas komentar.
- **Kelola Program Keahlian**: Tambah dan ubah data jurusan.
- **Profil Sekolah**: Ubah sambutan kepala sekolah, visi misi, alamat, dan media sosial.
- **Pengaturan & Akun**: Konfigurasi kebijakan komentar dan ganti kata sandi admin.

---

## 🚀 Cara Menjalankan di XAMPP

1. Pastikan folder proyek berada di:
   ```
   C:\xampp\htdocs\bnbsmk\
   ```
2. Jalankan modul **Apache** dan **MySQL** pada **XAMPP Control Panel**.
3. Buka browser dan akses alamat:
   ```
   http://localhost/bnbsmk/
   ```
   *(Sistem memiliki fitur Auto-Migration yang akan otomatis membuat database `bnbsmk_db` dan mengimpor tabel serta seeder awal saat website pertama kali dibuka)*.

4. Jika ingin mengimpor database secara manual melalui **phpMyAdmin**:
   - Buka `http://localhost/phpmyadmin/`
   - Buat database baru bernama `bnbsmk_db`
   - Import file `database.sql` yang ada di dalam folder proyek.

---

## 🔐 Kredensial Login Administrator

- **URL Login**: `http://localhost/bnbsmk/login.php`
- **Username**: `admin`
- **Password**: `admin123`

---

## 📁 Struktur Direktori

```
bnbsmk/
├── admin/                  # Panel Dashboard CMS
│   ├── includes/           # Header & Footer Admin
│   ├── index.php           # Dashboard Analytics & Chart
│   ├── artikel.php         # Manajemen List Artikel
│   ├── artikel-tambah.php  # Form Tambah Artikel + Quill Editor
│   ├── artikel-edit.php    # Form Edit Artikel
│   ├── kategori.php        # Kelola Kategori Berita
│   ├── komentar.php        # Moderasi & Balas Komentar
│   ├── jurusan.php         # Kelola Program Keahlian
│   ├── profil-sekolah.php  # Kelola Profil & Sambutan
│   ├── pengaturan.php      # Kebijakan Komentar & Web
│   └── users.php           # Manajemen Akun & Password
├── assets/
│   ├── css/                # style.css (3D & Glass) & admin.css
│   ├── js/                 # 3d-hero.js (Three.js), main.js, admin.js
│   └── images/             # Logo, placeholder SVG, & uploads/
├── config/
│   ├── database.php        # Koneksi PDO & Auto-Installer DB
│   └── functions.php       # Helper Utility, Auth, Sanitasi, Upload
├── includes/
│   ├── header.php          # Navbar publik & 3D Brand Badge
│   └── footer.php          # Footer info sekolah & CDN scripts
├── database.sql            # Skema MySQL dan data seeder
├── index.php               # Beranda Utama (3D Hero Canvas)
├── profil.php              # Profil Sekolah, Sejarah, Visi Misi
├── jurusan.php             # Program Keahlian
├── artikel.php             # Indeks Artikel & Pencarian
├── artikel-detail.php      # Detail Artikel + Sistem Komentar
├── kontak.php              # Kontak & Lokasi Kampus
├── login.php               # Login Administrator
├── logout.php              # Handler Logout
└── README.md
```
