<?php 
session_start(); 
$angka1 = rand(1, 9);
$angka2 = rand(1, 9);
$_SESSION['captcha_hasil'] = $angka1 + $angka2;
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register - Sistem Peternakan</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <div class="auth-wrap">
    <div class="auth-card">
      <div class="auth-head">
        <div class="logo"><img src="assets/burung.svg" alt="icon" /></div>
        <div>
          <h2>Daftar Akun</h2>
          <p>Sistem Manajemen Peternakan</p>
        </div>
      </div>

      <?php if(isset($_GET['pesan'])): ?>
          <p style="color: #dc2626; text-align: center; font-size: 13px; margin-bottom: 10px; padding: 8px; background: #fee2e2; border-radius: 8px;">
            <?php 
                if($_GET['pesan'] == 'captcha_salah') echo "Hasil Captcha salah!";
                if($_GET['pesan'] == 'username_ada') echo "Username sudah terdaftar!";
            ?>
          </p>
      <?php endif; ?>

      <form class="form" action="proses_register.php" method="POST">
        <div class="input">
          <label>Username</label>
          <input name="username" type="text" placeholder="Masukkan username" required />
        </div>

        <div class="input">
          <label>Password</label>
          <input name="password" type="password" placeholder="••••••••" required />
        </div>

        <div class="input">
                <label>Role / Jabatan</label>
                <select name="id_role" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #e5e7eb;">
                <option value="">-- Pilih Role --</option>
                <option value="1">Manajer</option>
                <option value="2">Sopir</option>
                <option value="3">Kepala Gudang</option>
                <option value="4">Karyawan Kandang</option>
            </select>
            </div>

        <div class="input">
          <label>Keamanan: Berapa hasil <b><?php echo "$angka1 + $angka2"; ?></b>?</label>
          <input name="captcha_input" type="number" placeholder="Jawaban Anda" required />
        </div>

        <button class="btn btn-primary" name="register" type="submit">Daftar Sekarang</button>
      </form>

      <div class="auth-foot">
        <span>Sudah punya akun? <a href="login.php" style="color:var(--primary); font-weight:600;">Login di sini</a></span>
      </div>
    </div>
  </div>

</body>
</html>
