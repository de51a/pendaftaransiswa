<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $id_jurusan = mysqli_real_escape_string($koneksi, $_POST['id_jurusan']);
    $alasan = mysqli_real_escape_string($koneksi, $_POST['alasan']);

    // Check if new photo was uploaded
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0 && $_FILES['foto']['size'] > 0) {
        $foto_name = $_FILES['foto']['name'];
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_size = $_FILES['foto']['size'];
        $ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png');

        if (in_array($ext, $allowed_ext)) {
            if ($foto_size <= 2097152) { // 2MB
                // Hapus foto lama
                $q = mysqli_query($koneksi, "SELECT foto FROM tb_siswa WHERE id='$id'");
                if ($old = mysqli_fetch_assoc($q)) {
                    if (!empty($old['foto']) && file_exists("uploads/" . $old['foto'])) {
                        unlink("uploads/" . $old['foto']);
                    }
                }

                $new_foto_name = time() . '_' . $nis . '.' . $ext;
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                move_uploaded_file($foto_tmp, 'uploads/' . $new_foto_name);
                
                $sql = "UPDATE tb_siswa SET nis='$nis', nama='$nama', id_jurusan='$id_jurusan', foto='$new_foto_name', alasan='$alasan' WHERE id='$id'";
            } else {
                die("Ukuran foto terlalu besar! Maksimal 2MB.");
            }
        } else {
            die("Format foto tidak valid! Hanya JPG & PNG.");
        }
    } else {
        $sql = "UPDATE tb_siswa SET nis='$nis', nama='$nama', id_jurusan='$id_jurusan', alasan='$alasan' WHERE id='$id'";
    }

    if (mysqli_query($koneksi, $sql)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>