<?php session_start(); ?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Sistem Peternakan</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <div class="auth-wrap">
    <div class="auth-card">
      <div class="auth-head">
        <div class="logo">
          <img src="assets/burung.svg" alt="icon" />
        </div>
        <div>
          <h2>Peternakan</h2>
          <p>Sistem Manajemen</p>
        </div>
      </div>

      <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
          <p style="color: #dc2626; text-align: center; font-size: 13px; margin-bottom: 10px; padding: 8px; background: #fee2e2; border-radius: 8px;">
            Username atau Password salah!
          </p>
      <?php endif; ?>

      <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil_daftar'): ?>
          <p style="color: #166534; text-align: center; font-size: 13px; margin-bottom: 10px; padding: 8px; background: #dcfce7; border-radius: 8px;">
            Registrasi berhasil! Silakan login.
          </p>
      <?php endif; ?>

      <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'akses_ditolak'): ?>
          <p style="color: #854d0e; text-align: center; font-size: 13px; margin-bottom: 10px; padding: 8px; background: #fef9c3; border-radius: 8px;">
            Anda harus login terlebih dahulu!
          </p>
      <?php endif; ?>

      <form class="form" action="proses_login.php" method="POST">
        <div class="input">
          <label for="username">Username</label>
          <input id="username" name="username" type="text" placeholder="Masukkan username" required />
        </div>

        <div class="input">
          <label for="pass">Password</label>
          <input id="pass" name="password" type="password" placeholder="••••••••" required />
        </div>

        <button class="btn btn-primary" name="login" type="submit" style="width: 100%;">Masuk</button>
      </form>

      <div class="auth-foot" style="margin-top: 20px; text-align: center; font-size: 14px;">
        <span>Belum punya akun? <a href="register.php" style="color: #22c55e; font-weight: 600; text-decoration: none;">Daftar di sini</a></span>
      </div>
    </div>
  </div>

</body>
</html>
