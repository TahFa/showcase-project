<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['id'])) {

    echo json_encode([
        'success' => false
    ]);

    exit;
}

$user_id    = $_SESSION['id'];
$project_id = (int) $_POST['project_id'];


// CHECK LIKE
$check = mysqli_query($conn, "

    SELECT *
    FROM likes

    WHERE user_id = '$user_id'
    AND project_id = '$project_id'

");


// UNLIKE
if (mysqli_num_rows($check) > 0) {

    mysqli_query($conn, "

        DELETE FROM likes

        WHERE user_id = '$user_id'
        AND project_id = '$project_id'

    ");

    mysqli_query($conn, "

        UPDATE projects

        SET likes_count = likes_count - 1

        WHERE id = '$project_id'

    ");

    $liked = false;

} else {


// LIKE
    mysqli_query($conn, "

        INSERT INTO likes(
            user_id,
            project_id
        )

        VALUES(
            '$user_id',
            '$project_id'
        )

    ");

    mysqli_query($conn, "

        UPDATE projects

        SET likes_count = likes_count + 1

        WHERE id = '$project_id'

    ");

    $liked = true;
}


// GET TOTAL LIKE
$getLike = mysqli_query($conn, "

    SELECT likes_count
    FROM projects

    WHERE id = '$project_id'

");

$data = mysqli_fetch_assoc($getLike);

echo json_encode([

    'success' => true,
    'liked'  => $liked,
    'total'  => $data['likes_count']

]);