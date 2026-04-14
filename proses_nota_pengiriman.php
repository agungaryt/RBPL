<?php
session_start();
include 'koneksi.php';

if (isset($_POST['submit_nota'])) {
    $nomor_nota         = mysqli_real_escape_string($conn, $_POST['nomor_nota']);
    $tanggal            = $_POST['tanggal_pengiriman'];
    $nama_penerima      = mysqli_real_escape_string($conn, $_POST['nama_penerima']);
    $telepon            = mysqli_real_escape_string($conn, $_POST['telepon']);
    $tujuan_kota        = mysqli_real_escape_string($conn, $_POST['tujuan_kota']);
    $alamat_lengkap     = mysqli_real_escape_string($conn, $_POST['alamat_lengkap']);
    $jenis_produk       = mysqli_real_escape_string($conn, $_POST['jenis_produk']);
    $jumlah_produk      = $_POST['jumlah_produk'];
    $satuan             = mysqli_real_escape_string($conn, $_POST['satuan']);
    $ongkos_kirim       = $_POST['ongkos_kirim'];
    $id_user            = $_SESSION['user_id'];

    $query = "INSERT INTO nota_pengiriman (nomor_nota, tanggal_pengiriman, nama_penerima, telepon, tujuan_kota, alamat_lengkap, jenis_produk, jumlah_produk, satuan, ongkos_kirim, id_user) 
              VALUES ('$nomor_nota', '$tanggal', '$nama_penerima', '$telepon', '$tujuan_kota', '$alamat_lengkap', '$jenis_produk', '$jumlah_produk', '$satuan', '$ongkos_kirim', '$id_user')";

    if (mysqli_query($conn, $query)) {
        header("Location: nota_pengiriman.php?status=sukses");
    } else {
        header("Location: nota_pengiriman.php?status=gagal");
    }
    exit;
}
