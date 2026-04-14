<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['Manajer', 'Kepala Gudang', 'Karyawan Kandang'])) {
    header("Location: login.php?pesan=akses_ditolak");
    exit;
}

$query = "SELECT * FROM kebutuhan_pakan ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$jumlah_data = mysqli_num_rows($result);
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Operasional - Sistem Peternakan</title>
  <link rel="stylesheet" href="style.css" />
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
        <a href="data_telur.php">
          <img src="assets/telur.svg" alt="icon" />
          Data Telur
        </a>
        <a class="active" href="kebutuhan_pakan.php">
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
      <div class="header-flex" style="display:flex; justify-content:space-between; align-items:center;">
        <div class="page-title">
          <h2>Kebutuhan Pakan</h2>
          <p>Input dan kelola kebutuhan pakan ternak harian</p>
        </div>
        <?php if($jumlah_data > 0): ?>
          <button class="btn btn-primary" onclick="openModal()">+ Input Data Baru</button>
        <?php endif; ?>
      </div>

      <?php if($jumlah_data == 0): ?>
        <div class="panel" style="display: flex; align-items: center; justify-content: center; margin-top: 20px; min-height: 400px;">
          <div class="empty-state" style="border:none;">
            <div class="badge-icon" style="width: 80px; height: 80px; margin: 0 auto 20px; background: #f0fdf4; border-radius: 50%;">
              <img src="assets/pakan.svg" style="width: 40px; height: 40px;" />
            </div>
            <h3>Input Kebutuhan Pakan</h3>
            <p>Klik tombol di bawah untuk mulai input kebutuhan pakan</p>
            <button class="btn btn-primary" onclick="openModal()" style="margin-top:20px;">Mulai Input Data</button>
          </div>
        </div>
      <?php else: ?>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Kandang</th>
                <th>Jenis Pakan</th>
                <th>Jumlah</th>
                <th>Konsumsi/Ekor</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                  <td><strong><?php echo htmlspecialchars($row['nomor_kandang']); ?></strong></td>
                  <td><?php echo htmlspecialchars($row['jenis_pakan']); ?></td>
                  <td><?php echo $row['jumlah_kebutuhan'] . " " . $row['satuan']; ?></td>
                  <td><?php echo $row['konsumsi_per_ekor']; ?> gram</td>
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
        <h3>Form Kebutuhan Pakan</h3>
        <span class="close-btn" onclick="closeModal()">&times;</span>
      </div>
      <form class="form" action="proses_kebutuhan_pakan.php" method="POST">
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
            <label>Jenis Pakan *</label>
            <input type="text" name="jenis_pakan" placeholder="Contoh: BR-1, Starter" required />
          </div>
          <div class="input">
            <label>Waktu Pemberian</label>
            <input type="text" name="waktu_pemberian" placeholder="Pagi / Sore" />
          </div>
          <div class="input">
            <label>Jumlah Kebutuhan *</label>
            <input type="number" name="jumlah_kebutuhan" step="0.01" required />
          </div>
          <div class="input">
            <label>Satuan</label>
            <input type="text" name="satuan" placeholder="Kg / Sak" />
          </div>
          <div class="input">
            <label>Jumlah Ternak</label>
            <input type="number" name="jumlah_ternak" />
          </div>
          <div class="input">
            <label>Konsumsi Per Ekor (gram)</label>
            <input type="number" name="konsumsi_per_ekor" step="0.1" />
          </div>
          <div class="input full-width">
            <label>Catatan</label>
            <textarea name="catatan" rows="3" placeholder="Catatan tambahan mengenai kebutuhan pakan"></textarea>
          </div>
        </div>
        <div class="modal-footer" style="display:flex; gap:10px; margin-top:20px;">
          <button type="button" class="btn" style="flex:1; background:#f3f4f6;" onclick="closeModal()">Batal</button>
          <button type="submit" name="submit_pakan" class="btn btn-primary" style="flex:1;">Simpan Data</button>
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
