<?php
/**
 * Konfigurasi Database & Auto Migration Helper
 * SMK Bangun Nusa Bangsa
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bnbsmk_db');

function getDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            // Coba koneksi ke host langsung
            $dsnWithoutDb = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
            $tempPdo = new PDO($dsnWithoutDb, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            
            // Cek apakah database sudah ada, jika belum buat database
            $tempPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // Hubungkan ke database yang sudah dibuat
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            
            // Auto-check apakah tabel sudah ada, jika belum buatkan otomatis
            ensureTablesAndSeed($pdo);

        } catch (PDOException $e) {
            die("<div style='font-family: sans-serif; padding: 30px; background: #fee2e2; color: #991b1b; border-radius: 8px; margin: 20px auto; max-width: 650px; border: 1px solid #f87171;'>
                <h3 style='margin-top:0;'>⚠️ Gagal Menghubungkan ke Database MySQL</h3>
                <p>Pastikan MySQL pada XAMPP Anda telah dijalankan (Status: Running).</p>
                <p><strong>Pesan Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
            </div>");
        }
    }
    
    return $pdo;
}

function ensureTablesAndSeed($pdo) {
    // Cek keberadaan tabel 'users'
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    $tableExists = $stmt->fetch();
    
    if (!$tableExists) {
        $sqlFile = __DIR__ . '/../database.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $pdo->exec($sql);
        }
    }
}
