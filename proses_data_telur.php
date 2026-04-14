<?php
session_start();
include 'koneksi.php';

if (isset($_POST['submit_telur'])) {
    $tanggal            = $_POST['tanggal'];
    $nomor_kandang      = mysqli_real_escape_string($conn, $_POST['nomor_kandang']);
    $jumlah_total_telur = $_POST['jumlah_total_telur'];
    $telur_baik         = $_POST['telur_baik'];
    $telur_retak        = $_POST['telur_retak'];
    $telur_kotor        = $_POST['telur_kotor'];
    $berat_total_kg     = $_POST['berat_total_kg'];
    $kualitas           = mysqli_real_escape_string($conn, $_POST['kualitas']);
    $petugas            = mysqli_real_escape_string($conn, $_POST['petugas']);
    $id_user            = $_SESSION['user_id'];

    $query = "INSERT INTO data_telur (tanggal, nomor_kandang, jumlah_total_telur, telur_baik, telur_retak, telur_kotor, berat_total_kg, kualitas, petugas, id_user) 
              VALUES ('$tanggal', '$nomor_kandang', '$jumlah_total_telur', '$telur_baik', '$telur_retak', '$telur_kotor', '$berat_total_kg', '$kualitas', '$petugas', '$id_user')";

    if (mysqli_query($conn, $query)) {
        header("Location: data_telur.php?status=sukses");
    } else {
        header("Location: data_telur.php?status=gagal");
    }
    exit;
}