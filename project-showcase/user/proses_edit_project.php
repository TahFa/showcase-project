<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: dashboard.php?menu=my_projects");
    exit;
}

$id          = (int) $_POST['id'];
$title       = trim($_POST['title']);
$description = trim($_POST['description']);
$tech_stack  = trim($_POST['tech_stack']);
$demo_link   = trim($_POST['demo_link']);


// =======================
// GET OLD DATA
// =======================

$stmtOld = $conn->prepare("
    SELECT image, zip_file
    FROM projects
    WHERE id = ?
");

$stmtOld->bind_param("i", $id);
$stmtOld->execute();

$oldResult = $stmtOld->get_result();
$oldData   = $oldResult->fetch_assoc();

$oldImage = $oldData['image'] ?? '';
$oldZip   = $oldData['zip_file'] ?? '';

$stmtOld->close();

$imageName = $oldImage;
$zipName   = $oldZip;


// =======================
// UPLOAD IMAGE
// =======================

$thumbnailDir = __DIR__ . '/uploads/thumbnails/';

if (!is_dir($thumbnailDir)) {
    mkdir($thumbnailDir, 0777, true);
}

if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] == 0
) {

    $image    = $_FILES['image']['name'];
    $tmpImage = $_FILES['image']['tmp_name'];

    $imageExt = strtolower(
        pathinfo($image, PATHINFO_EXTENSION)
    );

    $allowedImage = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($imageExt, $allowedImage)) {

        // hapus gambar lama
        if (
            !empty($oldImage) &&
            file_exists($thumbnailDir . $oldImage)
        ) {
            unlink($thumbnailDir . $oldImage);
        }

        $imageName =
            time() . '_img_' . uniqid() . '.' . $imageExt;

        move_uploaded_file(
            $tmpImage,
            $thumbnailDir . $imageName
        );
    }
}


// =======================
// UPLOAD ZIP FILE
// =======================

$zipDir = __DIR__ . '/uploads/zips/';

if (!is_dir($zipDir)) {
    mkdir($zipDir, 0777, true);
}

if (
    isset($_FILES['zip_file']) &&
    $_FILES['zip_file']['error'] == 0
) {

    $zip      = $_FILES['zip_file']['name'];
    $tmpZip   = $_FILES['zip_file']['tmp_name'];

    $zipExt = strtolower(
        pathinfo($zip, PATHINFO_EXTENSION)
    );

    if ($zipExt == 'zip') {

        // hapus zip lama
        if (
            !empty($oldZip) &&
            file_exists($zipDir . $oldZip)
        ) {
            unlink($zipDir . $oldZip);
        }

        $zipName =
            time() . '_zip_' . uniqid() . '.zip';

        move_uploaded_file(
            $tmpZip,
            $zipDir . $zipName
        );
    }
}


// =======================
// UPDATE PROJECT
// =======================

$stmt = $conn->prepare("
    UPDATE projects
    SET
        title = ?,
        description = ?,
        tech_stack = ?,
        demo_link = ?,
        image = ?,
        zip_file = ?
    WHERE id = ?
");

$stmt->bind_param(
    "ssssssi",
    $title,
    $description,
    $tech_stack,
    $demo_link,
    $imageName,
    $zipName,
    $id
);

$stmt->execute();
$stmt->close();

header("Location: dashboard.php?menu=my_projects");
exit;
