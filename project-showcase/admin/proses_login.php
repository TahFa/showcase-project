<?php
session_start();

// disable cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'){
    header("Location: index.php");
    exit;
}

include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];
$captcha  = $_POST['captcha'];

// VALIDASI CAPTCHA ADMIN
if ($captcha != $_SESSION['captcha_admin']) {
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
        title: 'Captcha Salah!',
        text: 'Akses admin membutuhkan verifikasi captcha!'
    }).then(() => {
        window.location.href = 'login.php';
    });
    </script>
    </body>
    </html>
    ";
    exit;
}

// LOGIN ADMIN
$stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data && $data['role'] == 'admin' && password_verify($password, $data['password'])) {

    $_SESSION['id'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];

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
        title: 'Welcome Admin 👑',
        timer: 1500,
        showConfirmButton: false
    }).then(() => {
        window.location.href = 'index.php';
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
        title: 'Login Gagal!',
        text: 'Username atau password salah'
    }).then(() => {
        window.location.href = 'login.php';
    });
    </script>
    </body>
    </html>
    ";
}
?>