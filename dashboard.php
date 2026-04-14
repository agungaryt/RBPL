<?php
session_start();
include 'koneksi.php'; 

if (!isset($_SESSION['status_login'])) {
    header("Location: login.php");
    exit;
}

$q_persediaan = mysqli_query($conn, "SELECT COUNT(*) as total FROM data_persediaan");
$total_item = mysqli_fetch_assoc($q_persediaan)['total'] ?? 0;

$q_telur = mysqli_query($conn, "SELECT SUM(jumlah_total_telur) as total FROM data_telur");
$total_telur = mysqli_fetch_assoc($q_telur)['total'] ?? 0;

$q_kandang = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_kandang");
$total_laporan = mysqli_fetch_assoc($q_kandang)['total'] ?? 0;

$q_nota = mysqli_query($conn, "SELECT COUNT(*) as total FROM nota_pengiriman");
$total_nota = mysqli_fetch_assoc($q_nota)['total'] ?? 0;

$sql_7hari = "SELECT tanggal, SUM(jumlah_total_telur) as harian 
              FROM data_telur 
              WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
              GROUP BY tanggal ORDER BY tanggal DESC";
$res_7hari = mysqli_query($conn, $sql_7hari);

$sql_dist_persediaan = "SELECT kategori, SUM(jumlah) as total_qty FROM data_persediaan GROUP BY kategori LIMIT 5";
$res_dist = mysqli_query($conn, $sql_dist_persediaan);
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Operasional - Sistem Peternakan</title>
  <link rel="stylesheet" href="style.css" />
  <style>
      .mini-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
      .mini-table td { padding: 8px 0; border-bottom: 1px solid #eee; font-size: 14px; }
      .mini-table tr:last-child td { border-bottom: none; }
      .text-right { text-align: right; }
  </style>
</head>
<body>

  <div class="container">
    <aside class="sidebar">
      <div class="brand">
        <div class="logo"><img src="assets/burung.svg" alt="icon" /></div>
        <div class="title"><h1>Peternakan</h1><p>Sistem Manajemen</p></div>
      </div>
      <nav class="nav">
        <a class="active" href="dashboard.php"><img src="assets/dashboard.svg" alt="icon" /> Dashboard Operasional</a>
        <a href="upload_nota.php"><img src="assets/upload.svg" alt="icon" /> Upload Nota</a>
        <a href="data_persediaan.php"><img src="assets/pengiriman_abu.svg" alt="icon" /> Data Persediaan</a>
        <a href="laporan_kandang.php"><img src="assets/home_abu.svg" alt="icon" /> Laporan Kandang</a>
        <a href="data_telur.php"><img src="assets/telur.svg" alt="icon" /> Data Telur</a>
        <a href="kebutuhan_pakan.php"><img src="assets/pakan.svg" alt="icon" /> Kebutuhan Pakan</a>
        <a href="nota_pengiriman.php"><img src="assets/pengiriman_abu.svg" alt="icon" /> Nota Pengiriman</a>
        <a href="lihat_semua_nota.php"><img src="assets/selengkapnya.svg" alt="icon" /> Lihat Nota</a>
        <a href="ekspor_laporan.php"><img src="assets/download.svg" alt="icon" /> Ekspor Laporan</a>
        <div class="spacer"></div>
        <div class="logout"><a href="logout.php"><img src="assets/keluar.svg" alt="icon" /> Keluar</a></div>
      </nav>
    </aside>

    <main class="content">
      <div class="page-title">
        <h2>Dashboard Operasional</h2>
        <p>Monitor aktivitas dan status peternakan Anda</p>
      </div>

      <section class="grid-4">
        <div class="card">
          <div class="card-top">
            <div class="badge-icon" style="background:#dcfce7;"><img src="assets/pengiriman_hijau.svg" alt="icon" /></div>
            <div class="trend">↗</div>
          </div>
          <h3>Total Persediaan</h3>
          <p class="value"><?php echo number_format($total_item); ?> Item</p>
        </div>

        <div class="card">
          <div class="card-top">
            <div class="badge-icon" style="background:#e0f2fe;"><img src="assets/telur_biru.svg" alt="icon" /></div>
            <div class="trend">↗</div>
          </div>
          <h3>Total Produksi Telur</h3>
          <p class="value"><?php echo number_format($total_telur); ?> Butir</p>
        </div>

        <div class="card">
          <div class="card-top">
            <div class="badge-icon" style="background:#f3e8ff;"><img src="assets/home_ungu.svg" alt="icon" /></div>
            <div class="trend">↗</div>
          </div>
          <h3>Laporan Kandang</h3>
          <p class="value"><?php echo $total_laporan; ?> Laporan</p>
        </div>

        <div class="card">
          <div class="card-top">
            <div class="badge-icon" style="background:#ffedd5;"><img src="assets/file_oren.svg" alt="icon" /></div>
            <div class="trend">↗</div>
          </div>
          <h3>Nota Pengiriman</h3>
          <p class="value"><?php echo $total_nota; ?> Nota</p>
        </div>
      </section>

      <section class="grid-2">
        <div class="panel">
          <h4>Produksi Telur (7 Hari Terakhir)</h4>
          <div style="margin-top:10px;">
            <?php if(mysqli_num_rows($res_7hari) > 0): ?>
                <table class="mini-table">
                    <?php while($row = mysqli_fetch_assoc($res_7hari)): ?>
                    <tr>
                        <td><?php echo date('d M Y', strtotime($row['tanggal'])); ?></td>
                        <td class="text-right"><strong><?php echo number_format($row['harian']); ?></strong> Butir</td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div>
                        <img src="assets/telur_putih.svg" alt="icon" />
                        <p>Belum ada data produksi 7 hari terakhir</p>
                    </div>
                </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="panel">
          <h4>Distribusi Persediaan</h4>
          <div style="margin-top:10px;">
            <?php if(mysqli_num_rows($res_dist) > 0): ?>
                <table class="mini-table">
                    <?php while($row = mysqli_fetch_assoc($res_dist)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                        <td class="text-right"><strong><?php echo number_format($row['total_qty']); ?></strong> Qty</td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div>
                        <img src="assets/pengiriman_putih.svg" alt="icon" />
                        <p>Belum ada data kategori persediaan</p>
                    </div>
                </div>
            <?php endif; ?>
          </div>
        </div>
      </section>

      <section style="margin-top:14px;">
        <div class="panel" style="min-height: 220px;">
          <h4>Aktivitas Terkini</h4>
          <div class="table-container" style="border:none;">
            <table>
              <?php
                $sql_aktifitas = "(SELECT 'Nota Pembelian' as tipe, nomor_nota as info, upload_at as waktu FROM notaPembelian)
                  UNION 
                  (SELECT 'Laporan Kandang' as tipe, nomor_kandang as info, created_at as waktu FROM laporan_kandang)
                  UNION 
                  (SELECT 'Data Telur' as tipe, nomor_kandang as info, created_at as waktu FROM data_telur)
                  UNION 
                  (SELECT 'Kebutuhan Pakan' as tipe, nomor_kandang as info, created_at as waktu FROM kebutuhan_pakan)
                  ORDER BY waktu DESC LIMIT 5";
                $res_aktifitas = mysqli_query($conn, $sql_aktifitas);
              
              if(mysqli_num_rows($res_aktifitas) > 0){
                  while($act = mysqli_fetch_assoc($res_aktifitas)){
                      echo "<tr>
                              <td style='border:none; padding:8px 0;'><strong>" . htmlspecialchars($act['tipe']) . "</strong></td>
                              <td style='border:none; color:var(--muted);'>ID: " . htmlspecialchars($act['info']) . "</td>
                              <td style='border:none; text-align:right;'>" . date('H:i', strtotime($act['waktu'])) . "</td>
                            </tr>";
                  }
              } else {
                  echo "<tr><td colspan='3' style='text-align:center; color:var(--muted); padding-top:40px;'>Belum ada aktivitas</td></tr>";
              }
              ?>
            </table>
          </div>
        </div>
      </section>

    </main>
  </div>

</body>
</html>
