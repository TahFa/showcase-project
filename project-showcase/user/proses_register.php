<?php
include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];
$confirm  = $_POST['confirm_password'];

// ❌ VALIDASI PASSWORD
if ($password != $confirm) {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Password tidak sama'
    }).then(() => {
        window.location.href = 'register.php';
    });
    </script>
    </body>
    </html>
    ";
    exit;
}

// 🔍 CEK USERNAME SUDAH ADA
$cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

if (mysqli_num_rows($cek) > 0) {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Username sudah digunakan'
    }).then(() => {
        window.location.href = 'register.php';
    });
    </script>
    </body>
    </html>
    ";
    exit;
}

// 🔐 HASH PASSWORD
$hash = password_hash($password, PASSWORD_DEFAULT);

// 💾 SIMPAN KE DATABASE
$query = mysqli_query($conn, "INSERT INTO users (username, password, role) 
                             VALUES ('$username', '$hash', 'user')");

if ($query) {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Akun berhasil dibuat 🎉'
    }).then(() => {
        window.location.href = 'login.php';
    });
    </script>
    </body>
    </html>
    ";
} else {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: 'Terjadi kesalahan'
    }).then(() => {
        window.location.href = 'register.php';
    });
    </script>
    </body>
    </html>
    ";
}
