<?php
include 'koneksi.php';


// =======================
// TAMBAH CATEGORY
// =======================

if (isset($_POST['tambah'])) {

    $category = trim($_POST['category']);

    // VALIDASI KOSONG
    if ($category == '') {

        echo "
        <script>
            alert('Category tidak boleh kosong');
            window.location='index.php?menu=category';
        </script>
        ";

        exit;
    }

    $category = mysqli_real_escape_string($conn, $category);

    // CEK DUPLIKAT
    $cek = mysqli_query($conn, "
        SELECT *
        FROM categories
        WHERE category_name='$category'
    ");

    if (mysqli_num_rows($cek) > 0) {

        echo "
        <script>
            alert('Category sudah ada');
            window.location='index.php?menu=category';
        </script>
        ";

        exit;
    }

    mysqli_query($conn, "
        INSERT INTO categories(category_name)
        VALUES('$category')
    ");

    echo "
    <script>
        alert('Category berhasil ditambahkan');
        window.location='index.php?menu=category';
    </script>
    ";
}



// =======================
// EDIT CATEGORY
// =======================

if (isset($_POST['edit'])) {

    $id = intval($_POST['id']);

    $category = trim($_POST['category']);

    // VALIDASI KOSONG
    if ($category == '') {

        echo "
        <script>
            alert('Category tidak boleh kosong');
            window.location='index.php?menu=category';
        </script>
        ";

        exit;
    }

    $category = mysqli_real_escape_string($conn, $category);

    // CEK DUPLIKAT
    $cek = mysqli_query($conn, "
        SELECT *
        FROM categories
        WHERE category_name='$category'
        AND id != '$id'
    ");

    if (mysqli_num_rows($cek) > 0) {

        echo "
        <script>
            alert('Category sudah digunakan');
            window.location='index.php?menu=category';
        </script>
        ";

        exit;
    }

    mysqli_query($conn, "
        UPDATE categories
        SET category_name='$category'
        WHERE id='$id'
    ");

    echo "
    <script>
        alert('Category berhasil diupdate');
        window.location='index.php?menu=category';
    </script>
    ";
}



// =======================
// HAPUS CATEGORY
// =======================

if (isset($_GET['hapus'])) {

    $id = intval($_GET['hapus']);

    // CEK APAKAH CATEGORY DIGUNAKAN PROJECT
    $cek_project = mysqli_query($conn, "
        SELECT *
        FROM projects
        WHERE category_id='$id'
    ");

    if (mysqli_num_rows($cek_project) > 0) {

        echo "
        <script>
            alert('Category tidak bisa dihapus karena masih digunakan project');
            window.location='index.php?menu=category';
        </script>
        ";

        exit;
    }

    mysqli_query($conn, "
        DELETE FROM categories
        WHERE id='$id'
    ");

    echo "
    <script>
        alert('Category berhasil dihapus');
        window.location='index.php?menu=category';
    </script>
    ";
}



// =======================
// QUERY CATEGORY
// =======================

$query = mysqli_query($conn, "
    SELECT *
    FROM categories
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta 
        name="viewport" 
        content="width=device-width, initial-scale=1.0"
    >

    <title>Management Category</title>


    <!-- BOOTSTRAP -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >


    <!-- ICON -->
    <link 
        rel="stylesheet" 
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- FONT -->
    <link 
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" 
        rel="stylesheet"
    >


    <style>

        body{
            background:
            linear-gradient(to bottom right,#f8fafc,#eef2ff);

            font-family:'Poppins',sans-serif;
        }

        /* HEADER */
        .page-header{

            background:
            linear-gradient(135deg,#6366f1,#3b82f6);

            color:white;

            border-radius:28px;

            padding:28px 32px;

            position:relative;

            overflow:hidden;

            box-shadow:
            0 20px 40px rgba(59,130,246,.18);
        }

        .page-header::before{

            content:'';

            position:absolute;

            width:180px;
            height:180px;

            border-radius:50%;

            background:rgba(255,255,255,.08);

            top:-60px;
            right:-60px;
        }

        .page-header h3{

            font-weight:800;

            margin:0;
        }

        .page-header p{

            margin:0;

            opacity:.9;
        }

        /* CARD TABLE */
        .table-box{

            background:white;

            border-radius:28px;

            padding:24px;

            box-shadow:
            0 10px 35px rgba(0,0,0,.05);
        }

        table{

            border-collapse:separate;

            border-spacing:0 12px;
        }

        table thead th{

            border:none !important;

            color:#64748b;

            font-size:13px;
            font-weight:700;
        }

        table tbody tr{

            background:#f8fafc;

            transition:.25s ease;
        }

        table tbody tr:hover{

            background:#eef4ff;

            transform:translateY(-2px);
        }

        table tbody td{

            border:none !important;

            padding:16px !important;

            vertical-align:middle;
        }

        table tbody tr td:first-child{

            border-top-left-radius:16px;
            border-bottom-left-radius:16px;
        }

        table tbody tr td:last-child{

            border-top-right-radius:16px;
            border-bottom-right-radius:16px;
        }

        /* BUTTON */
        .btn-modern{

            border-radius:12px;

            font-size:13px;
            font-weight:600;

            padding:8px 14px;

            transition:.25s;
        }

        .btn-modern:hover{

            transform:translateY(-2px);
        }

        .btn-add{

            background:#3b82f6;

            color:white;
        }

        .btn-edit{

            background:#facc15;

            color:#000;
        }

        .btn-delete{

            background:#ef4444;

            color:white;
        }

        /* MODAL */
        .modal-content{

            border:none;

            border-radius:24px;

            overflow:hidden;
        }

        .modal-header{

            background:
            linear-gradient(135deg,#6366f1,#3b82f6);

            color:white;

            border:none;
        }

        .form-control{

            border-radius:14px;

            padding:12px;

            border:1px solid #dbeafe;
        }

        .form-control:focus{

            border-color:#3b82f6;

            box-shadow:
            0 0 0 4px rgba(59,130,246,.12);
        }

    </style>

</head>

<body>

<div class="container-fluid py-4 px-4">

    <!-- HEADER -->
    <div class="page-header d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Management Category 📁
            </h3>

            <p>
                Kelola kategori project dengan mudah
            </p>

        </div>

        <button 
            class="btn btn-modern btn-add"
            data-bs-toggle="modal"
            data-bs-target="#tambahModal"
        >

            <i class="bi bi-plus-circle"></i>
            Tambah

        </button>

    </div>

    <!-- TABLE -->
    <div class="table-box">

        <div class="table-responsive">

            <table class="table align-middle w-100">

                <thead>

                    <tr>

                        <th width="60">
                            No
                        </th>

                        <th>
                            Nama Category
                        </th>

                        <th width="220">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php 
                $no = 1;

                while($data = mysqli_fetch_assoc($query)) : 
                ?>

                    <tr>

                        <td>
                            <?= $no++; ?>
                        </td>

                        <td class="fw-semibold text-dark">

                            <?= htmlspecialchars($data['category_name']); ?>

                        </td>

                        <td class="d-flex gap-2">

                            <!-- EDIT -->
                            <button 
                                class="btn btn-modern btn-edit btn-sm"

                                data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $data['id']; ?>"
                            >

                                <i class="bi bi-pencil-square"></i>
                                Edit

                            </button>


                            <!-- DELETE -->
                            <a 
                                href="index.php?menu=category&hapus=<?= $data['id']; ?>"

                                class="btn btn-modern btn-delete btn-sm"

                                onclick="return confirm('Yakin ingin menghapus category ini?')"
                            >

                                <i class="bi bi-trash"></i>
                                Hapus

                            </a>

                        </td>

                    </tr>


                    <!-- MODAL EDIT -->
                    <div 
                        class="modal fade"
                        id="editModal<?= $data['id']; ?>"
                        tabindex="-1"
                    >

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <form method="POST">

                                    <div class="modal-header">

                                        <h5 class="modal-title">
                                            Edit Category
                                        </h5>

                                        <button 
                                            type="button"
                                            class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"
                                        ></button>

                                    </div>

                                    <div class="modal-body">

                                        <input 
                                            type="hidden"
                                            name="id"
                                            value="<?= $data['id']; ?>"
                                        >

                                        <label class="form-label">
                                            Nama Category
                                        </label>

                                        <input 
                                            type="text"

                                            name="category"

                                            class="form-control"

                                            value="<?= htmlspecialchars($data['category_name']); ?>"

                                            required
                                        >

                                    </div>

                                    <div class="modal-footer border-0">

                                        <button 
                                            type="button"
                                            class="btn btn-light"
                                            data-bs-dismiss="modal"
                                        >
                                            Batal
                                        </button>

                                        <button 
                                            type="submit"
                                            name="edit"
                                            class="btn btn-warning"
                                        >
                                            Update
                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- MODAL TAMBAH -->
<div 
    class="modal fade"
    id="tambahModal"
    tabindex="-1"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Tambah Category
                    </h5>

                    <button 
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    <label class="form-label">
                        Nama Category
                    </label>

                    <input 
                        type="text"

                        name="category"

                        class="form-control"

                        placeholder="Masukkan category"

                        required
                    >

                </div>

                <div class="modal-footer border-0">

                    <button 
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button 
                        type="submit"
                        name="tambah"
                        class="btn btn-primary"
                    >
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>