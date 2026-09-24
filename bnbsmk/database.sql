-- Database Schema & Initial Seeders for SMK Bangun Nusa Bangsa
-- Database: bnbsmk_db

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Table Users (Admin & Penulis)
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `role` ENUM('admin', 'editor') DEFAULT 'admin',
  `foto` VARCHAR(255) DEFAULT 'default-avatar.png',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Table Kategori Artikel
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_kategori` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `deskripsi` TEXT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Table Artikel / Berita
CREATE TABLE IF NOT EXISTS `articles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  `judul` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `ringkasan` TEXT NOT NULL,
  `konten` LONGTEXT NOT NULL,
  `gambar_sampul` VARCHAR(255) DEFAULT 'default-article.jpg',
  `status` ENUM('published', 'draft') DEFAULT 'published',
  `views` INT DEFAULT 0,
  `allow_comments` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_article_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_article_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Table Komentar
CREATE TABLE IF NOT EXISTS `comments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `article_id` INT NOT NULL,
  `parent_id` INT NULL DEFAULT NULL,
  `nama_pengirim` VARCHAR(100) NOT NULL,
  `email_pengirim` VARCHAR(120) NULL DEFAULT NULL,
  `is_anonymous` TINYINT(1) DEFAULT 0,
  `isi_komentar` TEXT NOT NULL,
  `status` ENUM('approved', 'pending', 'spam') DEFAULT 'approved',
  `ip_address` VARCHAR(45) NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_comment_article` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_comment_parent` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Table Jurusan / Program Keahlian
CREATE TABLE IF NOT EXISTS `jurusan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kode` VARCHAR(20) NOT NULL UNIQUE,
  `nama_jurusan` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(160) NOT NULL UNIQUE,
  `ikon` VARCHAR(50) DEFAULT 'bi-laptop',
  `gambar` VARCHAR(255) DEFAULT 'default-jurusan.jpg',
  `deskripsi_singkat` TEXT NOT NULL,
  `kompetensi` TEXT NULL,
  `prospek_karir` TEXT NULL,
  `urutan` INT DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Table Profil Sekolah
CREATE TABLE IF NOT EXISTS `school_profile` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_sekolah` VARCHAR(150) NOT NULL DEFAULT 'SMK Bangun Nusa Bangsa',
  `slogan` VARCHAR(255) NOT NULL DEFAULT 'Mencetak Generasi Unggul, Terampil, Berkarakter & Siap Kerja',
  `sambutan_kepsek` TEXT NOT NULL,
  `nama_kepsek` VARCHAR(100) NOT NULL DEFAULT 'Drs. H. Mulyadi, M.Kom',
  `foto_kepsek` VARCHAR(255) DEFAULT 'kepsek.jpg',
  `sejarah` LONGTEXT NOT NULL,
  `visi` TEXT NOT NULL,
  `misi` TEXT NOT NULL,
  `alamat` TEXT NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `telepon` VARCHAR(50) NOT NULL,
  `whatsapp` VARCHAR(50) NOT NULL,
  `maps_embed` TEXT NULL,
  `facebook` VARCHAR(255) NULL,
  `instagram` VARCHAR(255) NULL,
  `youtube` VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Table Pengaturan Situs
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- SEED DATA AWAL
-- ========================================================

