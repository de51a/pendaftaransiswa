<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
$nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
$id_jurusan = mysqli_real_escape_string($koneksi, $_POST['id_jurusan']);
$alasan = mysqli_real_escape_string($koneksi, $_POST['alasan']);
// LOGIKA UPLOAD FILE FOTO
$foto_name = $_FILES['foto']['name'];
$foto_tmp = $_FILES['foto']['tmp_name'];
$foto_size = $_FILES['foto']['size'];
$ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
$allowed_ext = array('jpg', 'jpeg', 'png');
if (in_array($ext, $allowed_ext)) {
    if ($foto_size <= 2097152) { // Max 2MB
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        $new_foto_name = time() . '_' . $nis . '.' . $ext;
        $destination = 'uploads/' . $new_foto_name;
        if (move_uploaded_file($foto_tmp, $destination)) {
$sql = "INSERT INTO tb_siswa (nis, nama, id_jurusan, foto, alasan)
VALUES ('$nis', '$nama', '$id_jurusan', '$new_foto_name', '$alasan')";
if (mysqli_query($koneksi, $sql)) {
header("Location: index.php");
exit();
} else { echo "Gagal simpan database: " . mysqli_error($koneksi); }
} else { echo "Gagal upload file foto ke folder uploads."; }
} else { echo "Ukuran file terlalu besar! Maksimal 2MB."; }
} else { echo "Format file tidak diizinkan! Hanya JPG/PNG."; }
}
?>