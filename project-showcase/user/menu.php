<?php

$menu = isset($_GET['menu'])
    ? $_GET['menu']
    : 'dashboard';

switch($menu){

    case 'dashboard':
        include "home.php";
        break;

    case 'my_projects':
        include "my_projects.php";
        break;

    case 'trending':
        include "trending.php";
        break;

    case 'new_project':
        include "new_project.php";
        break;

    default:
        include "home.php";
        break;
}
?>