-- Akun Admin Default (Password: admin123)
INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `email`, `role`, `foto`) VALUES
(1, 'admin', '$2y$10$JVPqsCrVbsNb/vh1oFnFQ.zmxwNh4/tIEl8tB.Azrfom1EBtbVwn6', 'Administrator BNB', 'admin@smkbangunnusabangsa.sch.id', 'admin', 'admin.png')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Kategori Artikel
INSERT INTO `categories` (`id`, `nama_kategori`, `slug`, `deskripsi`) VALUES
(1, 'Berita Sekolah', 'berita-sekolah', 'Informasi dan warta terkini seputar kegiatan akademik dan non-akademik di SMK Bangun Nusa Bangsa.'),
(2, 'Prestasi Siswa', 'prestasi-siswa', 'Kumpulan pencapaian, piala, dan kejuaraan yang diraih oleh siswa-siswi berprestasi.'),
(3, 'Teknologi & Inovasi', 'teknologi-inovasi', 'Artikel edukasi seputar perkembangan coding, AI, networking, dan desain grafis masa kini.'),
(4, 'Info PPDB & Beasiswa', 'info-ppdb-beasiswa', 'Informasi resmi penerimaan peserta didik baru dan program beasiswa pendidikan.'),
(5, 'Kegiatan & Ekskul', 'kegiatan-ekskul', 'Dokumentasi kegiatan ekstrakurikuler, pramuka, OSIS, dan pentas seni sekolah.')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- -- Profil Sekolah
INSERT INTO `school_profile` (`id`, `nama_sekolah`, `slogan`, `sambutan_kepsek`, `nama_kepsek`, `foto_kepsek`, `sejarah`, `visi`, `misi`, `alamat`, `email`, `telepon`, `whatsapp`, `maps_embed`, `facebook`, `instagram`, `youtube`) VALUES
(1, 
'SMK Bangun Nusa Bangsa', 
'Mencetak Generasi Unggul, Terampil, Berkarakter & Siap Kerja di Era Digital 4.0', 
'Assalamu’alaikum Warahmatullahi Wabarakatuh.\n\nSelamat datang di portal resmi SMK Bangun Nusa Bangsa. Pendidikan kejuruan masa kini menuntut adaptasi cepat terhadap percepatan teknologi dan kebutuhan industri global. Kami hadir bukan hanya mencetak lulusan dengan ijazah, melainkan tenaga profesional yang berkeahlian tinggi, berkarakter mulia, adaptif, dan siap langsung bekerja, berwirausaha, maupun melanjutkan ke perguruan tinggi.\n\nDengan kurikulum berbasis industri terkini, sarana laboratorium modern, serta tenaga pengajar tersertifikasi, kami yakin SMK Bangun Nusa Bangsa adalah kawah candradimuka terbaik bagi putra-putri bangsa untuk meraih masa depan gemilang.\n\nWassalamu’alaikum Warahmatullahi Wabarakatuh.',
'Muhammad Yunus, S.E., M.Pd.',
'kepsek.png',
'SMK Bangun Nusa Bangsa didirikan pada tahun 2012 oleh Yayasan Pendidikan Bangun Nusa Bangsa. Berawal dari program keahlian teknik dengan fasilitas sederhana, kini SMK BNB telah berkembang pesat menjadi salah satu SMK Pusat Keunggulan di Jawa Barat yang memiliki program keahlian berstandar industri internasional, fasilitas smart classroom, dan teaching factory modern.',
'Menjadi Sekolah Menengah Kejuruan rujukan nasional yang unggul dalam IPTEK, berdaya saing global, berkarakter Profil Pelajar Pancasila, dan berjiwa wirausaha.',
'1. Menyelenggarakan pendidikan vokasi berbasis standar kompetensi kerja nasional dan internasional (SKKNI & IDUKA).\n2. Membina karakter religius, disiplin, berintegritas, dan berkebhinekaan global.\n3. Mengembangkan teaching factory dan inkubator bisnis siswa yang berorientasi produk nyata.\n4. Memperluas jejaring kemitraan strategis dengan BUMN, PMA, dan startup teknologi nasional.\n5. Meningkatkan kompetensi pendidik dan tenaga kependidikan secara berkesinambungan.',
'Jl. Merdeka Bangsa No. 88, Cibinong, Kab. Bogor, Jawa Barat',
'info@smkbangunnusabangsa.sch.id',
'(0251) 8899770',
'081234567890',
'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126829.04351657874!2d106.72173167156942!3d-6.596277646698305!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5b7ad4337b7%3A0xa1bb650863d8829d!2sBogor%2C%20Bogor%20City%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid',
'https://facebook.com/smkbangunnusabangsa',
'https://instagram.com/smkbangunnusabangsa',
'https://youtube.com/@smkbangunnusabangsa'
) ON DUPLICATE KEY UPDATE `id`=`id`;

