<?php
session_start();

if (!isset($_SESSION['id'])) {
    exit;
}

require_once 'koneksi.php';

$user_id    = $_SESSION['id'];
$project_id = (int) $_GET['id'];


// GET PROJECT
$stmt = $conn->prepare("
    SELECT zip_file, download_count
    FROM projects
    WHERE id = ?
");

$stmt->bind_param("i", $project_id);
$stmt->execute();

$result  = $stmt->get_result();
$project = $result->fetch_assoc();

$stmt->close();

if (!$project) {
    die('Project tidak ditemukan');
}

$file = trim($project['zip_file']);

if (empty($file)) {
    die('File kosong');
}


// PATH FILE
$filePath =
    __DIR__ . '/uploads/projects/' . $file;


// CEK FILE
if (!file_exists($filePath)) {

    die('File tidak ditemukan');
}


// CHECK DOWNLOAD HISTORY
$check = $conn->prepare("
    SELECT id
    FROM downloads
    WHERE user_id = ?
    AND project_id = ?
");

$check->bind_param(
    "ii",
    $user_id,
    $project_id
);

$check->execute();

$alreadyDownloaded =
    $check->get_result()->num_rows > 0;

$check->close();


// FIRST DOWNLOAD ONLY
if (!$alreadyDownloaded) {

    // INSERT HISTORY
    $insert = $conn->prepare("
        INSERT INTO downloads (
            user_id,
            project_id
        )
        VALUES (?, ?)
    ");

    $insert->bind_param(
        "ii",
        $user_id,
        $project_id
    );

    if (!$insert->execute()) {
        die($insert->error);
    }

    $insert->close();


    // UPDATE DOWNLOAD COUNT
    $sqlUpdate = "
    UPDATE projects
    SET download_count = COALESCE(download_count, 0) + 1
    WHERE id = $project_id";

    if (!mysqli_query($conn, $sqlUpdate)) {
        die(mysqli_error($conn));
    }
}


// CLEAR BUFFER
while (ob_get_level()) {
    ob_end_clean();
}


// CLOSE DATABASE
mysqli_close($conn);


// FORCE DOWNLOAD
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');

header(
    'Content-Disposition: attachment; filename="' .
        basename($filePath) . '"'
);

header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));

flush();

readfile($filePath);
exit;
