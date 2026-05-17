<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: my_projects.php");
    exit;
}

$id = (int) $_GET['id'];


// GET PROJECT IMAGE
$stmt = $conn->prepare("
    SELECT image
    FROM projects
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$data   = $result->fetch_assoc();

$image = $data['image'] ?? '';

$stmt->close();


// DELETE IMAGE FILE
if (!empty($image)) {

    $imagePath =
        __DIR__ . '/uploads/thumbnails/' . $image;

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}


// DELETE PROJECT
$stmtDelete = $conn->prepare("
    DELETE FROM projects
    WHERE id = ?
");

$stmtDelete->bind_param("i", $id);
$stmtDelete->execute();

$stmtDelete->close();

header("Location: my_projects.php");
exit;