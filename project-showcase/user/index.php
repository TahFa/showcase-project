<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User</title>
</head>
<body>

<h2>User Dashboard</h2>

<a href="?menu=dashboard">Dashboard</a> |
<a href="?menu=logout">Logout</a>

<hr>

<?php include "menu.php"; ?>

</body>
</html>