-- Jurusan / Program Keahlian
INSERT INTO `jurusan` (`id`, `kode`, `nama_jurusan`, `slug`, `ikon`, `gambar`, `deskripsi_singkat`, `kompetensi`, `prospek_karir`, `urutan`) VALUES
(1, 'AK', 'Akuntansi (AK)', 'akuntansi', 'bi-journal-check', 'jurusan-akuntansi.jpg', 'Membekali peserta didik dengan keahlian dalam pembukuan keuangan, perpajakan, audit, dan pengoperasian software akuntansi modern.', 'Akuntansi Keuangan, Komputer Akuntansi (MYOB/Accurate), Administrasi Pajak, Praktikum Akuntansi Lembaga/Instansi Pemerintah, dan Spreadsheet Bisnis.', 'Staf Akuntansi, Junior Auditor, Kasir/Teller Bank, Staf Perpajakan, Administrasi Keuangan, dan Wirausahawan.', 1),
(2, 'TKJ', 'Teknik Komputer & Jaringan (TKJ)', 'teknik-komputer-dan-jaringan', 'bi-router', 'jurusan-tkj.jpg', 'Fokus pada penguasaan instalasi jaringan komputer, konfigurasi server, teknologi fiber optic, cyber security, dan infrastruktur cloud.', 'Perakitan Komputer & Troubleshooting, Instalasi Jaringan LAN/WAN/Fiber Optic, Administrasi Server Linux/Windows, Mikrotik MTCNA & Cisco Networking, serta Keamanan Jaringan.', 'Network Engineer, System Administrator, IT Support, Cyber Security Officer, Teknisi Jaringan Fiber Optic, dan Cloud Administrator.', 2),
(3, 'TKR', 'Teknik Kendaraan Ringan (TKR)', 'teknik-kendaraan-ringan', 'bi-gear-wide-connected', 'jurusan-tkr.jpg', 'Mempelajari perawatan, perbaikan mesin otomotif, sistem kelistrikan kendaraan ringan modern, chasis, dan teknologi Electronic Fuel Injection (EFI).', 'Pemeliharaan Mesin Kendaraan Ringan (Engine Tune-Up), Sistem Kelistrikan Otomotif & EFI, Pemeliharaan Chasis & Pemindah Tenaga, Spooring & Balancing, serta Diagnosis Sistem Otomotif Modern.', 'Mekanik Otomotif Profesional, Teknisi Service Advisor, Quality Control Pabrik Otomotif, Modifikator Kendaraan, dan Wirausaha Bengkel Mandiri.', 3)
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Artikel & Berita Sampel (2 Artikel)
INSERT INTO `articles` (`id`, `user_id`, `category_id`, `judul`, `slug`, `ringkasan`, `konten`, `gambar_sampul`, `status`, `views`, `allow_comments`) VALUES
(1, 1, 1, 
'Mengenal Kampus Yayasan Bangun Nusa Bangsa: Pusat Pendidikan Vokasi Terpadu & Modern di Cibinong', 
'mengenal-kampus-yayasan-bangun-nusa-bangsa-pusat-pendidikan-vokasi-terpadu',
'Kampus Yayasan Bangun Nusa Bangsa Cibinong hadir dengan gedung representatif, lab komputer & bengkel otomotif modern, sarana ibadah, dan lingkungan hijau asri yang mendukung kompetensi siswa.',
'<p class=\"lead\">Yayasan Bangun Nusa Bangsa terus berkomitmen menghadirkan ekosistem pendidikan yang unggul, inspiratif, dan berwawasan masa depan. Terletak strategis di kawasan Cibinong, kampus terpadu Yayasan Bangun Nusa Bangsa dirancang khusus untuk memenuhi standar sarana dan prasarana pendidikan vokasi modern.</p>

<h4>Sarana & Fasilitas Terpadu di Kampus Yayasan Bangun Nusa Bangsa</h4>
<p>Kompleks kampus Yayasan Bangun Nusa Bangsa dilengkapi berbagai fasilitas mutakhir guna memastikan setiap peserta didik mendapatkan pengalaman belajar dan praktik industri terbaik, di antaranya:</p>

<ul>
  <li><strong>Smart Laboratory & IT Center:</strong> Laboratorium komputer terkoneksi fiber optic dan simulator jaringan enterprise untuk keahlian Teknik Komputer & Jaringan (TKJ).</li>
  <li><strong>Modern Automotive Workshop:</strong> Bengkel standar industri untuk praktikum Teknik Kendaraan Ringan (TKR) lengkap dengan peralatan hidrolik, scanner EFI, dan tune-up modern.</li>
  <li><strong>Accounting & Business Simulation Room:</strong> Ruang simulasi perbankan dan komputer akuntansi (MYOB/Accurate) bagi siswa kompetensi Akuntansi (AK).</li>
  <li><strong>Masjid Kampus & Sarana Ibadah:</strong> Pusat pembinaan karakter religius dan kegiatan kerohanian siswa.</li>
  <li><strong>Sarana Olahraga & Ruang Terbuka Hijau:</strong> Lapangan olahraga multifungsi, area kantin higienis, serta perpustakaan literasi digital yang nyaman.</li>
</ul>

<p>Kepala SMK Bangun Nusa Bangsa, <em>Muhammad Yunus, S.E., M.Pd.</em>, menegaskan bahwa kenyamanan dan kelengkapan fasilitas kampus merupakan pilar krusial dalam membentuk generasi muda yang kompeten, disiplin, berintegritas, dan siap bersaing di dunia kerja maupun dunia wirausaha.</p>',
'kampus-bnb.jpg', 'published', 428, 1),

