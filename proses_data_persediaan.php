<?php
session_start();
include 'koneksi.php';

if (isset($_POST['submit_persediaan'])) {
    $nama_item    = mysqli_real_escape_string($conn, $_POST['nama_item']);
    $kategori     = mysqli_real_escape_string($conn, $_POST['kategori']);
    $jumlah       = $_POST['jumlah'];
    $satuan       = mysqli_real_escape_string($conn, $_POST['satuan']);
    $tanggal      = $_POST['tanggal_masuk'];
    $supplier     = mysqli_real_escape_string($conn, $_POST['supplier']);
    $harga        = $_POST['harga_satuan'];
    $lokasi       = mysqli_real_escape_string($conn, $_POST['lokasi_penyimpanan']);
    $id_user      = $_SESSION['user_id'];

    $query = "INSERT INTO data_persediaan (nama_item, kategori, jumlah, satuan, tanggal_masuk, supplier, harga_satuan, lokasi_penyimpanan, id_user) 
              VALUES ('$nama_item', '$kategori', '$jumlah', '$satuan', '$tanggal', '$supplier', '$harga', '$lokasi', '$id_user')";

    if (mysqli_query($conn, $query)) {
        header("Location: data_persediaan.php?status=sukses");
    } else {
        header("Location: data_persediaan.php?status=gagal");
    }
    exit;
}