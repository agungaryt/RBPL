<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['Manajer', 'Kepala Gudang'])) {
    header("Location: login.php?pesan=akses_ditolak");
    exit;
}

$query = "SELECT * FROM data_persediaan ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$jumlah_data = mysqli_num_rows($result);
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <title>Data Persediaan - Sistem Peternakan</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
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
        <a class="active" href="data_persediaan.php">
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
        <a href="#ekspor_laporan.php">
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
          <h2>Data Persediaan</h2>
          <p>Kelola data persediaan barang peternakan</p>
        </div>
      </div>

      <button class="btn btn-primary" onclick="openModal()" style="margin-top:20px;">+ Tambah Persediaan</button>

      <?php if($jumlah_data == 0): ?>
        <div class="panel" style="display: flex; align-items: center; justify-content: center; margin-top: 20px; min-height: 400px;">
          <div class="empty-state" style="border:none;">
            <div class="badge-icon" style="width: 80px; height: 80px; margin: 0 auto 20px; background: #fff; border: 2px solid var(--border); border-radius: 50%;">
              <img src="assets/pengiriman_abu.svg" style="width: 40px; height: 40px; opacity: 0.2;" />
            </div>
            <p>Belum ada data persediaan</p>
          </div>
        </div>
      <?php else: ?>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Nama Item</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Supplier</th>
                <th>Lokasi</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><strong><?php echo htmlspecialchars($row['nama_item']); ?></strong></td>
                  <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                  <td><?php echo number_format($row['jumlah']) . " " . $row['satuan']; ?></td>
                  <td><?php echo htmlspecialchars($row['supplier']); ?></td>
                  <td><?php echo htmlspecialchars($row['lokasi_penyimpanan']); ?></td>
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
        <h3>Form Input Persediaan</h3>
        <span class="close-btn" onclick="closeModal()">&times;</span>
      </div>
      <form class="form" action="proses_data_persediaan.php" method="POST">
        <div class="form-grid">
          <div class="input">
            <label>Nama Item *</label>
            <input type="text" name="nama_item" placeholder="Nama barang" required />
          </div>
          <div class="input">
            <label>Kategori *</label>
            <input type="text" name="kategori" placeholder="Pakan / Obat / dll" required />
          </div>
          <div class="input">
            <label>Jumlah *</label>
            <input type="number" name="jumlah" value="0" required />
          </div>
          <div class="input">
            <label>Satuan</label>
            <input type="text" name="satuan" placeholder="Kg / Liter / Karung" />
          </div>
          <div class="input">
            <label>Tanggal Masuk *</label>
            <input type="date" name="tanggal_masuk" required />
          </div>
          <div class="input">
            <label>Supplier</label>
            <input type="text" name="supplier" placeholder="Nama supplier" />
          </div>
          <div class="input">
            <label>Harga (Rp)</label>
            <input type="number" name="harga_satuan" value="0" />
          </div>
          <div class="input">
            <label>Lokasi Penyimpanan</label>
            <input type="text" name="lokasi_penyimpanan" placeholder="Gudang A, Rak 1, dll" />
          </div>
        </div>
        <div class="modal-footer" style="display:flex; gap:10px; margin-top:20px;">
          <button type="button" class="btn" style="flex:1; background:#f3f4f6;" onclick="closeModal()">Batal</button>
          <button type="submit" name="submit_persediaan" class="btn btn-primary" style="flex:1;">Simpan Data</button>
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
