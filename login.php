<?php
session_start();
include 'koneksi.php';

// Jika sudah login, redirect langsung ke dashboard.php
if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(mysqli_real_escape_string($koneksi, $_POST['username']));
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi!";
    } else {
        $query = mysqli_query($koneksi, "SELECT * FROM tb_users WHERE username = '$username'");
        if ($data = mysqli_fetch_assoc($query)) {
            // Verifikasi password (password_verify hash atau fallback plaintext)
            if (password_verify($password, $data['password']) || $password === $data['password']) {
                $_SESSION['login'] = true;
                $_SESSION['username'] = $data['username'];
                $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Password yang Anda masukkan salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pendaftaran Siswa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #311042 100%);
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 40px 32px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.1);
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-bottom: 12px;
            box-shadow: 0 8px 16px rgba(79, 70, 229, 0.3);
        }

        .brand-icon svg {
            width: 28px;
            height: 28px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .brand-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .brand-header p {
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 4px;
        }

        .alert-error {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.3s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px 16px 12px 42px;
            font-size: 0.95rem;
            color: #0f172a;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease;
        }

        .input-wrapper input:focus {
            background: #ffffff;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .input-icon svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 2px;
            display: flex;
            align-items: center;
        }

        .toggle-password:hover {
            color: #0f172a;
        }

        .btn-submit {
            width: 100%;
            padding: 12px 20px;
            font-size: 0.95rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            margin-top: 8px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .demo-info {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid #e2e8f0;
            font-size: 0.8rem;
            color: #64748b;
            text-align: center;
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px;
        }

        .demo-info code {
            background: #e2e8f0;
            color: #334155;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-header">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
        </div>
        <h1>Portal Siswa</h1>
        <p>Silakan masuk ke akun admin Anda</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert-error">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php" autocomplete="off">
        <div class="form-group">
            <label for="username">Username</label>
            <div class="input-wrapper">
                <div class="input-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <input type="text" id="username" name="username" placeholder="Masukkan username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required autofocus>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <div class="input-icon">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                <button type="button" class="toggle-password" onclick="togglePasswordVisibility()" title="Tampilkan/Sembunyikan Password">
                    <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-submit">Masuk ke Sistem</button>
    </form>

    <div class="demo-info">
        Akun Default: Username <code>admin</code> | Password <code>admin123</code>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passInput.type === 'password') {
            passInput.type = 'text';
            eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        } else {
            passInput.type = 'password';
            eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        }
    }
</script>

</body>
</html>
