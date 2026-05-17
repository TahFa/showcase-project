<?php
$menu = isset($_GET['menu']) ? $_GET['menu'] : 'dashboard';

switch ($menu) {
    case 'dashboard':
        include "dashboard.php";
        break;

    case 'user':
        include "manage-user.php";
        break;

    case 'project':
        include "manage-project.php";
        break;

    case 'category':
        include "manage-category.php";
        break;

    case 'logout':
        include "logout.php";
        break;

    default:
        echo "<h4>Halaman tidak ditemukan</h4>";
}
