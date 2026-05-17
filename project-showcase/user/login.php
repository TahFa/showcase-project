<?php
session_start();

if (isset($_SESSION['role']) && $_SESSION['role'] == 'user') {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta 
        name="viewport" 
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login - Infoshowcase</title>


    <!-- BOOTSTRAP -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >


    <!-- ICON -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" 
        rel="stylesheet"
    >


    <!-- STYLE -->
    <link 
        rel="stylesheet" 
        href="css/style-login.css"
    >


    <style>

        body{
            margin:0;
            padding:0;

            min-height:100vh;

            background:
            linear-gradient(135deg,#4f46e5,#3b82f6);

            overflow:hidden;

            font-family:'Poppins',sans-serif;
        }

        .login-section{

            min-height:100vh;

            display:flex;
            align-items:center;
            justify-content:center;

            padding:20px;

            position:relative;
        }

        .login-section::before{

            content:'';

            position:absolute;

            width:450px;
            height:450px;

            background:
            rgba(255,255,255,.08);

            border-radius:50%;

            top:-120px;
            left:-120px;
        }

        .login-section::after{

            content:'';

            position:absolute;

            width:350px;
            height:350px;

            background:
            rgba(255,255,255,.06);

            border-radius:50%;

            bottom:-100px;
            right:-100px;
        }

        .login-box{

            width:100%;
            max-width:430px;

            background:
            rgba(255,255,255,.15);

            backdrop-filter:blur(18px);

            border:1px solid rgba(255,255,255,.2);

            border-radius:30px;

            padding:45px 35px;

            box-shadow:
            0 20px 45px rgba(0,0,0,.15);

            position:relative;
            z-index:2;

            animation:fadeUp .7s ease;
        }

        @keyframes fadeUp{

            from{
                opacity:0;
                transform:translateY(30px);
            }

            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        .login-box h2{

            color:white;

            font-weight:800;

            text-align:center;

            margin-bottom:8px;
        }

        .login-box p{

            text-align:center;

            color:rgba(255,255,255,.85);

            margin-bottom:35px;
        }

        .input-group{

            position:relative;

            margin-bottom:28px;
        }

        .input-group input{

            width:100%;

            border:none;

            outline:none;

            background:
            rgba(255,255,255,.15);

            border:1px solid rgba(255,255,255,.2);

            border-radius:16px;

            padding:16px 18px;

            color:white;

            font-size:15px;
        }

        .input-group input:focus{

            border-color:#fff;

            box-shadow:
            0 0 0 4px rgba(255,255,255,.15);
        }

        .input-group label{

            position:absolute;

            top:50%;
            left:18px;

            transform:translateY(-50%);

            color:rgba(255,255,255,.7);

            pointer-events:none;

            transition:.25s;
        }

        .input-group input:focus + label,
        .input-group input:valid + label{

            top:-10px;

            left:14px;

            background:#4f46e5;

            padding:2px 10px;

            border-radius:20px;

            font-size:12px;

            color:white;
        }

        .captcha-group{

            margin-bottom:28px;
        }

        .captcha-group label{

            display:block;

            margin-bottom:10px;

            color:white;

            font-weight:500;
        }

        .captcha-box{

            background:
            rgba(255,255,255,.12);

            border-radius:18px;

            padding:12px;

            margin-bottom:15px;

            display:flex;
            align-items:center;
        }

        .captcha-img{

            border-radius:12px;

            cursor:pointer;

            transition:.3s;
        }

        .captcha-img:hover{

            transform:scale(1.03);
        }

        .captcha-group input{

            width:100%;

            border:none;

            outline:none;

            background:
            rgba(255,255,255,.15);

            border:1px solid rgba(255,255,255,.2);

            border-radius:16px;

            padding:15px 18px;

            color:white;
        }

        .captcha-group input::placeholder{

            color:rgba(255,255,255,.65);
        }

        .btn-login-main{

            width:100%;

            border:none;

            border-radius:16px;

            padding:15px;

            background:white;

            color:#4f46e5;

            font-weight:700;

            transition:.3s;
        }

        .btn-login-main:hover{

            transform:translateY(-2px);

            box-shadow:
            0 12px 24px rgba(255,255,255,.18);
        }

        .extra{

            margin-top:22px;

            text-align:center;

            color:white;
        }

        .extra a{

            color:white;

            text-decoration:none;

            font-weight:500;

            transition:.3s;
        }

        .extra a:hover{

            opacity:.8;
        }

        @media(max-width:576px){

            .login-box{

                padding:35px 25px;
            }

            .login-box h2{

                font-size:28px;
            }
        }

    </style>

</head>

<body>

    <!-- LOGIN SECTION -->
    <section class="login-section">

        <div class="login-box">

            <h2>
                Welcome Back
            </h2>

            <p>
                Login to continue
            </p>

            <form method="POST" action="proses_login.php">

                <!-- USERNAME -->
                <div class="input-group">

                    <input 
                        type="text" 
                        name="username" 
                        required
                    >

                    <label>
                        Username
                    </label>

                </div>


                <!-- PASSWORD -->
                <div class="input-group">

                    <input 
                        type="password" 
                        name="password" 
                        required
                    >

                    <label>
                        Password
                    </label>

                </div>


                <!-- CAPTCHA -->
                <div class="captcha-group">

                    <label>
                        Captcha
                    </label>

                    <div class="captcha-box justify-content-center">

                        <img 
                            src="captcha.php" 
                            id="captcha-img" 
                            onclick="refreshCaptcha()" 
                            class="captcha-img"
                        >

                    </div>

                    <input 
                        type="text" 
                        name="captcha" 
                        placeholder="Masukkan captcha" 
                        required
                    >

                </div>


                <!-- BUTTON -->
                <button 
                    type="submit" 
                    class="btn-login-main"
                >

                    <i class="bi bi-box-arrow-in-right"></i>
                    Login

                </button>


                <div class="extra">

                    <a href="../index.php">
                        Kembali
                    </a>

                    <span> | </span>

                    <a href="register.php">
                        Register
                    </a>

                </div>

            </form>

        </div>

    </section>


    <script>

        function refreshCaptcha() {

            document.getElementById('captcha-img').src =
            'captcha.php?' + Date.now();

        }

    </script>

</body>
</html>