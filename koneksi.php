<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_siswa";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Cek dan buat tabel tb_users secara otomatis jika belum ada
$cek_tabel = mysqli_query($koneksi, "SHOW TABLES LIKE 'tb_users'");
if (mysqli_num_rows($cek_tabel) == 0) {
    $sql_create = "CREATE TABLE `tb_users` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `nama_lengkap` VARCHAR(100) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    if (mysqli_query($koneksi, $sql_create)) {
        $default_user = "admin";
        $default_pass = password_hash("admin123", PASSWORD_DEFAULT);
        $default_nama = "Administrator";
        
        $sql_insert = "INSERT INTO `tb_users` (`username`, `password`, `nama_lengkap`) VALUES ('$default_user', '$default_pass', '$default_nama')";
        mysqli_query($koneksi, $sql_insert);
    }
}
?>