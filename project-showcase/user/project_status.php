<?php
session_start();
require_once 'koneksi.php';

if (!isset($_GET['id']) || !isset($_GET['status'])) {
    header("Location: my_projects.php");
    exit;
}

$id = (int) $_GET['id'];
$status = $_GET['status'];

$allowed = ['published', 'private'];

if (!in_array($status, $allowed)) {
    header("Location: dashboard.php?menu=my_projects");
    exit;
}

$stmt = $conn->prepare("
    UPDATE projects
    SET status = ?
    WHERE id = ?
");

$stmt->bind_param("si", $status, $id);
$stmt->execute();

header("Location: dashboard.php?menu=my_projects");
exit;