(2, 1, 1, 
'Pelaksanaan MPLS Ramah & Edukatif Tahun Ajaran 2026/2027 SMK Bangun Nusa Bangsa', 
'pelaksanaan-mpls-ramah-dan-edukatif-tahun-ajaran-2026-2027-smk-bangun-nusa-bangsa',
'Masa Pengenalan Lingkungan Sekolah (MPLS) Tahun Ajaran 2026/2027 SMK Bangun Nusa Bangsa resmi digelar dengan mengedepankan kegiatan edukatif, pembentukan karakter, dan budaya industri.',
'<p class=\"lead\">SMK Bangun Nusa Bangsa menyambut hangat ratusan peserta didik baru melalui kegiatan <strong>Masa Pengenalan Lingkungan Sekolah (MPLS) Tahun Ajaran 2026/2027</strong> yang berlangsung semarak, tertib, dan penuh inspirasi.</p>

<h4>Rangkaian Kegiatan MPLS Ramah & Berkarakter</h4>
<p>Dengan mengusung tema <em>\"Membangun Karakter Pelajar Vokasi yang Unggul, Berakhlak Mulia, dan Siap Berkarya\"</em>, kegiatan MPLS 2026/2027 diisi dengan beragam agenda edukatif dan pengenalan lingkungan kampus, meliputi:</p>

<ol>
  <li><strong>Sosialisasi Budaya Industri 5S/5R:</strong> Penanaman kedisiplinan kerja, etos profesional, dan budaya keselamatan kerja sejak hari pertama masuk sekolah.</li>
  <li><strong>Pengenalan Program Keahlian:</strong> Tur bengkel otomotif TKR, pengenalan lab jaringan TKJ, dan praktikum awal lab akuntansi AK.</li>
  <li><strong>Materi Bahaya Narkoba & Tertib Lalu Lintas:</strong> Edukasi bersama jajaran kepolisian dan BNN setempat guna membentengi generasi muda dari pengaruh negatif.</li>
  <li><strong>Unjuk Bakat & Demo Ekstrakurikuler:</strong> Penampilan atraktif OSIS, Pramuka, Paskibra, PMR, Seni Musik, dan cabang olahraga prestasi.</li>
</ol>

<p>Kepala SMK Bangun Nusa Bangsa, <em>Muhammad Yunus, S.E., M.Pd.</em>, dalam amanat upacara pembukaan berpesan agar seluruh siswa baru angkatan 2026/2027 senantiasa memegang teguh semangat belajar dan menjadikan SMK Bangun Nusa Bangsa sebagai tempat bertumbuh meraih masa depan gemilang.</p>',
'mpls-bnb.jpg', 'published', 512, 1)
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Komentar Sampel
INSERT INTO `comments` (`id`, `article_id`, `parent_id`, `nama_pengirim`, `email_pengirim`, `is_anonymous`, `isi_komentar`, `status`) VALUES
(1, 1, NULL, 'Ahmad Fauzi', 'ahmad.fauzi@gmail.com', 0, 'Fasilitas kampus Yayasan Bangun Nusa Bangsa sangat lengkap dan representatif untuk mendukung siswa SMK siap kerja.', 'approved'),
(2, 1, NULL, 'Wali Murid Siswa', NULL, 1, 'Alhamdulillah lingkungan sekolahnya nyaman dan asri. Sukses selalu untuk Yayasan Bangun Nusa Bangsa!', 'approved'),
(3, 2, NULL, 'Rian Hidayat', 'rian.hidayat@gmail.com', 0, 'Semangat untuk adik-adik peserta didik baru MPLS 2026/2027! Bangga menjadi bagian dari keluarga besar SMK Bangun Nusa Bangsa.', 'approved'),
(4, 2, 3, 'Panitia MPLS 2026', 'admin@smkbangunnusabangsa.sch.id', 0, 'Terima kasih kak Rian! Semoga adik-adik kelas 10 semakin bersemangat menimba ilmu vokasi di SMK BNB.', 'approved')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Site Settings
INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('site_title', 'SMK Bangun Nusa Bangsa - Official Portal & Media Berita'),
('site_tagline', 'Sekolah Vokasi Masa Depan Berbasis Teknologi & Karakter Luhur'),
('allow_anonymous_comments', '1'),
('auto_approve_comments', '1'),
('primary_color', '#2563eb'),
('secondary_color', '#06b6d4')
ON DUPLICATE KEY UPDATE `setting_key`=`setting_key`;

SET FOREIGN_KEY_CHECKS = 1;
