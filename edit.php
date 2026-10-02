<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM tb_siswa WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Data Siswa - <?php echo htmlspecialchars($data['nama']); ?></title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 20px; background-color: #f4f4f9; }
.container { max-width: 600px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
.form-group { margin-bottom: 15px; }
label { display: block; margin-bottom: 6px; font-weight: bold; font-size: 13px; color: #334155; }
input[type="text"], textarea, select { width: 100%; padding: 9px 12px; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; }
.btn { padding: 9px 16px; border: none; border-radius: 6px; color: white; cursor: pointer; text-decoration: none; display: inline-block; font-size: 13px; font-weight: 500; }
.btn-blue { background-color: #0284c7; }
.btn-blue:hover { background-color: #0369a1; }
.btn-secondary { background-color: #64748b; }
.btn-secondary:hover { background-color: #475569; }
.img-preview { width: 70px; height: 85px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; margin-bottom: 8px; }
</style>
</head>
<body>
<div class="container">
<h2>Edit Data Siswa</h2>
<br>
<form action="update.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $data['id']; ?>">

<div class="form-group">
<label>NIS:</label>
<input type="text" name="nis" value="<?php echo htmlspecialchars($data['nis']); ?>" required>
</div>

<div class="form-group">
<label>Nama Lengkap:</label>
<input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
</div>

<div class="form-group">
<label>Pilihan Jurusan:</label>
<select name="id_jurusan" required>
<option value="">-- Pilih Jurusan --</option>
<?php
$jur_q = mysqli_query($koneksi, "SELECT * FROM tb_jurusan");
while($j = mysqli_fetch_assoc($jur_q)){
    $selected = ($j['id_jurusan'] == $data['id_jurusan']) ? 'selected' : '';
    echo "<option value='{$j['id_jurusan']}' $selected>{$j['nama_jurusan']}</option>";
}
?>
</select>
</div>

<div class="form-group">
<label>Pasfoto Saat Ini:</label>
<div>
    <img src="uploads/<?php echo htmlspecialchars($data['foto']); ?>" class="img-preview" alt="Foto Siswa">
</div>
<label style="font-weight: normal; font-size: 12px; color: #64748b;">Ganti Foto (Biarkan kosong jika tidak ingin mengubah):</label>
<input type="file" name="foto" accept="image/*">
</div>

<div class="form-group">
<label>Alasan Masuk:</label>
<textarea name="alasan" rows="3" required><?php echo htmlspecialchars($data['alasan']); ?></textarea>
</div>

<div style="display: flex; gap: 10px; margin-top: 20px;">
<button type="submit" class="btn btn-blue">Simpan Perubahan</button>
<a href="dashboard.php" class="btn btn-secondary">Batal</a>
</div>
</form>
</div>
</body>
</html>