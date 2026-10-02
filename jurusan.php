<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

if (isset($_POST['tambah'])) {
    $nama_jurusan = mysqli_real_escape_string($koneksi, $_POST['nama_jurusan']);
    if (!empty($nama_jurusan)) {
        mysqli_query($koneksi, "INSERT INTO tb_jurusan (nama_jurusan) VALUES ('$nama_jurusan')");
    }
    header("Location: jurusan.php");
    exit();
}
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM tb_jurusan WHERE id_jurusan='$id'");
    header("Location: jurusan.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Master Data Jurusan - db_siswa</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 20px; background: #f4f4f9; }
.container { max-width: 650px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px; }
th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; }
th { background: #1e293b; color: white; }
.btn { padding: 8px 14px; border: none; border-radius: 6px; color: white; cursor: pointer; text-decoration: none; font-size: 12px; font-weight: 500; display: inline-block; transition: 0.2s; }
.btn-green { background: #28a745; }
.btn-green:hover { background: #218838; }
.btn-danger { background: #dc3545; }
.btn-danger:hover { background: #bd2130; }
.btn-nav { background: #6c757d; }
.btn-nav:hover { background: #5a6268; }
.btn-dark { background: #0f172a; }
.btn-dark:hover { background: #1e293b; }
.top-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; }
.nav-btns { display: flex; gap: 8px; }
</style>
</head>
<body>
<div class="container">
<div class="top-nav">
  <h2 style="font-size: 18px;">Master Jurusan</h2>
  <div class="nav-btns">
    <a href="dashboard.php" class="btn btn-dark">📊 Dashboard</a>
    <a href="index.php" class="btn btn-nav">📝 Form Pendaftaran</a>
  </div>
</div>

<form method="POST" style="display: flex; gap: 10px; margin-bottom: 20px;">
<input type="text" name="nama_jurusan" placeholder="Nama Jurusan Baru..." required style="flex: 1; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
<button type="submit" name="tambah" class="btn btn-green">+ Tambah Jurusan</button>
</form>

<table>
<thead>
<tr>
  <th style="width: 60px;">ID</th>
  <th>Nama Jurusan</th>
  <th style="width: 90px; text-align: center;">Aksi</th>
</tr>
</thead>
<tbody>
<?php
$q = mysqli_query($koneksi, "SELECT * FROM tb_jurusan ORDER BY id_jurusan ASC");
if (mysqli_num_rows($q) > 0) {
    while($r = mysqli_fetch_assoc($q)){
        $confirm_msg = htmlspecialchars("Hapus jurusan " . $r['nama_jurusan'] . "?", ENT_QUOTES);
        echo "<tr>
        <td>{$r['id_jurusan']}</td>
        <td><strong>{$r['nama_jurusan']}</strong></td>
        <td style='text-align:center;'><a href='jurusan.php?hapus={$r['id_jurusan']}' class='btn btn-danger' onclick=\"return confirm('{$confirm_msg}')\">Hapus</a></td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='3' style='text-align:center; color:#94a3b8;'>Belum ada jurusan terdaftar.</td></tr>";
}
?>
</tbody>
</table>
</div>
</body>
</html>