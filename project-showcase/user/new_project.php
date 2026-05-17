<?php
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_SESSION['id'];

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = $_POST['category_id'];
    $tech_stack = mysqli_real_escape_string($conn, $_POST['tech_stack']);
    $demo_link = mysqli_real_escape_string($conn, $_POST['demo_link']);

    // CREATE FOLDER IF NOT EXIST
    $thumbnailDir = __DIR__ . '/uploads/thumbnails/';
    $projectDir   = __DIR__ . '/uploads/projects/';

    if (!is_dir($thumbnailDir)) {
        mkdir($thumbnailDir, 0777, true);
    }

    if (!is_dir($projectDir)) {
        mkdir($projectDir, 0777, true);
    }

    // UPLOAD IMAGE
    $imageName = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

        $image      = $_FILES['image']['name'];
        $tmpImage   = $_FILES['image']['tmp_name'];

        $imageExt = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        $allowedImage = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($imageExt, $allowedImage)) {

            $imageName = time() . '_img_' . uniqid() . '.' . $imageExt;

            move_uploaded_file(
                $tmpImage,
                $thumbnailDir . $imageName
            );
        }
    }

    // UPLOAD ZIP FILE
    $zipName = '';

    if (isset($_FILES['zip_file']) && $_FILES['zip_file']['error'] == 0) {

        $zip      = $_FILES['zip_file']['name'];
        $tmpZip   = $_FILES['zip_file']['tmp_name'];

        $zipExt = strtolower(pathinfo($zip, PATHINFO_EXTENSION));

        if ($zipExt == 'zip') {

            $zipName = time() . '_zip_' . uniqid() . '.zip';

            move_uploaded_file(
                $tmpZip,
                $projectDir . $zipName
            );
        }
    }

    // INSERT PROJECT
    $query = mysqli_query($conn, "
        INSERT INTO projects
        (
            user_id,
            category_id,
            title,
            description,
            tech_stack,
            image,
            zip_file,
            demo_link
        )
        VALUES
        (
            '$user_id',
            '$category_id',
            '$title',
            '$description',
            '$tech_stack',
            '$imageName',
            '$zipName',
            '$demo_link'
        )
    ");

    if ($query) {

        header("Location: index.php?menu=dashboard");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>New Project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="css/style-dashboard.css">

    <style>
        .form-wrapper {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 35px;
            border-radius: 24px;
            border: 1px solid #e5e7eb;
        }

        .form-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 14px;
            padding: 14px;
            border: 1px solid #d1d5db;
        }

        textarea {
            resize: none;
        }

        .submit-btn {
            border: none;
            background: #2563eb;
            color: white;
            padding: 14px 24px;
            border-radius: 14px;
            font-weight: 600;
        }

        .submit-btn:hover {
            background: #1d4ed8;
        }

        .back-btn {
            border: none;
            background: #adb5bd;
            color: white;
            padding: 14px 24px;
            border-radius: 14px;
            font-weight: 600;
        }

        .back-btn:hover {
            background: #6c757d;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="form-wrapper">

            <h2 class="form-title">
                Create New Project
            </h2>

            <form method="POST" enctype="multipart/form-data">

                <div class="row">

                    <!-- TITLE -->
                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Project Title
                        </label>

                        <input type="text"
                            name="title"
                            class="form-control"
                            required>

                    </div>

                    <!-- CATEGORY -->
                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Category
                        </label>

                        <select
                            name="category_id"
                            class="form-select"
                            required>

                            <option value="">
                                Choose Category
                            </option>

                            <?php
                            $categories = mysqli_query($conn, "
                    SELECT * FROM categories
                ");

                            while ($category = mysqli_fetch_assoc($categories)) :
                            ?>

                                <option value="<?= $category['id']; ?>">
                                    <?= $category['category_name']; ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>

                </div>


                <!-- DESCRIPTION -->
                <div class="mb-4">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="form-control"
                        required></textarea>

                </div>


                <div class="row">

                    <!-- TECH STACK -->
                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Tech Stack
                        </label>

                        <input type="text"
                            name="tech_stack"
                            class="form-control"
                            placeholder="Laravel, Bootstrap, Flutter"
                            required>

                    </div>

                    <!-- DEMO LINK -->
                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Demo Link
                        </label>

                        <input type="url"
                            name="demo_link"
                            class="form-control"
                            placeholder="https://example.com">

                    </div>

                </div>


                <div class="row">

                    <!-- IMAGE -->
                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Thumbnail Project
                        </label>

                        <input type="file"
                            name="image"
                            class="form-control"
                            accept="image/*"
                            required>

                    </div>

                    <!-- ZIP -->
                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Upload ZIP Project
                        </label>

                        <input type="file"
                            name="zip_file"
                            class="form-control"
                            accept=".zip"
                            required>

                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php?menu=dashboard" class="btn back-btn">Kembali</a>
                    <button type="submit" class="btn submit-btn">Publish Project</button>
                </div>

            </form>

        </div>

    </div>

</body>

</html>