<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT pengguna.*, roles.nama_role 
            FROM pengguna 
            LEFT JOIN roles ON pengguna.id_role = roles.id_role 
            WHERE pengguna.username = '$username'";
            
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['status_login'] = true;
            $_SESSION['user_id'] = $row['id_user'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['nama_role']; 
            
            header("Location: dashboard.php");
            exit;
        }
    }
    header("Location: login.php?pesan=gagal");
}
?>
