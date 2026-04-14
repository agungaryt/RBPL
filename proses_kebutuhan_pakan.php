<?php
session_start();
include 'koneksi.php';

if (isset($_POST['submit_pakan'])) {
    $tanggal            = $_POST['tanggal'];
    $nomor_kandang      = mysqli_real_escape_string($conn, $_POST['nomor_kandang']);
    $jenis_pakan        = mysqli_real_escape_string($conn, $_POST['jenis_pakan']);
    $waktu_pemberian    = mysqli_real_escape_string($conn, $_POST['waktu_pemberian']);
    $jumlah_kebutuhan   = $_POST['jumlah_kebutuhan'];
    $satuan             = mysqli_real_escape_string($conn, $_POST['satuan']);
    $jumlah_ternak      = $_POST['jumlah_ternak'];
    $konsumsi_per_ekor  = $_POST['konsumsi_per_ekor'];
    $catatan            = mysqli_real_escape_string($conn, $_POST['catatan']);
    $id_user            = $_SESSION['user_id'];

    $query = "INSERT INTO kebutuhan_pakan (tanggal, nomor_kandang, jenis_pakan, waktu_pemberian, jumlah_kebutuhan, satuan, jumlah_ternak, konsumsi_per_ekor, catatan, id_user) 
              VALUES ('$tanggal', '$nomor_kandang', '$jenis_pakan', '$waktu_pemberian', '$jumlah_kebutuhan', '$satuan', '$jumlah_ternak', '$konsumsi_per_ekor', '$catatan', '$id_user')";

    if (mysqli_query($conn, $query)) {
        header("Location: kebutuhan_pakan.php?status=sukses");
    } else {
        header("Location: kebutuhan_pakan.php?status=gagal");
    }
    exit;
}