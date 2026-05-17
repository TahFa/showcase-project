<?php
session_destroy();
?>

<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <script>
        Swal.fire({
            icon: 'success',
            title: 'Logout Berhasil!',
            text: 'Sampai jumpa',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            window.location.href = 'login.php';
        });
    </script>

</body>

</html>