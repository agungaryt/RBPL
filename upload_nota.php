<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['Manajer', 'Sopir'])) {
    header("Location: login.php?pesan=akses_ditolak");
    exit;
}

$query = "SELECT * FROM notaPembelian ORDER BY upload_at DESC";
$result = mysqli_query($conn, $query);
$jumlah_data = mysqli_num_rows($result);
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nota Pembelian - Sistem Peternakan</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    /* CSS Tambahan untuk Tabel */
    .table-container { 
        margin-top: 20px; 
        background: #fff; 
        border-radius: 14px; 
        border: 1px solid var(--border); 
        box-shadow: var(--shadow);
        overflow: hidden; 
    }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f9fafb; padding: 14px; text-align: left; color: var(--muted); font-size: 13px; border-bottom: 1px solid var(--border); }
    td { padding: 14px; border-bottom: 1px solid var(--border); font-size: 14px; color: var(--text); }
    .btn-file { color: var(--primary); font-weight: 600; text-decoration: none; }
    .btn-file:hover { text-decoration: underline; }
    .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
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
        <a class="active" href="upload_nota.php">
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
        <a href="#">
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
      <div class="header-flex">
        <div class="page-title">
          <h2>Nota Pembelian</h2>
          <p>Kelola riwayat nota transaksi peternakan</p>
        </div>
        <?php if($jumlah_data > 0): ?>
          <button class="btn btn-primary" onclick="openModal()">+ Tambah Nota</button>
        <?php endif; ?>
      </div>

      <?php if(isset($_GET['status'])): ?>
        <div style="padding: 12px; margin: 15px 0; border-radius: 10px; background: #dcfce7; color: #166534; border: 1px solid #16a34a;">
          <?php echo ($_GET['status'] == 'sukses') ? "✅ Nota berhasil diupload!" : "❌ Gagal memproses nota."; ?>
        </div>
      <?php endif; ?>

      <?php if($jumlah_data == 0): ?>
        <div class="panel" style="display: flex; align-items: center; justify-content: center; margin-top: 20px; min-height: 350px;">
          <div class="empty-state" style="border: none;">
            <div class="badge-icon" style="width: 80px; height: 80px; margin: 0 auto 20px; background: #f0fdf4;">
              <img src="assets/upload.svg" style="width: 40px; height: 40px;" alt="icon" />
            </div>
            <h2>Belum Ada Nota</h2>
            <p>Klik tombol di bawah untuk mulai upload nota transaksi</p>
            <button class="btn btn-primary" onclick="openModal()" style="margin-top: 20px;">Mulai Upload Nota</button>
          </div>
        </div>
      <?php else: ?>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Nomor Nota</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>Keterangan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><strong><?php echo htmlspecialchars($row['nomor_nota']); ?></strong></td>
                  <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                  <td><?php echo htmlspecialchars($row['supplier']); ?></td>
                  <td><?php echo htmlspecialchars($row['keterangan']) ?: '-'; ?></td>
                  <td>
                    <a href="uploads/<?php echo $row['file_nota']; ?>" target="_blank" class="btn-file">Lihat File</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </main>
  </div>

  <div id="uploadModal" class="modal-overlay">
    <div class="modal-card">
      <div class="modal-header">
        <h3>Form Upload Nota</h3>
        <span class="close-btn" onclick="closeModal()">&times;</span>
      </div>
      <form class="form" action="proses_upload_nota.php" method="POST" enctype="multipart/form-data">
        <div class="input">
          <label>Nomor Nota *</label>
          <input type="text" name="nomor_nota" placeholder="Contoh: NT-001" required />
        </div>
        <div class="input">
          <label>Tanggal *</label>
          <input type="date" name="tanggal" required />
        </div>
        <div class="input">
          <label>Supplier *</label>
          <input type="text" name="supplier" placeholder="Nama supplier" required />
        </div>
        <div class="input">
          <label>File Nota *</label>
          <div class="upload-area">
            <input type="file" name="file_nota" id="fileInput" accept="image/png, image/jpeg, application/pdf" hidden required />
            <label for="fileInput" style="cursor:pointer; text-align:center;">
              <p>Klik untuk pilih file (PNG, JPG, PDF)</p>
            </label>
          </div>
        </div>
        <div class="input">
          <label>Keterangan</label>
          <textarea name="keterangan" rows="2"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" onclick="closeModal()" style="background:#f3f4f6;">Batal</button>
          <button type="submit" name="submit_nota" class="btn btn-primary">Simpan Nota</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal() { document.getElementById('uploadModal').classList.add('active'); }
    function closeModal() { document.getElementById('uploadModal').classList.remove('active'); }
    
  \
    window.onclick = function(event) {
      if (event.target == document.getElementById('uploadModal')) { closeModal(); }
    }
  </script>
</body>
</html>
