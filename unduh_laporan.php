<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login'])) {
    header("Location: login.php");
    exit;
}

$type = $_GET['type'] ?? '';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_" . ucfirst($type) . "_" . date('d-m-Y') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "
<style>
    .table-excel { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
    .table-excel th { background-color: #22c55e; color: white; border: 1px solid #000; padding: 10px; text-transform: uppercase; font-size: 12px; }
    .table-excel td { border: 1px solid #000; padding: 8px; font-size: 11px; vertical-align: top; }
    .header-title { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 5px; }
    .header-info { text-align: center; margin-bottom: 20px; color: #666; }
    .section-title { background-color: #f3f4f6; font-weight: bold; padding: 10px; border: 1px solid #000; }
</style>
";

echo "<div class='header-title'>LAPORAN " . strtoupper(str_replace('_', ' ', $type)) . "</div>";
echo "<div class='header-info'>Sistem Manajemen Peternakan | Dicetak pada: " . date('d-m-Y H:i') . "</div>";

function generateTable($conn, $query, $columns, $title) {
    echo "<table class='table-excel'>";
    echo "<thead><tr><th colspan='" . count($columns) . "' class='section-title'>DATA " . strtoupper($title) . "</th></tr><tr>";
    foreach ($columns as $col) {
        echo "<th>$col</th>";
    }
    echo "</tr></thead><tbody>";

    $res = mysqli_query($conn, $query);
    $no = 1;
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_row($res)) {
            echo "<tr>";
            echo "<td align='center'>$no</td>";
            for ($i = 1; $i < count($row); $i++) {
                $align = is_numeric($row[$i]) ? "align='right'" : "align='left'";
                echo "<td $align>" . htmlspecialchars($row[$i]) . "</td>";
            }
            echo "</tr>";
            $no++;
        }
    } else {
        echo "<tr><td colspan='" . count($columns) . "' align='center'>Tidak ada data ditemukan</td></tr>";
    }
    echo "</tbody></table><br>";
}

if ($type == 'persediaan') {
    $cols = ['NO', 'NAMA ITEM', 'KATEGORI', 'STOK', 'SATUAN', 'SUPPLIER', 'TANGGAL MASUK', 'LOKASI'];
    $sql = "SELECT id_persediaan, nama_item, kategori, jumlah, satuan, supplier, tanggal_masuk, lokasi_penyimpanan FROM data_persediaan ORDER BY tanggal_masuk DESC";
    generateTable($conn, $sql, $cols, "Persediaan");

} elseif ($type == 'telur') {
    $cols = ['NO', 'TANGGAL', 'KANDANG', 'TOTAL BUTIR', 'BERAT (KG)', 'KUALITAS', 'PETUGAS'];
    $sql = "SELECT id_data_telur, tanggal, nomor_kandang, jumlah_total_telur, berat_total_kg, kualitas, petugas FROM data_telur ORDER BY tanggal DESC";
    generateTable($conn, $sql, $cols, "Produksi Telur");

} elseif ($type == 'kandang') {
    $cols = ['NO', 'TANGGAL', 'KANDANG', 'AYAM (EKOR)', 'SUHU', 'LEMBAB', 'KESEHATAN', 'PENYAKIT'];
    $sql = "SELECT id_laporan_kandang, tanggal, nomor_kandang, jumlah_ayam, suhu, kelembaban, kondisi_kesehatan, penyakit FROM laporan_kandang ORDER BY tanggal DESC";
    generateTable($conn, $sql, $cols, "Kondisi Kandang");

} elseif ($type == 'lengkap') {

    $cols1 = ['NO', 'NAMA ITEM', 'KATEGORI', 'STOK', 'SATUAN', 'SUPPLIER', 'TANGGAL MASUK', 'LOKASI'];
    $sql1 = "SELECT id_persediaan, nama_item, kategori, jumlah, satuan, supplier, tanggal_masuk, lokasi_penyimpanan FROM data_persediaan ORDER BY tanggal_masuk DESC";
    generateTable($conn, $sql1, $cols1, "Persediaan");

    $cols2 = ['NO', 'TANGGAL', 'KANDANG', 'TOTAL BUTIR', 'BERAT (KG)', 'KUALITAS', 'PETUGAS'];
    $sql2 = "SELECT id_data_telur, tanggal, nomor_kandang, jumlah_total_telur, berat_total_kg, kualitas, petugas FROM data_telur ORDER BY tanggal DESC";
    generateTable($conn, $sql2, $cols2, "Produksi Telur");

    $cols3 = ['NO', 'TANGGAL', 'KANDANG', 'AYAM (EKOR)', 'SUHU', 'LEMBAB', 'KESEHATAN', 'PENYAKIT'];
    $sql3 = "SELECT id_laporan_kandang, tanggal, nomor_kandang, jumlah_ayam, suhu, kelembaban, kondisi_kesehatan, penyakit FROM laporan_kandang ORDER BY tanggal DESC";
    generateTable($conn, $sql3, $cols3, "Kondisi Kandang");

    $cols4 = ['NO', 'TANGGAL', 'KANDANG', 'PAKAN', 'WAKTU', 'JUMLAH', 'SATUAN', 'PER EKOR (GR)'];
    $sql4 = "SELECT id_kebutuhan, tanggal, nomor_kandang, jenis_pakan, waktu_pemberian, jumlah_kebutuhan, satuan, konsumsi_per_ekor FROM kebutuhan_pakan ORDER BY tanggal DESC";
    generateTable($conn, $sql4, $cols4, "Kebutuhan Pakan");
}

exit;
?>
