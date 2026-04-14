<?php
session_start();
include 'koneksi.php';

if (isset($_POST['submit_nota'])) {
    // 1. Ambil data dari form
    $nomor_nota = mysqli_real_escape_string($conn, $_POST['nomor_nota']);
    $tanggal    = $_POST['tanggal'];
    $supplier   = mysqli_real_escape_string($conn, $_POST['supplier']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $id_user    = $_SESSION['user_id'];

    // 2. Cek apakah ada error pada pengiriman file dari browser ke server
    if ($_FILES['file_nota']['error'] !== UPLOAD_ERR_OK) {
        header("Location: upload_nota.php?status=gagal_upload_error_" . $_FILES['file_nota']['error']);
        exit; // Wajib menggunakan exit setelah header
    }

    // 3. Persiapan variabel file
    $filename = $_FILES['file_nota']['name'];
    $tmp_name = $_FILES['file_nota']['tmp_name'];
    $ekstensi = pathinfo($filename, PATHINFO_EXTENSION);
    
    // Gunakan nama unik (time + uniqid) agar file dengan nama sama tidak saling tindih
    $new_filename = "nota_" . time() . "_" . uniqid() . "." . $ekstensi;
    $folder_tujuan = "uploads/";

    // 4. Pastikan folder uploads tersedia
    if (!is_dir($folder_tujuan)) {
        mkdir($folder_tujuan, 0777, true);
    }

    // 5. Proses Pindahkan File ke Folder 'uploads'
    if (move_uploaded_file($tmp_name, $folder_tujuan . $new_filename)) {
        
        // 6. Jika file berhasil dipindah, baru simpan datanya ke Database
        $query = "INSERT INTO notaPembelian (nomor_nota, tanggal, supplier, file_nota, keterangan, id_user) 
                  VALUES ('$nomor_nota', '$tanggal', '$supplier', '$new_filename', '$keterangan', '$id_user')";
        
        if (mysqli_query($conn, $query)) {
            header("Location: upload_nota.php?status=sukses");
            exit;
        } else {
            // Jika database gagal, hapus file yang sudah terlanjur diupload agar folder tidak penuh sampah
            unlink($folder_tujuan . $new_filename);
            header("Location: upload_nota.php?status=gagal_db");
            exit;
        }
    } else {
        header("Location: upload_nota.php?status=gagal_move_file");
        exit;
    }
} else {
    // Jika mencoba akses langsung tanpa submit form
    header("Location: upload_nota.php");
    exit;
}
?>