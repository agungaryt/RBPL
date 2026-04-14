<?php
session_start();
include 'koneksi.php';

if (isset($_POST['submit_nota'])) {
    $nomor_nota = mysqli_real_escape_string($conn, $_POST['nomor_nota']);
    $tanggal    = $_POST['tanggal'];
    $supplier   = mysqli_real_escape_string($conn, $_POST['supplier']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $id_user    = $_SESSION['user_id'];

    if ($_FILES['file_nota']['error'] !== UPLOAD_ERR_OK) {
        header("Location: upload_nota.php?status=gagal_upload_error_" . $_FILES['file_nota']['error']);
        exit; 
    }

    $filename = $_FILES['file_nota']['name'];
    $tmp_name = $_FILES['file_nota']['tmp_name'];
    $ekstensi = pathinfo($filename, PATHINFO_EXTENSION);
    
    $new_filename = "nota_" . time() . "_" . uniqid() . "." . $ekstensi;
    $folder_tujuan = "uploads/";

    if (!is_dir($folder_tujuan)) {
        mkdir($folder_tujuan, 0777, true);
    }


    if (move_uploaded_file($tmp_name, $folder_tujuan . $new_filename)) {
        
        $query = "INSERT INTO notaPembelian (nomor_nota, tanggal, supplier, file_nota, keterangan, id_user) 
                  VALUES ('$nomor_nota', '$tanggal', '$supplier', '$new_filename', '$keterangan', '$id_user')";
        
        if (mysqli_query($conn, $query)) {
            header("Location: upload_nota.php?status=sukses");
            exit;
        } else {
        
            unlink($folder_tujuan . $new_filename);
            header("Location: upload_nota.php?status=gagal_db");
            exit;
        }
    } else {
        header("Location: upload_nota.php?status=gagal_move_file");
        exit;
    }
} else {
    header("Location: upload_nota.php");
    exit;
}
?>
