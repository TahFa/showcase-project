<?php

include 'koneksi.php';


// ==========================
// FILTER TANGGAL
// ==========================

$startDate = $_GET['start_date'] ?? '';
$endDate   = $_GET['end_date'] ?? '';

$where = "";

if (!empty($startDate) && !empty($endDate)) {

    $where = "
        WHERE DATE(projects.created_at)
        BETWEEN '$startDate'
        AND '$endDate'
    ";
}


// TOTAL USER
$total_user = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) as total 
        FROM users
        WHERE role='user'
    ")
)['total'];


// TOTAL PROJECT
$total_project = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) as total 
        FROM projects
    ")
)['total'];


// USER HARI INI
$user_today = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) as total
        FROM users
        WHERE DATE(created_at)=CURDATE()
    ")
)['total'];


// PROJECT HARI INI
$project_today = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) as total
        FROM projects
        WHERE DATE(created_at)=CURDATE()
    ")
)['total'];



// ==========================
// DATA LAPORAN
// ==========================

$projects = mysqli_query($conn, "

    SELECT
        projects.*,
        users.username,
        categories.category_name

    FROM projects

    JOIN users
    ON users.id = projects.user_id

    LEFT JOIN categories
    ON categories.id = projects.category_id

    $where

    ORDER BY projects.created_at DESC

");
?>

<style>
    body {
        background: linear-gradient(to bottom right, #f8fafc, #eef2ff);
        font-family: 'Poppins', sans-serif;
    }

    .dashboard-container {
        padding: 0px 15px;
    }

    .content {
        margin-left: 250px;
        padding: 30px;
    }

    .header-box {

        position: relative;
        overflow: hidden;

        background:
            linear-gradient(135deg, #4f46e5, #3b82f6, #06b6d4);

        padding: 35px;

        border-radius: 28px;

        display: flex;
        justify-content: space-between;
        align-items: center;

        box-shadow:
            0 20px 40px rgba(59, 130, 246, .25);

        margin-bottom: 30px;

        color: white;
    }

    .header-box::before {
        content: '';

        position: absolute;

        width: 280px;
        height: 280px;

        border-radius: 50%;

        background: rgba(255, 255, 255, .08);

        top: -120px;
        right: -80px;
    }

    .header-title {
        position: relative;
        z-index: 2;
    }

    .header-title h4 {
        font-size: 34px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .header-title p {
        margin: 0;
        opacity: .92;
        font-size: 15px;
    }

    .welcome-admin {

        position: relative;
        z-index: 2;

        background: rgba(255, 255, 255, .15);

        border: 1px solid rgba(255, 255, 255, .18);

        backdrop-filter: blur(12px);

        padding: 14px 24px;

        border-radius: 50px;

        font-weight: 600;
    }

    .dashboard-cards {

        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));

        gap: 22px;

        margin-bottom: 30px;
    }

    .card-modern {

        position: relative;
        overflow: hidden;

        border-radius: 24px;

        padding: 28px;

        color: white;

        transition: .35s ease;

        box-shadow:
            0 15px 35px rgba(0, 0, 0, .08);
    }

    .card-modern:hover {
        transform: translateY(-8px);
    }

    .card-modern:nth-child(1) {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
    }

    .card-modern:nth-child(2) {
        background: linear-gradient(135deg, #06b6d4, #3b82f6);
    }

    .card-top {

        display: flex;
        justify-content: space-between;
        align-items: center;

        margin-bottom: 22px;
    }

    .card-icon {

        width: 65px;
        height: 65px;

        border-radius: 20px;

        background: rgba(255, 255, 255, .18);

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 28px;
    }

    .card-modern h5 {
        opacity: 0.9;
        font-size: 17px;
        font-weight: 600;
    }

    .card-modern h2 {
        font-size: 42px;
        font-weight: 800;
        margin: 10px 0;
    }

    .card-modern small {
        opacity: 0.9;
        font-size: 14px;
    }


    /* REPORT BOX */
    .report-box {

        background: #ffffff;

        padding: 30px;

        border-radius: 28px;

        box-shadow:
            0 10px 35px rgba(0, 0, 0, .05);
    }


    /* FILTER */
    .filter-box {

        background: #f8fafc;

        padding: 20px;

        border-radius: 22px;

        margin-bottom: 25px;
    }

    .filter-box label {
        font-size: 14px;
        font-weight: 600;
        color: #334155;
    }


    /* TABLE HEADER */
    .table-header {

        display: flex;
        justify-content: space-between;
        align-items: center;

        margin-bottom: 20px;

        flex-wrap: wrap;
        gap: 15px;
    }

    .table-title h5 {

        margin: 0;

        font-size: 24px;
        font-weight: 800;

        color: #0f172a;
    }

    .table-title p {

        margin: 5px 0 0;

        color: #64748b;
        font-size: 14px;
    }


    /* DATATABLE */
    table {
        border-collapse: separate;
        border-spacing: 0 14px;
    }

    table thead th {

        border: none !important;

        color: #64748b;

        font-size: 14px;
        font-weight: 700;
    }

    table tbody tr {

        background: #f8fafc;

        transition: .25s ease;
    }

    table tbody tr:hover {

        background: #eef4ff;

        transform: scale(1.01);
    }

    table tbody td {

        border: none !important;

        padding: 18px 15px !important;

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


    /* USER */
    .user-info {

        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {

        width: 45px;
        height: 45px;

        border-radius: 50%;

        background:
            linear-gradient(135deg, #6366f1, #3b82f6);

        display: flex;
        align-items: center;
        justify-content: center;

        color: white;
        font-weight: 700;
    }


    /* BADGE */
    .badge-modern {

        padding: 8px 16px;

        border-radius: 50px;

        font-size: 13px;
        font-weight: 600;
    }

    .badge-publish {

        background: #dcfce7;
        color: #15803d;
    }

    .tech-badge {

        background: #e2e8f0;
        color: #334155;

        padding: 8px 14px;

        border-radius: 50px;

        font-size: 13px;
        font-weight: 600;
    }


    /* DATATABLE CUSTOM */
    .dataTables_wrapper .dataTables_filter input {

        border: 1px solid #e2e8f0;

        border-radius: 14px;

        padding: 10px 14px;

        margin-left: 10px;

        outline: none;
    }

    /* SHOW DATA */
    .dataTables_wrapper .dataTables_length select {

        min-width: 90px !important;

        width: 90px !important;

        height: 46px !important;

        padding-left: 14px !important;

        padding-right: 38px !important;

        border-radius: 14px !important;

        border: 1px solid #dbeafe !important;

        background-position: right 14px center !important;

        font-size: 14px !important;
    }


    /* CONTAINER */
    .dataTables_wrapper .dataTables_length {

        display: flex !important;

        align-items: center !important;

        gap: 12px !important;

        margin-top: 18px;
        margin-bottom: 28px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {

        border-radius: 12px !important;

        margin: 0 4px;
    }

    /* DATATABLE TOP SPACING */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {

        margin-top: 18px;
        margin-bottom: 28px;
    }

    /* SEARCH INPUT */
    .dataTables_wrapper .dataTables_filter input {

        border: 1px solid #dbeafe;

        border-radius: 16px;

        padding: 12px 16px;

        height: 48px;

        min-width: 240px;
    }


    /* SHOW DATA SELECT */
    .dataTables_wrapper .dataTables_length select {

        height: 46px;

        padding: 0 14px;

        border-radius: 14px;

        margin: 0 10px;
    }


    /* BIAR HEADER TABEL ADA JARAK */
    #reportTable thead th {

        padding-top: 12px !important;
        padding-bottom: 18px !important;
    }
</style>


<!-- FONT AWESOME -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
    rel="stylesheet">


<!-- DATATABLE -->
<link rel="stylesheet"
    href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">


<div class="dashboard-container">

    <!-- HEADER -->
    <div class="header-box">

        <div class="header-title">

            <h4>Admin Dashboard 🚀</h4>

            <p>
                Kelola seluruh project showcase dan laporan aktivitas user.
            </p>

        </div>

        <div class="welcome-admin">
            👋 Welcome Admin
        </div>

    </div>


    <!-- CARDS -->
    <div class="dashboard-cards">

        <div class="card-modern">

            <div class="card-top">

                <div>

                    <h5>Total User</h5>

                    <h2><?= $total_user; ?></h2>

                </div>

                <div class="card-icon">
                    <i class="fa-solid fa-users"></i>
                </div>

            </div>

            <small>
                <i class="fa-solid fa-arrow-trend-up"></i>
                +<?= $user_today; ?> user hari ini
            </small>

        </div>


        <div class="card-modern">

            <div class="card-top">

                <div>

                    <h5>Total Project</h5>

                    <h2><?= $total_project; ?></h2>

                </div>

                <div class="card-icon">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>

            </div>

            <small>
                <i class="fa-solid fa-folder-plus"></i>
                +<?= $project_today; ?> project hari ini
            </small>

        </div>

    </div>



    <!-- REPORT -->
    <div class="report-box">


        <!-- HEADER -->
        <div class="table-header">

            <div class="table-title">

                <h5>📊 Project Report</h5>

                <p>
                    Laporan aktivitas seluruh project user
                </p>

            </div>

        </div>


        <!-- FILTER -->
        <div class="filter-box">

            <form method="GET" class="row g-3 align-items-end">

                <div class="col-md-4">

                    <label>
                        Dari Tanggal
                    </label>

                    <input
                        type="date"
                        name="start_date"
                        class="form-control"
                        value="<?= $startDate; ?>">

                </div>


                <div class="col-md-4">

                    <label>
                        Sampai Tanggal
                    </label>

                    <input
                        type="date"
                        name="end_date"
                        class="form-control"
                        value="<?= $endDate; ?>">

                </div>


                <div class="col-md-4 d-flex gap-2">

                    <button class="btn btn-primary px-4">

                        <i class="fa-solid fa-filter"></i>
                        Filter

                    </button>


                    <a
                        href="export-pdf.php?start_date=<?= $startDate; ?>&end_date=<?= $endDate; ?>"
                        target="_blank"
                        class="btn btn-danger px-4">

                        <i class="fa-solid fa-file-pdf"></i>
                        PDF

                    </a>

                </div>

            </form>

        </div>


        <!-- TABLE -->
        <div class="table-responsive">

            <table
                id="reportTable"
                class="table align-middle w-100">

                <thead>

                    <tr>

                        <th>User</th>
                        <th>Project</th>
                        <th>Category</th>
                        <th>Likes</th>
                        <th>Downloads</th>
                        <th>Status</th>
                        <th>Tanggal</th>

                    </tr>

                </thead>

                <tbody>

                    <?php while ($data = mysqli_fetch_assoc($projects)) : ?>

                        <tr>

                            <td>

                                <div class="user-info">

                                    <div class="user-avatar">

                                        <?= strtoupper(substr($data['username'], 0, 1)); ?>

                                    </div>

                                    <div>
                                        <?= htmlspecialchars($data['username']); ?>
                                    </div>

                                </div>

                            </td>

                            <td class="fw-semibold">

                                <?= htmlspecialchars($data['title']); ?>

                            </td>

                            <td>

                                <span class="tech-badge">

                                    <?= !empty($data['category_name'])
                                        ? htmlspecialchars($data['category_name'])
                                        : 'No Category'; ?>

                                </span>

                            </td>

                            <td>
                                <?= $data['likes_count']; ?>
                            </td>

                            <td>
                                <?= $data['download_count']; ?>
                            </td>

                            <td>

                                <span class="badge-modern badge-publish">
                                    Published
                                </span>

                            </td>

                            <td>

                                <?= date('d M Y', strtotime($data['created_at'])); ?>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>



<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


<!-- DATATABLE -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>


<script>
    $(document).ready(function() {

        $('#reportTable').DataTable({

            pageLength: 8,

            lengthMenu: [
                [5, 8, 10, 25, 50],
                [5, 8, 10, 25, 50]
            ],

            responsive: true,

            language: {

                search: "",

                searchPlaceholder: "Search report...",

                lengthMenu: "Show _MENU_ data",

                info: "Showing _START_ to _END_ of _TOTAL_ reports",

                paginate: {
                    previous: "Prev",
                    next: "Next"
                }

            }

        });

    });
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>