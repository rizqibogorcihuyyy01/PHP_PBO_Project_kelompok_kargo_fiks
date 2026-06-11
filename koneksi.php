<?php
/**
 * ============================================================================
 * KONFIGURASI KONEKSI DATABASE — PDO MySQL
 * ============================================================================
 * 
 * Job 1 (Database Engineer):
 * File ini bertanggung jawab untuk membangun koneksi ke database MySQL
 * 'db_kargo_ekspedisi' menggunakan PDO (PHP Data Objects) untuk keamanan
 * prepared statements dan kompatibilitas lintas database.
 * 
 * Database  : db_kargo_ekspedisi
 * Charset   : utf8mb4
 * Collation : utf8mb4_0900_ai_ci
 * 
 * @package  LogiCargo Dashboard
 * @version  1.0.0
 * ============================================================================
 */

// ── Konfigurasi Kredensial Database ──
$db_host     = "localhost";
$db_name     = "db_kargo_ekspedisi";
$db_username = "root";
$db_password = "";
$db_charset  = "utf8mb4";

// ── Inisialisasi Koneksi PDO ──
$koneksi = null;
$is_db_connected = false;

try {
    $dsn = "mysql:host={$db_host};dbname={$db_name};charset={$db_charset}";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $koneksi = new PDO($dsn, $db_username, $db_password, $options);
    $is_db_connected = true;
} catch (PDOException $e) {
    $is_db_connected = false;
    // Koneksi gagal — halaman akan menampilkan status "DB Terputus"
}
?>