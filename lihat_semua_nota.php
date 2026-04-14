<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login'])) {
    header("Location: login.php");
    exit;
}

$query = "
    (SELECT 
        'Pembelian' as kategori, 
        nomor_nota, 
        tanggal, 
        supplier as pihak_terkait, 
        'Gudang' as tujuan, 
        'Logistik' as produk, 
        '-' as jumlah
    FROM notaPembelian)
    UNION
    (SELECT 
        'Pengiriman' as kategori, 
        nomor_nota, 
        tanggal_pengiriman as tanggal, 
        nama_penerima as pihak_terkait, 
        tujuan_kota as tujuan, 
        jenis_produk as produk, 
        jumlah_produk as jumlah
    FROM nota_pengiriman)
    ORDER BY tanggal DESC";

$result = mysqli_query($conn, $query);
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <title>Semua Nota - Sistem Peternakan</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .search-container { margin: 20px 0; }
    .search-bar { width: 100%; padding: 12px; border-radius: 10px; border: 1px solid var(--border); outline: none; }
    .badge-kategori { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
    .bg-pembelian { background: #dcfce7; color: #166534; }
    .bg-pengiriman { background: #dbeafe; color: #1e40af; }
    .btn-view { color: var(--primary); font-weight: 600; cursor: pointer; }
  </style>
</head>
<body>

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
        <a href="nota_pengiriman.php">
          <img src="assets/pengiriman_abu.svg" alt="icon" />
          Nota Pengiriman
        </a>
        <a class="active" href="lihat_semua_nota.php">
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
        <h2>Riwayat Semua Nota</h2>
        <p>Gabungan data nota pembelian dan pengiriman</p>
      </div>

      <div class="search-container">
        <input type="text" id="searchInput" class="search-bar" placeholder="Cari nomor nota, supplier, atau penerima...">
      </div>

      <div class="table-container" style="background:#fff; border-radius:14px; border:1px solid var(--border); overflow:hidden;">
        <table>
          <thead>
            <tr>
              <th>Kategori</th>
              <th>Nomor Nota</th>
              <th>Tanggal</th>
              <th>Pihak Terkait</th>
              <th>Produk/Tujuan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="notaTable">
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td>
                <span class="badge-kategori <?php echo ($row['kategori'] == 'Pembelian') ? 'bg-pembelian' : 'bg-pengiriman'; ?>">
                  <?php echo $row['kategori']; ?>
                </span>
              </td>
              <td><strong><?php echo htmlspecialchars($row['nomor_nota']); ?></strong></td>
              <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
              <td><?php echo htmlspecialchars($row['pihak_terkait']); ?></td>
              <td><?php echo htmlspecialchars($row['tujuan']); ?></td>
              <td>
                <span class="btn-view" onclick='viewDetail(<?php echo json_encode($row); ?>)'>👁 Detail</span>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <div id="detailModal" class="modal-overlay">
    <div class="modal-card" style="max-width: 500px;">
      <div class="modal-header">
        <h3 id="modalTitle">Detail Nota</h3>
        <span class="close-btn" onclick="closeModal()">&times;</span>
      </div>
      <div id="modalBody" style="margin-top:20px; line-height: 1.8;"></div>
      <button class="btn" style="width:100%; margin-top:20px; background:#f3f4f6;" onclick="closeModal()">Tutup</button>
    </div>
  </div>

  <script>
    function viewDetail(data) {
        document.getElementById('modalTitle').innerText = 'Detail Nota ' + data.kategori;
        let content = `
            <div style="display:grid; gap:12px;">
                <div><label style="color:#6b7280; font-size:12px;">Nomor Nota</label><br><b>${data.nomor_nota}</b></div>
                <div><label style="color:#6b7280; font-size:12px;">Tanggal</label><br><b>${data.tanggal}</b></div>
                <div><label style="color:#6b7280; font-size:12px;">Pihak Terkait</label><br><b>${data.pihak_terkait}</b></div>
                <div><label style="color:#6b7280; font-size:12px;">Produk/Keterangan</label><br><b>${data.produk} (${data.jumlah})</b></div>
                <div><label style="color:#6b7280; font-size:12px;">Tujuan/Lokasi</label><br><b>${data.tujuan}</b></div>
            </div>
        `;
        document.getElementById('modalBody').innerHTML = content;
        document.getElementById('detailModal').classList.add('active');
    }

    function closeModal() { document.getElementById('detailModal').classList.remove('active'); }

    document.getElementById('searchInput').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        let rows = document.querySelectorAll('#notaTable tr');
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
        });
    });
  </script>
</body>
</html>
