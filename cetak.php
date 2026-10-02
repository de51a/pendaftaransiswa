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
$query = mysqli_query($koneksi, "SELECT tb_siswa.*, tb_jurusan.nama_jurusan 
                                 FROM tb_siswa 
                                 JOIN tb_jurusan ON tb_siswa.id_jurusan = tb_jurusan.id_jurusan 
                                 WHERE tb_siswa.id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data siswa tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kartu Pendaftaran - <?php echo htmlspecialchars($data['nama']); ?></title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
body { background-color: #f1f5f9; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
.card-box { background: #ffffff; width: 450px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden; }
.card-header { background: linear-gradient(135deg, #1e293b, #0f172a); color: #ffffff; padding: 20px; text-align: center; border-bottom: 3px solid #38bdf8; }
.card-header h2 { font-size: 18px; text-transform: uppercase; letter-spacing: 1px; }
.card-header p { font-size: 12px; color: #94a3b8; margin-top: 4px; }
.card-body { padding: 25px; display: flex; gap: 20px; }
.photo-area { width: 110px; text-align: center; }
.photo-area img { width: 100px; height: 130px; object-fit: cover; border-radius: 8px; border: 2px solid #cbd5e1; box-shadow: 0 2px 4px rgba(0,0,0,0.08); }
.details-area { flex: 1; font-size: 13px; color: #334155; }
.detail-row { margin-bottom: 12px; }
.detail-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; margin-bottom: 2px; }
.detail-value { font-size: 14px; font-weight: 600; color: #0f172a; }
.card-footer { background: #f8fafc; padding: 12px 25px; border-top: 1px solid #e2e8f0; font-size: 11px; color: #94a3b8; display: flex; justify-content: space-between; align-items: center; }
.action-bar { margin-top: 20px; display: flex; gap: 10px; }
.btn { padding: 8px 16px; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; transition: 0.2s; }
.btn-print { background: #2563eb; color: white; }
.btn-print:hover { background: #1d4ed8; }
.btn-back { background: #e2e8f0; color: #334155; }
.btn-back:hover { background: #cbd5e1; }

@media print {
    body { background: white; padding: 0; justify-content: flex-start; }
    .card-box { box-shadow: none; border: 2px solid #000; width: 100%; max-width: 500px; }
    .action-bar { display: none; }
}
</style>
</head>
<body>

<div class="card-box">
    <div class="card-header">
        <h2>SMK DIGITAL INDONESIA</h2>
        <p>KARTU TANDA PENDAFTARAN SISWA BARU</p>
    </div>
    <div class="card-body">
        <div class="photo-area">
            <img src="uploads/<?php echo htmlspecialchars($data['foto']); ?>" alt="Foto Siswa">
        </div>
        <div class="details-area">
            <div class="detail-row">
                <div class="detail-label">NIS / ID Pendaftaran</div>
                <div class="detail-value"><?php echo htmlspecialchars($data['nis']); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Nama Lengkap</div>
                <div class="detail-value"><?php echo htmlspecialchars($data['nama']); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Jurusan Pilihan</div>
                <div class="detail-value" style="color: #2563eb;"><?php echo htmlspecialchars($data['nama_jurusan']); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Alasan Masuk</div>
                <div class="detail-value" style="font-size: 12px; font-weight: normal; font-style: italic; color: #475569;">
                    "<?php echo htmlspecialchars($data['alasan']); ?>"
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <span>Status: TERDAFTAR</span>
        <span>Dicetak: <?php echo date('d-m-Y H:i'); ?></span>
    </div>
</div>

<div class="action-bar">
    <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Kartu</button>
    <a href="dashboard.php" class="btn btn-back">Kembali ke Dashboard</a>
</div>

</body>
</html>