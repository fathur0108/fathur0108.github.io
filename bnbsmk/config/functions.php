<?php
/**
 * Helper Functions & Utilities
 * SMK Bangun Nusa Bangsa
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';

// Base URL helper
function getBaseUrl() {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    $protocol = $isHttps ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $script = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
    // Normalize path to root project folder
    $path = str_replace('\\', '/', $script);
    $path = preg_replace('/\/admin(\/.*)?$/', '', $path);
    $path = rtrim($path, '/');
    return $protocol . $host . $path;
}

// Generate / Validate CSRF Token
function getCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Flash Message System
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type, // success, danger, warning, info
        'message' => $message
    ];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function renderFlash() {
    $flash = getFlash();
    if ($flash) {
        $type = htmlspecialchars($flash['type']);
        $message = $flash['message'];
        $icon = $type === 'success' ? 'check-circle-fill' : ($type === 'danger' ? 'exclamation-triangle-fill' : 'info-circle-fill');
        echo "
        <div class='alert alert-{$type} alert-dismissible fade show shadow-sm d-flex align-items-center mb-4' role='alert'>
            <i class='bi bi-{$icon} me-2 fs-5'></i>
            <div>{$message}</div>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
    }
}

// Auth Helper
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        setFlash('warning', 'Silakan login terlebih dahulu untuk mengakses panel admin.');
        $baseUrl = getBaseUrl();
        header("Location: {$baseUrl}/login.php");
        exit;
    }
}

function getAuthUser() {
    if (!isLoggedIn()) return null;
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id, username, nama_lengkap, email, role, foto FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// Data Sanitization
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Slug Generator
function createSlug($string) {
    $string = preg_replace('~[^\pL\d]+~u', '-', $string);
    $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
    $string = preg_replace('~[^-\w]+~', '', $string);
    $string = trim($string, '-');
    $string = preg_replace('~-+~', '-', $string);
    $string = strtolower($string);
    return empty($string) ? 'n-a' : $string;
}

// Relative time format (Indonesian)
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) {
        return 'Baru saja';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' menit yang lalu';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' jam yang lalu';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' hari yang lalu';
    } else {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $tgl = date('d', $time);
        $bln = $bulan[(int)date('m', $time)];
        $thn = date('Y', $time);
        return "$tgl $bln $thn";
    }
}

// Format Indonesian Date
function formatTanggalIndo($datetime, $withTime = false) {
    $time = strtotime($datetime);
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $tgl = date('d', $time);
    $bln = $bulan[(int)date('m', $time)];
    $thn = date('Y', $time);
    
    $res = "$tgl $bln $thn";
    if ($withTime) {
        $res .= ' pukul ' . date('H:i', $time) . ' WIB';
    }
    return $res;
}

// Estimate Reading Time
function estimateReadingTime($text) {
    $wordCount = str_word_count(strip_tags($text));
    $minutes = ceil($wordCount / 180);
    return max(1, $minutes);
}

// Image Uploader
function uploadImage($file, $folder = 'uploads') {
    $targetDir = __DIR__ . '/../assets/images/' . trim($folder, '/') . '/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['status' => false, 'message' => 'Parameter file tidak valid.'];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'message' => 'Terjadi kesalahan saat upload berkas.'];
    }
    
    if ($file['size'] > 5 * 1024 * 1024) { // 5MB limit
        return ['status' => false, 'message' => 'Ukuran file gambar maksimal 5 MB.'];
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowedMimes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    
    if (!array_key_exists($mime, $allowedMimes)) {
        return ['status' => false, 'message' => 'Format file tidak diizinkan. Gunakan JPG, PNG, atau WEBP.'];
    }
    
    $ext = $allowedMimes[$mime];
    $fileName = uniqid('bnb_', true) . '.' . $ext;
    $targetFilePath = $targetDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        return ['status' => true, 'fileName' => $folder . '/' . $fileName];
    }
    
    return ['status' => false, 'message' => 'Gagal menyimpan file ke direktori server.'];
}

// School Profile Info Getter
function getSchoolProfile() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT * FROM school_profile LIMIT 1");
    $profile = $stmt->fetch();
    if (!$profile) {
        return [
            'nama_sekolah' => 'SMK Bangun Nusa Bangsa',
            'slogan' => 'Mencetak Generasi Unggul, Terampil, Berkarakter & Siap Kerja',
            'nama_kepsek' => 'Muhammad Yunus, S.E., M.Pd.',
            'sambutan_kepsek' => 'Selamat datang di Website Resmi SMK Bangun Nusa Bangsa. Kami berkomitmen menyelenggarakan pendidikan vokasi berkualitas berbasis teknologi dan berkarakter luhur.',
            'foto_kepsek' => 'kepsek.png',
            'sejarah' => 'SMK Bangun Nusa Bangsa didirikan untuk menjawab tantangan dunia industri dan teknologi digital...',
            'visi' => 'Menjadi Sekolah Menengah Kejuruan unggulan yang berdaya saing global, berkarakter mulia, dan berwawasan teknologi.',
            'misi' => "1. Menyelenggarakan pendidikan kejuruan berbasis industri dan teknologi terkini.\n2. Membentuk karakter peserta didik yang beriman, bertaqwa, dan berakhlak mulia.\n3. Meningkatkan kompetensi siswa berstandar sertifikasi nasional dan internasional.\n4. Menjalin kemitraan strategis dengan dunia usaha dan industri (DUDI).",
            'alamat' => 'Jl. Merdeka Bangsa No. 88, Kota Bogor, Jawa Barat',
            'email' => 'info@smkbangunnusabangsa.sch.id',
            'telepon' => '(0251) 8899770',
            'whatsapp' => '081234567890',
            'facebook' => 'https://facebook.com',
            'instagram' => 'https://instagram.com',
            'youtube' => 'https://youtube.com',
            'maps_embed' => ''
        ];
    }
    return $profile;
}

// Site Settings Getter & Setter
function getSiteSetting($key, $default = '') {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $row = $stmt->fetch();
    return $row ? $row['setting_value'] : $default;
}

function setSiteSetting($key, $value) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) 
        ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    return $stmt->execute([$key, $value]);
}

// Smart Image URL Resolver with SVG Fallback
function getImageUrl($imageName, $default = 'default-article.svg') {
    $baseUrl = getBaseUrl();
    if (empty($imageName)) {
        return "{$baseUrl}/assets/images/{$default}";
    }
    
    // Check if full path starts with http
    if (strpos($imageName, 'http://') === 0 || strpos($imageName, 'https://') === 0) {
        return $imageName;
    }
    
    $localFile = __DIR__ . '/../assets/images/' . $imageName;
    if (file_exists($localFile)) {
        return "{$baseUrl}/assets/images/{$imageName}";
    }
    
    // Check if .svg counterpart exists (e.g. article-1.jpg -> article-1.svg)
    $svgAlternative = preg_replace('/\.(jpg|jpeg|png|webp)$/i', '.svg', $imageName);
    if (file_exists(__DIR__ . '/../assets/images/' . $svgAlternative)) {
        return "{$baseUrl}/assets/images/{$svgAlternative}";
    }
    
    return "{$baseUrl}/assets/images/{$default}";
}

