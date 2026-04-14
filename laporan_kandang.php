<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['Manajer', 'Karyawan Kandang'])) {
    header("Location: login.php?pesan=akses_ditolak");
    exit;
}

$query = "SELECT * FROM laporan_kandang ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$jumlah_data = mysqli_num_rows($result);
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <title>Laporan Kandang - Sistem Peternakan</title>
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
        <div class="logo"><img src="assets/burung.svg" alt="icon" /></div>
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
        <a class="active" href="laporan_kandang.php">
          <img src="assets/home_abu.svg" alt="icon" />
          Laporan Kandang
        </a>
        <a href="data_telur.php">
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
      <div class="header-flex" style="display:flex; justify-content:space-between; align-items:center;">
        <div class="page-title">
          <h2>Laporan Kondisi Kandang</h2>
          <p>Catat dan monitor kondisi kandang peternakan</p>
        </div>
        <?php if($jumlah_data > 0): ?>
          <button class="btn btn-primary" onclick="openModal()">+ Input Laporan Baru</button>
        <?php endif; ?>
      </div>

      <?php if($jumlah_data == 0): ?>
        <div class="panel" style="display: flex; align-items: center; justify-content: center; margin-top: 20px; min-height: 400px;">
          <div class="empty-state" style="text-align: center; border:none;">
            <div class="badge-icon" style="width: 80px; height: 80px; margin: 0 auto 20px; background: #f0fdf4;">
              <img src="assets/home_abu.svg" style="width: 40px; height: 40px;" />
            </div>
            <h3>Buat Laporan Kandang</h3>
            <p>Klik tombol di bawah untuk mulai input laporan kondisi kandang</p>
            <button class="btn btn-primary" onclick="openModal()" style="margin-top:20px;">Mulai Input Laporan</button>
          </div>
        </div>
      <?php else: ?>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>No. Kandang</th>
                <th>Tanggal</th>
                <th>Jumlah Ayam</th>
                <th>Suhu/Lembab</th>
                <th>Kesehatan</th>
                <th>Penyakit</th>
                <th>Tindakan</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><strong><?php echo htmlspecialchars($row['nomor_kandang']); ?></strong></td>
                  <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                  <td><?php echo number_format($row['jumlah_ayam']); ?> Ekor</td>
                  <td><?php echo $row['suhu']; ?>°C / <?php echo $row['kelembaban']; ?>%</td>
                  <td><?php echo htmlspecialchars($row['kondisi_kesehatan']); ?></td>
                  <td><?php echo htmlspecialchars($row['penyakit']) ?: '-'; ?></td>
                  <td><?php echo htmlspecialchars($row['tindakan_diambil']) ?: '-'; ?></td>
                  <td><small><?php echo htmlspecialchars($row['keterangan_tambahan']) ?: '-'; ?></small></td>
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
        <h3>Form Laporan Kandang</h3>
        <span class="close-btn" onclick="closeModal()">&times;</span>
      </div>
      <form class="form" action="proses_laporan_kandang.php" method="POST">
        <div class="form-grid">
          <div class="input">
            <label>Nomor Kandang *</label>
            <input type="text" name="nomor_kandang" placeholder="Contoh: K-001" required />
          </div>
          <div class="input">
            <label>Tanggal *</label>
            <input type="date" name="tanggal" required />
          </div>
          <div class="input">
            <label>Kondisi Kebersihan</label>
            <input type="text" name="kondisi_kebersihan" placeholder="Sangat Bersih / Cukup" />
          </div>
          <div class="input">
            <label>Jumlah Ayam *</label>
            <input type="number" name="jumlah_ayam" value="0" required />
          </div>
          <div class="input">
            <label>Suhu (°C)</label>
            <input type="text" name="suhu" placeholder="25.0" />
          </div>
          <div class="input">
            <label>Kelembaban (%)</label>
            <input type="text" name="kelembaban" placeholder="60" />
          </div>
          <div class="input full-width">
            <label>Kondisi Kesehatan Ternak</label>
            <input type="text" name="kondisi_kesehatan" />
          </div>
          <div class="input">
            <label>Penyakit (Jika Ada)</label>
            <input type="text" name="penyakit" placeholder="Nama penyakit" />
          </div>
          <div class="input">
            <label>Tindakan yang Diambil</label>
            <input type="text" name="tindakan_diambil" placeholder="Tindakan penanganan" />
          </div>
          <div class="input full-width">
            <label>Keterangan Tambahan</label>
            <textarea name="keterangan_tambahan" rows="3" placeholder="Catatan tambahan mengenai kondisi kandang"></textarea>
          </div>
        </div>
        <div class="modal-footer" style="display:flex; gap:10px; margin-top:20px;">
          <button type="button" class="btn" style="flex:1; background:#f3f4f6;" onclick="closeModal()">Batal</button>
          <button type="submit" name="submit_laporan" class="btn btn-primary" style="flex:1;">Simpan Laporan</button>
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
