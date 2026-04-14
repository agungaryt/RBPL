<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login']) || !in_array($_SESSION['role'], ['Manajer', 'Sopir'])) {
    header("Location: login.php?pesan=akses_ditolak");
    exit;
}

$query = "SELECT * FROM nota_pengiriman ORDER BY created_at DESC";
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
        <a href="kebutuhan_pakan.php">
          <img src="assets/pakan.svg" alt="icon" />
          Kebutuhan Pakan
        </a>
        <a  class="active" href="nota_pengiriman.php">
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
          <h2>Nota Pengiriman</h2>
          <p>Buat nota pengiriman produk peternakan</p>
        </div>
        <?php if($jumlah_data > 0): ?>
          <button class="btn btn-primary" onclick="openModal()">+ Buat Nota Baru</button>
        <?php endif; ?>
      </div>

      <?php if($jumlah_data == 0): ?>
        <div class="panel" style="display: flex; align-items: center; justify-content: center; margin-top: 20px; min-height: 400px;">
          <div class="empty-state" style="border:none;">
            <div class="badge-icon" style="width: 80px; height: 80px; margin: 0 auto 20px; background: #f0fdf4; border-radius: 50%;">
              <img src="assets/upload.svg" style="width: 40px; height: 40px;" />
            </div>
            <h3>Buat Nota Pengiriman Baru</h3>
            <p>Klik tombol di bawah untuk mulai membuat nota pengiriman</p>
            <button class="btn btn-primary" onclick="openModal()" style="margin-top:20px;">Buat Nota Pengiriman</button>
          </div>
        </div>
      <?php else: ?>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>No. Nota</th>
                <th>Tanggal</th>
                <th>Penerima</th>
                <th>Tujuan</th>
                <th>Produk</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><strong><?php echo $row['nomor_nota']; ?></strong></td>
                  <td><?php echo date('d/m/Y', strtotime($row['tanggal_pengiriman'])); ?></td>
                  <td><?php echo $row['nama_penerima']; ?></td>
                  <td><?php echo $row['tujuan_kota']; ?></td>
                  <td><?php echo $row['jumlah_produk'] . " " . $row['satuan']; ?></td>
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
        <h3>Form Nota Pengiriman</h3>
        <span class="close-btn" onclick="closeModal()">&times;</span>
      </div>
      <form class="form" action="proses_nota_pengiriman.php" method="POST">
        <div class="form-grid">
          <div class="input">
            <label>Nomor Nota *</label>
            <input type="text" name="nomor_nota" placeholder="DN-2024-001" required />
          </div>
          <div class="input">
            <label>Tanggal Pengiriman *</label>
            <input type="date" name="tanggal_pengiriman" required />
          </div>
          <div class="input">
            <label>Nama Penerima *</label>
            <input type="text" name="nama_penerima" placeholder="Nama penerima" required />
          </div>
          <div class="input">
            <label>Telepon</label>
            <input type="text" name="telepon" placeholder="08xx-xxxx-xxxx" />
          </div>
          <div class="input full-width">
            <label>Tujuan / Kota</label>
            <input type="text" name="tujuan_kota" placeholder="Nama kota tujuan" />
          </div>
          <div class="input full-width">
            <label>Alamat Lengkap Penerima</label>
            <textarea name="alamat_lengkap" rows="3" placeholder="Alamat lengkap penerima"></textarea>
          </div>
          <div class="input">
            <label>Jenis Produk</label>
            <input type="text" name="jenis_produk" />
          </div>
          <div class="input">
            <label>Jumlah Produk *</label>
            <input type="number" name="jumlah_produk" value="0" required />
          </div>
          <div class="input">
            <label>Satuan</label>
            <input type="text" name="satuan" placeholder="Butir / Kg / Sak" />
          </div>
          <div class="input">
            <label>Ongkos Kirim (Rp)</label>
            <input type="number" name="ongkos_kirim" value="0" />
          </div>
        </div>
        <div class="modal-footer" style="display:flex; gap:10px; margin-top:20px;">
          <button type="button" class="btn" style="flex:1; background:#f3f4f6;" onclick="closeModal()">Batal</button>
          <button type="submit" name="submit_nota" class="btn btn-primary" style="flex:1;">Buat Nota</button>
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
