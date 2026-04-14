<?php
session_start();
include 'koneksi.php';

if (isset($_POST['submit_laporan'])) {
    $nomor_kandang      = mysqli_real_escape_string($conn, $_POST['nomor_kandang']);
    $tanggal            = $_POST['tanggal'];
    $kondisi_kebersihan = mysqli_real_escape_string($conn, $_POST['kondisi_kebersihan']);
    $jumlah_ayam        = $_POST['jumlah_ayam'];
    $suhu               = $_POST['suhu'];
    $kelembaban         = $_POST['kelembaban'];
    $kondisi_kesehatan  = mysqli_real_escape_string($conn, $_POST['kondisi_kesehatan']);
    $penyakit           = mysqli_real_escape_string($conn, $_POST['penyakit']);
    $tindakan_diambil   = mysqli_real_escape_string($conn, $_POST['tindakan_diambil']);
    $keterangan         = mysqli_real_escape_string($conn, $_POST['keterangan_tambahan']);
    $id_user            = $_SESSION['user_id'];

    $query = "INSERT INTO laporan_kandang (nomor_kandang, tanggal, kondisi_kebersihan, jumlah_ayam, suhu, kelembaban, kondisi_kesehatan, penyakit, tindakan_diambil, keterangan_tambahan, id_user) 
              VALUES ('$nomor_kandang', '$tanggal', '$kondisi_kebersihan', '$jumlah_ayam', '$suhu', '$kelembaban', '$kondisi_kesehatan', '$penyakit', '$tindakan_diambil', '$keterangan', '$id_user')";

    if (mysqli_query($conn, $query)) {
        header("Location: laporan_kandang.php?status=sukses");
    } else {
        header("Location: laporan_kandang.php?status=gagal");
    }
    exit;
}