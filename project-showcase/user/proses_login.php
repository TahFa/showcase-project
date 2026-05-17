<?php
session_start();

if (isset($_SESSION['role']) && $_SESSION['role'] == 'user') {
    header("Location: dashboard.php");
    exit;
}

include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];
$captcha  = $_POST['captcha'];


// =======================
// VALIDASI CAPTCHA USER
// =======================

if ($captcha != $_SESSION['captcha_user']) {

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
        text: 'Silakan masukkan captcha dengan benar'
    }).then(() => {

        window.location.href = 'login.php';

    });

    </script>

    </body>
    </html>
    ";

    exit;
}



// =======================
// LOGIN USER
// =======================

$stmt = $conn->prepare("
    SELECT * 
    FROM users 
    WHERE username=?
");

$stmt->bind_param("s", $username);

$stmt->execute();

$result = $stmt->get_result();

$data = $result->fetch_assoc();



// =======================
// CEK USER
// =======================

if (
    $data &&
    $data['role'] == 'user' &&
    password_verify($password, $data['password'])
) {


    // =======================
    // CEK APAKAH USER DIBAN
    // =======================

    if (
        !empty($data['banned_until']) &&
        strtotime($data['banned_until']) > time()
    ) {

        $tanggal_ban = date(
            'd M Y H:i',
            strtotime($data['banned_until'])
        );

        $alasan_ban = !empty($data['ban_reason'])
            ? $data['ban_reason']
            : 'Tidak ada alasan';

        echo "
        <!DOCTYPE html>
        <html>
        <head>

            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

        </head>

        <body>

        <script>

        Swal.fire({
            icon: 'warning',
            title: 'Akun Dibanned!',
            html: `<b>Sampai:</b> $tanggal_ban <br><br>
                   <b>Alasan:</b><br>
                   $alasan_ban`
        }).then(() => {

            window.location.href = 'login.php';

        });

        </script>

        </body>
        </html>
        ";

        exit;
    }



    // =======================
    // SESSION LOGIN
    // =======================

    $_SESSION['id'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];



    // =======================
    // LOGIN BERHASIL
    // =======================

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
        title: 'Login Berhasil!',
        text: 'Selamat datang 👋',
        timer: 1500,
        showConfirmButton: false
    }).then(() => {

        window.location.href = 'dashboard.php';

    });

    </script>

    </body>
    </html>
    ";

} else {

    // =======================
    // LOGIN GAGAL
    // =======================

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