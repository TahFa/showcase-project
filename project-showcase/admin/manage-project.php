<?php
include 'koneksi.php';


// =======================
// HAPUS PROJECT
// =======================

if (isset($_GET['hapus'])) {

    $id = intval($_GET['hapus']);

    // ambil data project
    $cek = mysqli_query($conn, "
        SELECT *
        FROM projects
        WHERE id='$id'
    ");

    $project = mysqli_fetch_assoc($cek);


    // hapus gambar
    if (
        !empty($project['image']) &&
        file_exists("../user/uploads/thumbnails/" . $project['image'])
    ) {
        unlink("../user/uploads/thumbnails/" . $project['image']);
    }


    // hapus file zip
    if (
        !empty($project['zip_file']) &&
        file_exists("../user/uploads/projects/" . $project['zip_file'])
    ) {
        unlink("../user/uploads/projects/" . $project['zip_file']);
    }


    // hapus likes project
    mysqli_query($conn, "
        DELETE FROM likes
        WHERE project_id='$id'
    ");


    // hapus project
    mysqli_query($conn, "
        DELETE FROM projects
        WHERE id='$id'
    ");


    echo "
    <script>
        alert('Project berhasil dihapus');
        window.location='manage-project.php';
    </script>
    ";
}



// =======================
// QUERY PROJECT
// =======================

$query = mysqli_query($conn, "

    SELECT 
        projects.*,
        users.username,
        categories.category_name

    FROM projects

    JOIN users
    ON users.id = projects.user_id

    LEFT JOIN categories
    ON categories.id = projects.category_id

    ORDER BY projects.id DESC

");
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Management Project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: linear-gradient(to bottom right, #f8fafc, #eef2ff);
            font-family: 'Poppins', sans-serif;
        }

        /* HEADER */
        .page-header {

            background: linear-gradient(135deg, #4f46e5, #3b82f6, #06b6d4);
            color: white;

            padding: 35px;
            border-radius: 30px;

            position: relative;
            overflow: hidden;

            box-shadow: 0 20px 40px rgba(59, 130, 246, .15);

            margin-bottom: 30px;
        }

        .page-header::before {
            content: '';
            position: absolute;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
            top: -100px;
            right: -80px;
        }

        .page-header h3 {
            font-weight: 800;
            font-size: 34px;
        }

        .page-header p {
            margin: 0;
            opacity: .9;
        }


        /* TABLE BOX */
        .table-box {

            background: white;
            border-radius: 28px;

            padding: 25px;

            box-shadow: 0 10px 35px rgba(0, 0, 0, .05);
        }

        /* TABLE */
        table {
            border-collapse: separate;
            border-spacing: 0 14px;
        }

        table thead th {
            border: none !important;
            color: #64748b;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
        }

        table tbody tr {
            background: #f8fafc;
            transition: .25s ease;
        }

        table tbody tr:hover {
            background: #eef4ff;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, .08);
        }

        table tbody td {
            border: none !important;
            padding: 18px 14px !important;
            vertical-align: middle;
        }

        table tbody tr td:first-child {
            border-top-left-radius: 18px;
            border-bottom-left-radius: 18px;
        }

        table tbody tr td:last-child {
            border-top-right-radius: 18px;
            border-bottom-right-radius: 18px;
        }

        /* THUMBNAIL */
        .thumbnail {
            width: 90px;
            height: 65px;
            object-fit: cover;
            border-radius: 14px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, .08);
        }

        /* PROJECT TITLE */
        .project-title {
            font-weight: 700;
            color: #0f172a;
        }

        .project-desc {
            font-size: 13px;
            color: #64748b;
        }

        /* BADGE */
        .badge-modern {
            padding: 7px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-cat {
            background: #e0e7ff;
            color: #4f46e5;
        }

        /* ACTION */
        .action-box {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* BUTTON */
        .btn-modern {

            border: none;
            border-radius: 12px;

            padding: 8px 12px;

            font-size: 13px;
            font-weight: 600;

            display: flex;
            align-items: center;
            gap: 6px;

            transition: .25s ease;

            text-decoration: none;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
        }

        .btn-detail {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        /* MODAL */
        .modal-custom {
            border: none;
            border-radius: 24px;
        }

        .detail-image {
            width: 100%;
            height: 320px;
            object-fit: cover;
            border-radius: 18px;
        }

        .detail-title {
            font-weight: 800;
            color: #0f172a;
        }

        .detail-desc {
            color: #64748b;
            line-height: 1.7;
        }

        .info-label {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .info-value {
            font-weight: 600;
            color: #0f172a;
        }

        .demo-btn {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
        }

        .download-btn {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: white;
        }

        .detail-image {
            width: 100%;
            height: 100%;
            min-height: 320px;

            object-fit: cover;

            border-radius: 22px;

            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        .info-card {
            background: #f8fafc;

            border-radius: 18px;

            padding: 14px 16px;

            height: 100%;
        }

        .tech-stack-box {
            background: #f8fafc;

            border-radius: 18px;

            padding: 16px;

            color: #334155;

            line-height: 1.7;
        }

        .description-box {
            background: #f8fafc;

            border-radius: 22px;

            padding: 20px;
        }

        .detail-title {
            font-size: 30px;
            font-weight: 800;
            color: #0f172a;
        }

        .detail-desc {
            color: #475569;
            line-height: 1.8;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;

            color: #94a3b8;

            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 700;

            color: #0f172a;

            margin-top: 4px;
        }
    </style>

</head>

<body>

    <div class="container-fluid py-4 px-4">

        <!-- HEADER -->
        <div class="page-header">
            <h3>Management Project 📁</h3>
            <p>Kelola semua project yang dipublish user</p>
        </div>


        <!-- TABLE -->
        <div class="table-box">

            <div class="table-responsive">

                <table class="table align-middle w-100">

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Thumbnail</th>
                            <th>Project</th>
                            <th>User</th>
                            <th>Category</th>
                            <th>Like</th>
                            <th>Download</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php $no = 1;
                        while ($data = mysqli_fetch_assoc($query)) : ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td>
                                    <img src="../user/uploads/thumbnails/<?= htmlspecialchars($data['image']); ?>" class="thumbnail">
                                </td>

                                <td>
                                    <div class="project-title">
                                        <?= htmlspecialchars($data['title']); ?>
                                    </div>

                                    <div class="project-desc">
                                        <?= substr(htmlspecialchars($data['description']), 0, 70); ?>...
                                    </div>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['username']); ?>
                                </td>

                                <td>
                                    <?php if (!empty($data['category_name'])): ?>
                                        <span class="badge-modern badge-cat">
                                            <?= htmlspecialchars($data['category_name']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No Category</span>
                                    <?php endif; ?>
                                </td>

                                <td><?= $data['likes_count']; ?></td>
                                <td><?= $data['download_count']; ?></td>

                                <td>

                                    <div class="action-box">

                                        <button
                                            class="btn-modern btn-detail detail-btn"

                                            data-bs-toggle="modal"
                                            data-bs-target="#detailModal"

                                            data-title="<?= htmlspecialchars($data['title']); ?>"
                                            data-description="<?= htmlspecialchars($data['description']); ?>"
                                            data-category="<?= htmlspecialchars($data['category_name']); ?>"
                                            data-user="<?= htmlspecialchars($data['username']); ?>"
                                            data-tech="<?= htmlspecialchars($data['tech_stack']); ?>"
                                            data-image="<?= htmlspecialchars($data['image']); ?>"
                                            data-zip="<?= htmlspecialchars($data['zip_file']); ?>"
                                            data-demo="<?= htmlspecialchars($data['demo_link']); ?>"
                                            data-likes="<?= $data['likes_count']; ?>"
                                            data-download="<?= $data['download_count']; ?>">

                                            <i class="bi bi-eye"></i>
                                            Detail

                                        </button>

                                        <a
                                            href="manage-project.php?hapus=<?= $data['id']; ?>"
                                            class="btn-modern btn-delete"
                                            onclick="return confirm('Yakin ingin menghapus project ini?')">
                                            <i class="bi bi-trash"></i>
                                            Hapus
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- MODAL DETAIL -->
    <div class="modal fade" id="detailModal" tabindex="-1">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content modal-custom">

                <div class="modal-header border-0 pb-0">

                    <h4 class="fw-bold">
                        Detail Project
                    </h4>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body p-4">

                    <div class="row g-4">

                        <!-- LEFT -->
                        <div class="col-lg-5">

                            <img
                                id="modalImage"
                                class="detail-image"
                                src="">

                        </div>


                        <!-- RIGHT -->
                        <div class="col-lg-7 d-flex flex-column">

                            <!-- TITLE -->
                            <h2 id="modalTitle"
                                class="detail-title mb-3">
                            </h2>


                            <!-- INFO GRID -->
                            <div class="row g-3 mb-4">

                                <div class="col-6">

                                    <div class="info-card">

                                        <div class="info-label">
                                            Category
                                        </div>

                                        <div id="modalCategory"
                                            class="info-value">
                                        </div>

                                    </div>

                                </div>

                                <div class="col-6">

                                    <div class="info-card">

                                        <div class="info-label">
                                            Uploader
                                        </div>

                                        <div id="modalUser"
                                            class="info-value">
                                        </div>

                                    </div>

                                </div>

                                <div class="col-6">

                                    <div class="info-card">

                                        <div class="info-label">
                                            Likes
                                        </div>

                                        <div id="modalLikes"
                                            class="info-value">
                                        </div>

                                    </div>

                                </div>

                                <div class="col-6">

                                    <div class="info-card">

                                        <div class="info-label">
                                            Downloads
                                        </div>

                                        <div id="modalDownloads"
                                            class="info-value">
                                        </div>

                                    </div>

                                </div>

                            </div>


                            <!-- TECH STACK -->
                            <div class="mb-4">

                                <div class="info-label mb-2">
                                    Tech Stack
                                </div>

                                <div id="modalTech"
                                    class="tech-stack-box">
                                </div>

                            </div>


                            <!-- ACTION -->
                            <div class="d-flex gap-2 mt-auto flex-wrap">

                                <a
                                    id="modalDownload"
                                    href="#"
                                    class="btn-modern download-btn"
                                    download>

                                    <i class="bi bi-download"></i>
                                    Download ZIP

                                </a>

                                <a
                                    id="modalDemo"
                                    href="#"
                                    target="_blank"
                                    class="btn-modern demo-btn">

                                    <i class="bi bi-box-arrow-up-right"></i>
                                    Live Demo

                                </a>

                            </div>

                        </div>

                    </div>


                    <!-- DESCRIPTION -->
                    <div class="description-box mt-4">

                        <div class="info-label mb-2">
                            Description
                        </div>

                        <p id="modalDescription"
                            class="detail-desc mb-0">
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const detailButtons =
                document.querySelectorAll('.detail-btn');

            detailButtons.forEach(button => {

                button.addEventListener('click', function() {

                    document.getElementById('modalTitle')
                        .innerText = this.dataset.title;

                    document.getElementById('modalDescription')
                        .innerText = this.dataset.description;

                    document.getElementById('modalCategory')
                        .innerText = this.dataset.category || 'No Category';

                    document.getElementById('modalUser')
                        .innerText = this.dataset.user;

                    document.getElementById('modalTech')
                        .innerText = this.dataset.tech || '-';

                    document.getElementById('modalLikes')
                        .innerText = this.dataset.likes;

                    document.getElementById('modalDownloads')
                        .innerText = this.dataset.download;


                    // IMAGE
                    document.getElementById('modalImage')
                        .src =
                        '../user/uploads/thumbnails/' +
                        this.dataset.image;


                    // DOWNLOAD
                    document.getElementById('modalDownload')
                        .href =
                        '../user/uploads/projects/' +
                        this.dataset.zip;


                    // DEMO
                    const demoBtn =
                        document.getElementById('modalDemo');

                    if (
                        this.dataset.demo &&
                        this.dataset.demo.trim() !== ''
                    ) {

                        demoBtn.href =
                            this.dataset.demo;

                        demoBtn.style.display =
                            'inline-flex';

                    } else {

                        demoBtn.style.display =
                            'none';

                    }

                });

            });

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>