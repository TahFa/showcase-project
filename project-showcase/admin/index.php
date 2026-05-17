<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background:
                linear-gradient(to bottom right, #f8fafc, #eef2ff);

            font-family: 'Segoe UI', sans-serif;
            color: #0f172a;
        }

        /* SIDEBAR */

        .sidebar {

            width: 260px;
            height: 100vh;

            position: fixed;
            top: 0;
            left: 0;

            background:
                linear-gradient(180deg, #020617, #0f172a);

            padding: 25px 18px;

            overflow-y: auto;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, .2);

            z-index: 1000;
        }

        .sidebar h4 {

            color: #ffffff;

            margin-bottom: 25px;

            font-weight: 700;
            font-size: 28px;

            text-align: center;
        }

        .sidebar hr {

            border-color: rgba(255, 255, 255, .08);

            margin-bottom: 25px;
        }

        /* MENU TITLE */

        .menu-title {

            color: #64748b;

            font-size: 12px;

            margin-bottom: 15px;

            text-transform: uppercase;

            letter-spacing: 1px;

            padding-left: 10px;
        }

        /* SIDEBAR LINK */

        .sidebar a {

            display: flex;
            align-items: center;
            gap: 12px;

            color: #cbd5e1;

            padding: 14px 16px;

            border-radius: 14px;

            margin-bottom: 10px;

            text-decoration: none;

            transition: 0.3s ease;

            font-size: 15px;
            font-weight: 500;
        }

        .sidebar a:hover {

            background:
                linear-gradient(135deg, #3b82f6, #06b6d4);

            color: #ffffff;

            transform: translateX(5px);

            box-shadow:
                0 10px 20px rgba(59, 130, 246, .2);
        }

        .sidebar a.active {

            background:
                linear-gradient(135deg, #4f46e5, #3b82f6);

            color: white;

            box-shadow:
                0 10px 20px rgba(59, 130, 246, .25);
        }

        .sidebar a i {

            width: 20px;

            text-align: center;

            font-size: 15px;
        }

        /* CONTENT */

        .content {

            margin-left: 260px;

            padding: 30px;
        }

        /* CONTENT BOX */

        .content-box {

            background: white;

            border-radius: 24px;

            padding: 25px;

            min-height: 90vh;

            box-shadow:
                0 10px 35px rgba(0, 0, 0, .05);
        }

        /* LOGOUT */

        .logout-link {

            margin-top: 30px;
        }

        .logout-link:hover {

            background:
                linear-gradient(135deg, #ef4444, #f97316) !important;
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->

    <div class="sidebar">

        <h4>Admin Panel</h4>

        <hr>

        <div class="menu-title">
            MAIN MENU
        </div>

        <!-- DASHBOARD -->

        <a href="?menu=dashboard"
            class="<?php echo ($_GET['menu'] ?? 'dashboard') == 'dashboard' ? 'active' : ''; ?>">

            <i class="fa-solid fa-house"></i>
            Dashboard

        </a>

        <!-- USER -->

        <a href="?menu=user"
            class="<?php echo ($_GET['menu'] ?? '') == 'user' ? 'active' : ''; ?>">

            <i class="fa-solid fa-users"></i>
            Manajemen User

        </a>

        <!-- PROJECT -->

        <a href="?menu=project"
            class="<?php echo ($_GET['menu'] ?? '') == 'project' ? 'active' : ''; ?>">

            <i class="fa-solid fa-laptop-code"></i>
            Manajemen Project

        </a>

        <!-- CATEGORY -->

        <a href="?menu=category"
            class="<?php echo ($_GET['menu'] ?? '') == 'category' ? 'active' : ''; ?>">

            <i class="fa-solid fa-layer-group"></i>
            Manajemen Category

        </a>

        <!-- LOGOUT -->

        <a href="?menu=logout"
            class="logout-link">

            <i class="fa-solid fa-right-from-bracket"></i>
            Logout

        </a>

    </div>

    <!-- CONTENT -->

    <div class="content">

        <div class="content-box">

            <?php include "menu.php"; ?>

        </div>

    </div>

</body>

</html>