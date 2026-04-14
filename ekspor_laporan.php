<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'Manajer') {
    header("Location: login.php?pesan=akses_ditolak");
    exit;
}
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <title>Ekspor Laporan - Sistem Peternakan</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .export-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px; }
    .export-card { 
        background: #fff; border: 2px solid var(--border); border-radius: 14px; padding: 24px; cursor: pointer; transition: all 0.3s;
    }
    .export-card:hover { border-color: var(--primary); background: #f0fdf4; }
    .export-card.active { border-color: var(--primary); background: #f0fdf4; box-shadow: 0 0 0 3px rgba(34,197,94,0.1); }
    .export-card h3 { font-size: 16px; font-weight: 600; margin-bottom: 8px; }
    
    .step-number { 
        width: 32px; height: 32px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-bottom: 20px;
    }
    .download-section { margin-top: 30px; text-align: center; border: 2px dashed var(--border); border-radius: 14px; padding: 40px; }
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
        <a class="active" href="ekspor_laporan.php">
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
        <h2>Ekspor Laporan</h2>
        <p>Pilih jenis laporan dan format untuk mengunduh</p>
      </div>

      <div class="panel" style="margin-top:20px;">
        <div class="step-number">1</div>
        <p style="font-weight: 600;">Pilih Jenis Laporan</p>

        <div class="export-grid">
          <div class="export-card" onclick="selectReport('persediaan')">
            <h3>Laporan Persediaan</h3>
          </div>
          <div class="export-card" onclick="selectReport('telur')">
            <h3>Laporan Produksi Telur</h3>
          </div>
          <div class="export-card" onclick="selectReport('kandang')">
            <h3>Laporan Kondisi Kandang</h3>
          </div>
          <div class="export-card" onclick="selectReport('lengkap')">
            <h3>Laporan Lengkap (Semua Data)</h3>
          </div>
        </div>
      </div>

      <div class="download-section" id="downloadArea">
          <img src="assets/download.svg" style="width: 60px; margin: 0 auto 20px; opacity: 0.2;" />
          <p id="exportStatus" style="color: var(--muted);">Pilih jenis laporan untuk memulai ekspor</p>
          <div id="actionButtons" style="display:none; gap:10px; justify-content:center; margin-top:20px;">
              <a href="#" id="btnExcel" class="btn btn-primary">Download Excel</a>
          </div>
      </div>
    </main>
  </div>

  <script>
  function selectReport(type) {
      document.querySelectorAll('.export-card').forEach(c => c.classList.remove('active'));
      event.currentTarget.classList.add('active');
      
      document.getElementById('exportStatus').innerHTML = "Laporan <b>" + type.toUpperCase() + "</b> siap diunduh dalam format Excel.";
      document.getElementById('actionButtons').style.display = 'flex';
      
      document.getElementById('btnExcel').href = "unduh_laporan.php?type=" + type + "&format=excel";
  }
</script>
</body>
</html>
