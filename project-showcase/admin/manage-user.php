<?php
include 'koneksi.php';


// =======================
// HAPUS USER
// =======================

if (isset($_GET['hapus'])) {

    $id = intval($_GET['hapus']);

    mysqli_query($conn, "
        DELETE FROM users 
        WHERE id='$id'
    ");

    echo "
    <script>
        alert('User berhasil dihapus');
        window.location='?menu=user';
    </script>
    ";
}



// =======================
// BAN USER
// =======================

if (isset($_POST['ban_user'])) {

    $user_id = intval($_POST['user_id']);
    $durasi = intval($_POST['durasi']);
    $alasan = mysqli_real_escape_string($conn, $_POST['alasan']);

    $tanggal_ban = date(
        'Y-m-d H:i:s',
        strtotime("+$durasi days")
    );

    mysqli_query($conn, "
        UPDATE users 
        SET 
            banned_until='$tanggal_ban',
            ban_reason='$alasan'
        WHERE id='$user_id'
    ");

    echo "
    <script>
        alert('User berhasil diban');
        window.location='?menu=user';
    </script>
    ";
}



// =======================
// UNBAN USER
// =======================

if (isset($_GET['unban'])) {

    $id = intval($_GET['unban']);

    mysqli_query($conn, "
        UPDATE users 
        SET 
            banned_until=NULL,
            ban_reason=NULL
        WHERE id='$id'
    ");

    echo "
    <script>
        alert('User berhasil di unban');
        window.location='?menu=user';
    </script>
    ";
}



// =======================
// QUERY USER
// =======================

$query = mysqli_query($conn, "
    SELECT * 
    FROM users
    WHERE role='user'
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Management User</title>


    <!-- BOOTSTRAP -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- ICON -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <!-- FONT -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">


    <style>
        body {
            background:
                linear-gradient(to bottom right, #f8fafc, #eef2ff);

            font-family: 'Poppins', sans-serif;
        }

        .page-header {

            background:
                linear-gradient(135deg, #4f46e5, #3b82f6);

            border-radius: 28px;

            padding: 35px;

            color: white;

            position: relative;

            overflow: hidden;

            margin-bottom: 30px;

            box-shadow:
                0 20px 40px rgba(59, 130, 246, .18);
        }

        .page-header::before {

            content: '';

            position: absolute;

            width: 220px;
            height: 220px;

            border-radius: 50%;

            background: rgba(255, 255, 255, .08);

            top: -80px;
            right: -80px;
        }

        .page-header h3 {

            font-weight: 800;
            font-size: 34px;

            margin-bottom: 10px;

            position: relative;
            z-index: 2;
        }

        .page-header p {

            margin: 0;

            opacity: .92;

            position: relative;
            z-index: 2;
        }

        .table-box {

            background: white;

            border-radius: 28px;

            padding: 28px;

            box-shadow:
                0 10px 35px rgba(0, 0, 0, .05);
        }

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

            transition: .3s ease;
        }

        table tbody tr:hover {

            background: #eef4ff;

            transform: translateY(-2px);
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

        .user-box {

            display: flex;
            align-items: center;
            gap: 14px;
        }

        .avatar {

            width: 48px;
            height: 48px;

            border-radius: 50%;

            background:
                linear-gradient(135deg, #6366f1, #3b82f6);

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;

            font-weight: 700;
            font-size: 18px;

            flex-shrink: 0;
        }

        .username {

            font-weight: 600;
            color: #0f172a;
        }

        .user-email {

            font-size: 13px;
            color: #64748b;
        }

        .badge-active {

            background: #dcfce7;
            color: #15803d;

            padding: 8px 16px;

            border-radius: 50px;

            font-size: 13px;
            font-weight: 600;
        }

        .badge-banned {

            background: #fee2e2;
            color: #dc2626;

            padding: 8px 16px;

            border-radius: 50px;

            font-size: 13px;
            font-weight: 600;
        }

        .btn-modern {

            border: none;

            border-radius: 12px;

            padding: 9px 16px;

            font-size: 13px;
            font-weight: 600;

            transition: .25s;
        }

        .btn-modern:hover {

            transform: translateY(-2px);
        }

        .btn-ban {

            background: #facc15;
            color: #000;
        }

        .btn-unban {

            background: #22c55e;
            color: white;
        }

        .btn-delete {

            background: #ef4444;
            color: white;
        }

        .modal-content {

            border: none;
            border-radius: 24px;

            overflow: hidden;
        }

        .modal-header {

            background:
                linear-gradient(135deg, #4f46e5, #3b82f6);

            color: white;

            border: none;
        }

        .modal-title {

            font-weight: 700;
        }

        .form-control,
        .form-select {

            border-radius: 14px;

            padding: 12px 15px;

            border: 1px solid #dbeafe;
        }

        .form-control:focus,
        .form-select:focus {

            border-color: #3b82f6;

            box-shadow:
                0 0 0 4px rgba(59, 130, 246, .12);
        }
    </style>

</head>

<body>

    <div class="container-fluid py-4 px-4">

        <!-- HEADER -->
        <div class="page-header">

            <h3>
                Management User 👥
            </h3>

            <p>
                Kelola seluruh user, ban akun bermasalah, dan hapus user dengan mudah.
            </p>

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
                                User
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Dibuat
                            </th>

                            <th width="280">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php
                        $no = 1;

                        while ($data = mysqli_fetch_assoc($query)) :
                        ?>

                            <tr>

                                <td>
                                    <?= $no++; ?>
                                </td>


                                <!-- USER -->
                                <td>

                                    <div class="user-box">

                                        <div class="avatar">

                                            <?= strtoupper(substr($data['username'], 0, 1)); ?>

                                        </div>

                                        <div>

                                            <div class="username">

                                                <?= htmlspecialchars($data['username']); ?>

                                            </div>

                                            <div class="user-email">

                                                User Account

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <!-- STATUS -->
                                <td>

                                    <?php
                                    if (
                                        !empty($data['banned_until']) &&
                                        strtotime($data['banned_until']) > time()
                                    ) :
                                    ?>

                                        <span class="badge-banned">

                                            <i class="bi bi-slash-circle"></i>
                                            Banned

                                        </span>

                                    <?php else : ?>

                                        <span class="badge-active">

                                            <i class="bi bi-check-circle"></i>
                                            Active

                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- CREATED -->
                                <td>

                                    <?= date('d M Y', strtotime($data['created_at'])); ?>

                                </td>


                                <!-- ACTION -->
                                <td class="d-flex gap-2 flex-wrap">

                                    <?php
                                    $is_banned = (
                                        !empty($data['banned_until']) &&
                                        strtotime($data['banned_until']) > time()
                                    );
                                    ?>


                                    <?php if (!$is_banned) : ?>

                                        <!-- BAN -->
                                        <button
                                            type="button"
                                            class="btn btn-modern btn-ban btn-sm open-ban-modal"

                                            data-id="<?= $data['id']; ?>"
                                            data-username="<?= htmlspecialchars($data['username']); ?>"

                                            data-bs-toggle="modal"
                                            data-bs-target="#banModal">

                                            <i class="bi bi-slash-circle"></i>
                                            Ban

                                        </button>

                                    <?php else : ?>

                                        <!-- UNBAN -->
                                        <a
                                            href="?menu=user&unban=<?= $data['id']; ?>"
                                            class="btn btn-modern btn-unban btn-sm"
                                            onclick="return confirm('Yakin ingin membuka ban user ini?')">

                                            <i class="bi bi-check-circle"></i>
                                            Unban

                                        </a>

                                    <?php endif; ?>


                                    <!-- DELETE -->
                                    <a
                                        href="?menu=user&hapus=<?= $data['id']; ?>"
                                        class="btn btn-modern btn-delete btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus user ini?')">

                                        <i class="bi bi-trash"></i>
                                        Hapus

                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>




    <!-- GLOBAL MODAL -->
    <div
        class="modal fade"
        id="banModal"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <form method="POST">

                    <div class="modal-header">

                        <h5 class="modal-title">

                            Ban User

                        </h5>

                        <button
                            type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>

                    </div>


                    <div class="modal-body">

                        <input
                            type="hidden"
                            name="user_id"
                            id="ban_user_id">

                        <div class="mb-3">

                            <label class="form-label">
                                Username
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="ban_username"
                                readonly>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Durasi Ban
                            </label>

                            <select
                                name="durasi"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Durasi --
                                </option>

                                <option value="1">
                                    1 Hari
                                </option>

                                <option value="3">
                                    3 Hari
                                </option>

                                <option value="7">
                                    7 Hari
                                </option>

                                <option value="14">
                                    14 Hari
                                </option>

                                <option value="30">
                                    30 Hari
                                </option>

                            </select>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Alasan Ban
                            </label>

                            <textarea
                                name="alasan"
                                class="form-control"
                                rows="4"
                                placeholder="Masukkan alasan ban..."
                                required></textarea>

                        </div>

                    </div>


                    <div class="modal-footer border-0">

                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button
                            type="submit"
                            name="ban_user"
                            class="btn btn-warning">
                            Ban User
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        const banButtons = document.querySelectorAll('.open-ban-modal');

        banButtons.forEach(button => {

            button.addEventListener('click', function() {

                const userId = this.dataset.id;
                const username = this.dataset.username;

                document.getElementById('ban_user_id').value = userId;
                document.getElementById('ban_username').value = username;

            });

        });
    </script>

</body>

</html>