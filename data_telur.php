<?php
session_start();
include 'koneksi.php';

// Proteksi Halaman: Cek login & Role (Sesuai RF-012)
if (!isset($_SESSION['status_login'])) {
    header("Location: login.php");
    exit;
}

// Ambil data telur dari database
$query = "SELECT * FROM data_telur ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$jumlah_data = mysqli_num_rows($result);
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <title>Data Telur - Sistem Peternakan</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .full-width { grid-column: span 2; }
    .table-container { margin-top: 20px; background: #fff; border-radius: 14px; border: 1px solid var(--border); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid var(--border); font-size: 14px; }
    th { background: #f9fafb; color: var(--muted); }
  </style>
</head>
<body>

 <div class="container">
    <aside class="sidebar">
      <div class="brand">
        <div class="logo">
          <img src="assets/burung.svg" alt="icon" />
        </div>
        <div class="title">
          <h1>Peternakan</h1>
          <p>Sistem Manajemen</p>
        </div>
      </div>

      <nav class="nav">
        <a href="dashboard.php">
          <img src="assets/dashboard.svg" alt="icon" />
          Dashboard Operasional
        </a>
        <a href="upload_nota.php">
          <img src="assets/upload.svg" alt="icon" />
          Upload Nota
        </a>
        <a href="data_persediaan.php">
          <img src="assets/pengiriman_abu.svg" alt="icon" />
          Data Persediaan
        </a>
        <a href="laporan_kandang.php">
          <img src="assets/home_abu.svg" alt="icon" />
          Laporan Kandang
        </a>
        <a  class="active" href="data_telur.php">
          <img src="assets/telur.svg" alt="icon" />
          Data Telur
        </a>
        <a href="kebutuhan_pakan.php">
          <img src="assets/pakan.svg" alt="icon" />
          Kebutuhan Pakan
        </a>
        <a href="nota_pengiriman.php">
          <img src="assets/pengiriman_abu.svg" alt="icon" />
          Nota Pengiriman
        </a>
        <a href="lihat_semua_nota.php">
          <img src="assets/selengkapnya.svg" alt="icon" />
          Lihat Nota 
        </a>
        <a href="ekspor_laporan.php">
          <img src="assets/download.svg" alt="icon" />
          Ekspor Laporan
        </a>

        <div class="spacer"></div>

        <div class="logout">
          <a href="logout.php">
            <img src="assets/keluar.svg" alt="icon" />
            Keluar
          </a>
        </div>
      </nav>
    </aside>

    <main class="content">
      <div class="page-title">
        <h2>Data Telur</h2>
        <p>Kelola data produksi telur harian</p>
      </div>

      <button class="btn btn-primary" onclick="openModal()" style="margin-top:20px;">+ Tambah Data Telur</button>

      <?php if($jumlah_data == 0): ?>
        <div class="panel" style="display: flex; align-items: center; justify-content: center; margin-top: 20px; min-height: 350px;">
          <div class="empty-state" style="border:none;">
            <div class="badge-icon" style="width: 80px; height: 80px; margin: 0 auto 10px; background: #fff; border: 2px solid var(--border); border-radius: 50%;">
              <img src="assets/telur.svg" style="width: 40px; height: 40px; opacity: 0.2;" />
            </div>
            <p>Belum ada data telur</p>
          </div>
        </div>
      <?php else: ?>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Kandang</th>
                <th>Total Butir</th>
                <th>Baik/Retak/Kotor</th>
                <th>Berat (kg)</th>
                <th>Kualitas</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                  <td><strong><?php echo $row['nomor_kandang']; ?></strong></td>
                  <td><?php echo $row['jumlah_total_telur']; ?> Butir</td>
                  <td><?php echo "{$row['telur_baik']}/{$row['telur_retak']}/{$row['telur_kotor']}"; ?></td>
                  <td><?php echo $row['berat_total_kg']; ?> kg</td>
                  <td><?php echo $row['kualitas']; ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </main>
  </div>

  <div id="uploadModal" class="modal-overlay">
    <div class="modal-card" style="max-width: 800px;">
      <div class="modal-header">
        <h3>Form Input Data Telur</h3>
        <span class="close-btn" onclick="closeModal()">&times;</span>
      </div>
      <form class="form" action="proses_data_telur.php" method="POST">
        <div class="form-grid">
          <div class="input">
            <label>Tanggal *</label>
            <input type="date" name="tanggal" required />
          </div>
          <div class="input">
            <label>Nomor Kandang *</label>
            <input type="text" name="nomor_kandang" placeholder="K-001" required />
          </div>
          <div class="input">
            <label>Jumlah Total Telur *</label>
            <input type="number" name="jumlah_total_telur" value="0" required />
          </div>
          <div class="input">
            <label>Telur Baik</label>
            <input type="number" name="telur_baik" value="0" />
          </div>
          <div class="input">
            <label>Telur Retak</label>
            <input type="number" name="telur_retak" value="0" />
          </div>
          <div class="input">
            <label>Telur Kotor</label>
            <input type="number" name="telur_kotor" value="0" />
          </div>
          <div class="input">
            <label>Berat Total (kg)</label>
            <input type="text" name="berat_total_kg" placeholder="0.0" />
          </div>
          <div class="input">
            <label>Kualitas</label>
            <input type="text" name="kualitas" placeholder="A / B / C" />
          </div>
          <div class="input full-width">
            <label>Petugas</label>
            <input type="text" name="petugas" placeholder="Nama petugas" />
          </div>
        </div>
        <div class="modal-footer" style="display:flex; gap:10px; margin-top:20px;">
          <button type="button" class="btn" style="flex:1; background:#f3f4f6;" onclick="closeModal()">Batal</button>
          <button type="submit" name="submit_telur" class="btn btn-primary" style="flex:1;">Simpan Data</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal() { document.getElementById('uploadModal').classList.add('active'); }
    function closeModal() { document.getElementById('uploadModal').classList.remove('active'); }
  </script>
</body>
</html>