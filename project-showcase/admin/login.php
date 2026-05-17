<?php
session_start();

if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Infoshowcase</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="css/login-style.css">



</head>

<body>

    <!-- LOGIN SECTION -->
    <section class="login-section">

        <div class="login-box">

            <h2>Welcome Back</h2>
            <p>Login to continue</p>

            <form method="POST" action="proses_login.php">

                <!-- EMAIL -->
                <div class="input-group">
                    <input type="text" name="username" required>
                    <label>Username</label>
                </div>

                <!-- PASSWORD -->
                <div class="input-group">
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>

                <!-- CAPTCHA -->
                <div class="captcha-group">

                    <label>Captcha</label>

                    <div class="captcha-box justify-content-center">
                        <img src="captcha.php" id="captcha-img" onclick="refreshCaptcha()" class="captcha-img">
                    </div>

                    <input type="text" name="captcha" placeholder="Masukkan captcha" required>

                </div>

                <!-- BUTTON -->
                <button type="submit" class="btn-login-main">
                    Login
                </button>

            </form>

        </div>

    </section>

    <script>
        function refreshCaptcha() {
            document.getElementById('captcha-img').src = 'captcha.php?' + Date.now();
        }
    </script>
</body>

</html>