<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // 1. Ambil nama foto siswa
    $q = mysqli_query($koneksi, "SELECT foto FROM tb_siswa WHERE id='$id'");
    if ($row = mysqli_fetch_assoc($q)) {
        $foto_lama = $row['foto'];
        if (!empty($foto_lama) && file_exists("uploads/" . $foto_lama)) {
            unlink("uploads/" . $foto_lama);
        }
    }
    
    // 2. Hapus data dari database
    $sql = "DELETE FROM tb_siswa WHERE id='$id'";
    if (mysqli_query($koneksi, $sql)) {
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'dashboard.php';
        header("Location: " . $redirect);
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>