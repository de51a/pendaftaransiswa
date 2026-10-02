<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pendaftaran Siswa Baru - db_siswa</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 20px; background: #f4f4f9; }
.container { max-width: 950px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
.form-group { margin-bottom: 14px; }
label { display: block; font-weight: bold; margin-bottom: 6px; font-size: 13px; color: #334155; }
input[type="text"], textarea, select { width: 100%; padding: 9px 12px; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; }
input[type="text"]:focus, textarea:focus, select:focus { outline: none; border-color: #2563eb; }
.btn { padding: 8px 14px; border: none; border-radius: 6px; color: white; cursor: pointer; text-decoration: none; font-size: 13px; display: inline-block; font-weight: 500; transition: 0.2s; }
.btn-green { background-color: #28a745; }
.btn-green:hover { background-color: #218838; }
.btn-blue { background-color: #0284c7; }
.btn-blue:hover { background-color: #0369a1; }
.btn-warning { background-color: #f59e0b; }
.btn-warning:hover { background-color: #d97706; }
.btn-danger { background-color: #dc3545; }
.btn-danger:hover { background-color: #bd2130; }
.btn-purple { background-color: #6f42c1; }
.btn-purple:hover { background-color: #593196; }
.btn-dark { background-color: #0f172a; }
.btn-dark:hover { background-color: #1e293b; }
table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px; }
table, th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; }
th { background-color: #1e293b; color: white; }
.img-thumb { width: 45px; height: 55px; object-fit: cover; border-radius: 4px; }
.filter-box { background: #f1f5f9; padding: 14px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 10px; }
.top-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; }
.nav-btns { display: flex; gap: 8px; }
</style>
</head>
<body>
<div class="container">
<div class="top-nav">
  <h2>Form Pendaftaran Siswa Baru</h2>
  <div class="nav-btns">
    <a href="dashboard.php" class="btn btn-dark">📊 Dashboard Admin</a>
    <a href="jurusan.php" class="btn btn-purple">Kelola Jurusan »</a>
    <a href="logout.php" class="btn btn-danger" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
  </div>
</div>

<!-- FORM PENDAFTARAN DENGAN UPLOAD FILE -->
<form action="simpan.php" method="POST" enctype="multipart/form-data">
<div class="form-group">
<label>NIS:</label>
<input type="text" name="nis" required placeholder="Masukkan NIS">
</div>
<div class="form-group">
<label>Nama Lengkap:</label>
<input type="text" name="nama" required placeholder="Masukkan Nama Lengkap">
</div>
<div class="form-group">
<label>Pilih Jurusan:</label>
<select name="id_jurusan" required>
<option value="">-- Pilih Jurusan --</option>
<?php
$jur = mysqli_query($koneksi, "SELECT * FROM tb_jurusan");
while($j = mysqli_fetch_assoc($jur)){
echo "<option value='{$j['id_jurusan']}'>{$j['nama_jurusan']}</option>";
}
?>
</select>
</div>
<div class="form-group">
<label>Upload Pasfoto (JPG/PNG, Max 2MB):</label>
<input type="file" name="foto" accept="image/*" required>
</div>
<div class="form-group">
<label>Alasan Masuk:</label>
<textarea name="alasan" rows="3" required placeholder="Alasan memilih sekolah ini"></textarea>
</div>
<button type="submit" class="btn btn-green">Daftar Sekarang</button>
</form>

<hr style="margin: 25px 0; border: 0; border-top: 1px solid #e2e8f0;">

<h3>Daftar Siswa Terdaftar</h3>
<!-- FORM PENCARIAN & FILTER -->
<form method="GET" class="filter-box">
<input type="text" name="keyword" placeholder="Cari NIS / Nama..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" style="flex: 2;">
<select name="filter_jurusan" style="flex: 1;">
<option value="">Semua Jurusan</option>
<?php
$jur2 = mysqli_query($koneksi, "SELECT * FROM tb_jurusan");
while($j2 = mysqli_fetch_assoc($jur2)){
$selected = (isset($_GET['filter_jurusan']) && $_GET['filter_jurusan'] == $j2['id_jurusan']) ? 'selected' : '';
echo "<option value='{$j2['id_jurusan']}' $selected>{$j2['nama_jurusan']}</option>";
}
?>
</select>
<button type="submit" class="btn btn-blue">Cari & Filter</button>
<a href="index.php" class="btn btn-danger">Reset</a>
</form>

<table>
<thead>
<tr>
<th>Pasfoto</th>
<th>NIS</th>
<th>Nama</th>
<th>Jurusan</th>
<th>Alasan</th>
<th style="width: 180px;">Aksi</th>
</tr>
</thead>
<tbody>
<?php
// QUERY DENGAN JOIN & FILTER LIKE
$sql = "SELECT tb_siswa.*, tb_jurusan.nama_jurusan
FROM tb_siswa
JOIN tb_jurusan ON tb_siswa.id_jurusan = tb_jurusan.id_jurusan
WHERE 1=1";
if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
$kw = mysqli_real_escape_string($koneksi, $_GET['keyword']);
$sql .= " AND (tb_siswa.nama LIKE '%$kw%' OR tb_siswa.nis LIKE '%$kw%')";
}
if (isset($_GET['filter_jurusan']) && $_GET['filter_jurusan'] != '') {
$fj = mysqli_real_escape_string($koneksi, $_GET['filter_jurusan']);
$sql .= " AND tb_siswa.id_jurusan = '$fj'";
}
$sql .= " ORDER BY tb_siswa.id DESC";
$query = mysqli_query($koneksi, $sql);
if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $confirm_msg = htmlspecialchars("Hapus data " . $row['nama'] . "?", ENT_QUOTES);
        echo "<tr>
        <td><img src='uploads/{$row['foto']}' class='img-thumb' alt='Foto'></td>
        <td><strong>{$row['nis']}</strong></td>
        <td>{$row['nama']}</td>
        <td>{$row['nama_jurusan']}</td>
        <td>{$row['alasan']}</td>
        <td>
        <a href='cetak.php?id={$row['id']}' target='_blank' class='btn btn-blue'>Cetak</a>
        <a href='edit.php?id={$row['id']}' class='btn btn-warning'>Edit</a>
        <a href='delete.php?id={$row['id']}' class='btn btn-danger' onclick=\"return confirm('{$confirm_msg}')\">Hapus</a>
        </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='6' style='text-align:center; color: #94a3b8; padding: 15px;'>Data tidak ditemukan.</td></tr>";
}
?>
</tbody>
</table>
</div>
</body>
</html>