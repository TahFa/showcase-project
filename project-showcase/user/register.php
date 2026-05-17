<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style-register.css">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="register-box">
    <h2>Daftar Akun</h2>

    <form action="proses_register.php" method="POST">

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Konfirmasi Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-register">Daftar</button>

        <p class="mt-3 text-center">
            Sudah punya akun? <a href="login.php">Login</a>
        </p>
    </form>
</div>

</body>
</html>