<?php
session_start();
include 'koneksi.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $id_role = $_POST['id_role'];
    $captcha_input = $_POST['captcha_input'];
    if ($captcha_input != $_SESSION['captcha_hasil']) {
        header("Location: register.php?pesan=captcha_salah");
        exit;
    }

    $cek_user = mysqli_query($conn, "SELECT * FROM pengguna WHERE username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        header("Location: register.php?pesan=username_ada");
        exit;
    }

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO pengguna (username, password, id_role) VALUES ('$username', '$password_hashed', '$id_role')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: login.php?pesan=berhasil_daftar");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
