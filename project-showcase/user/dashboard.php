<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

$username = $_SESSION['username'];
$user_id  = $_SESSION['id'];

$menu = $_GET['menu'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/style-dashboard.css">
    <link rel="stylesheet" href="css/style-myprojects.css">

</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar-custom">

        <div class="logo-section">
            <i class="bi bi-folder2-open"></i>
            <h4>ProjectHub</h4>
        </div>

        <div class="menu-section">

            <a href="?menu=dashboard"
                class="<?= $menu === 'dashboard' ? 'active' : '' ?>">
                Beranda
            </a>

            <a href="?menu=trending"
                class="<?= $menu === 'trending' ? 'active' : '' ?>">
                Trending
            </a>

            <a href="?menu=my_projects"
                class="<?= $menu === 'my_projects' ? 'active' : '' ?>">
                My Projects
            </a>

        </div>

        <div class="right-section">

            <a href="?menu=new_project"
                class="new-project-btn text-decoration-none">

                <i class="bi bi-plus-lg"></i>
                New Project

            </a>

            <div class="profile-avatar">
                <?= strtoupper(substr($username, 0, 1)); ?>
            </div>

            <a href="logout.php" class="logout-icon">
                <i class="bi bi-box-arrow-right"></i>
            </a>

        </div>

    </nav>


    <!-- CONTENT -->
    <?php include 'menu.php'; ?>


  <script>
    document.addEventListener('DOMContentLoaded', function() {

        // =========================
        // AUTO SEARCH FROM INDEX
        // =========================
        const urlParams = new URLSearchParams(window.location.search);

        const searchProject =
            urlParams.get('search');

        // SEARCH
        const searchInput =
            document.querySelector('.search-box input');

        if (searchProject && searchInput) {

            searchInput.value =
                searchProject;

        }

        // CARD
        const cards =
            document.querySelectorAll('.project-card');

        // GRID
        const projectGrid =
            document.getElementById('projectGrid');

        // VIEW BUTTON
        const gridBtn =
            document.getElementById('gridViewBtn');

        const listBtn =
            document.getElementById('listViewBtn');

        // PAGINATION
        const paginationNumbers =
            document.getElementById('paginationNumbers');

        const prevBtn =
            document.getElementById('prevPage');

        const nextBtn =
            document.getElementById('nextPage');

        const showingData =
            document.getElementById('showingData');

        let currentPage = 1;

        let cardsPerPage = 6;

        let filteredCards = [...cards];


        // =========================
        // DISPLAY CARD
        // =========================
        function displayCards() {

            cards.forEach(card => {
                card.style.display = 'none';
            });

            const start =
                (currentPage - 1) * cardsPerPage;

            const end =
                start + cardsPerPage;

            filteredCards
                .slice(start, end)
                .forEach(card => {

                    card.style.display = '';

                    if (
                        projectGrid &&
                        projectGrid.classList.contains('list-view')
                    ) {
                        card.style.display = 'flex';
                    }

                });

            if (showingData) {

                if (filteredCards.length > 0) {

                    showingData.innerText =
                        `Showing ${start + 1}-${Math.min(end, filteredCards.length)} projects`;

                } else {

                    showingData.innerText =
                        'Showing 0-0 projects';

                }

            }

        }


        // =========================
        // PAGINATION
        // =========================
        function setupPagination() {

            if (!paginationNumbers) return;

            paginationNumbers.innerHTML = '';

            const pageCount =
                Math.ceil(filteredCards.length / cardsPerPage);

            for (let i = 1; i <= pageCount; i++) {

                const btn =
                    document.createElement('button');

                btn.classList.add('pagination-btn');

                btn.innerText = i;

                if (i === currentPage) {
                    btn.classList.add('active');
                }

                btn.addEventListener('click', () => {

                    currentPage = i;

                    updateUI();

                });

                paginationNumbers.appendChild(btn);

            }

        }


        // =========================
        // UPDATE UI
        // =========================
        function updateUI() {

            displayCards();

            setupPagination();

        }


        // =========================
        // PREV PAGE
        // =========================
        if (prevBtn) {

            prevBtn.addEventListener('click', () => {

                if (currentPage > 1) {

                    currentPage--;

                    updateUI();

                }

            });

        }


        // =========================
        // NEXT PAGE
        // =========================
        if (nextBtn) {

            nextBtn.addEventListener('click', () => {

                const totalPages =
                    Math.ceil(filteredCards.length / cardsPerPage);

                if (currentPage < totalPages) {

                    currentPage++;

                    updateUI();

                }

            });

        }


        // =========================
        // SEARCH
        // =========================
        if (searchInput) {

            searchInput.addEventListener('keyup', function() {

                const value =
                    this.value.toLowerCase();

                filteredCards = [...cards].filter(card => {

                    const title =
                        card.querySelector('h5')
                        .innerText
                        .toLowerCase();

                    return title.includes(value);

                });

                currentPage = 1;

                updateUI();

            });

        }


        // =========================
        // AUTO FILTER DARI INDEX
        // =========================
        if (searchProject && searchInput) {

            filteredCards = [...cards].filter(card => {

                const title =
                    card.querySelector('h5')
                    .innerText
                    .toLowerCase();

                return title.includes(
                    searchProject.toLowerCase()
                );

            });

        }


        // =========================
        // CATEGORY FILTER
        // =========================
        const filterButtons =
            document.querySelectorAll('.filter-section button');

        filterButtons.forEach(button => {

            button.addEventListener('click', () => {

                filterButtons.forEach(btn =>
                    btn.classList.remove('active')
                );

                button.classList.add('active');

                const category =
                    button.dataset.category;

                filteredCards = [...cards].filter(card => {

                    if (category === 'all') {
                        return true;
                    }

                    return card.dataset.category === category;

                });

                currentPage = 1;

                updateUI();

            });

        });


        // =========================
        // GRID VIEW
        // =========================
        if (gridBtn && projectGrid) {

            gridBtn.addEventListener('click', () => {

                projectGrid.classList.remove('list-view');

                gridBtn.classList.add('active');

                if (listBtn) {
                    listBtn.classList.remove('active');
                }

                displayCards();

            });

        }


        // =========================
        // LIST VIEW
        // =========================
        if (listBtn && projectGrid) {

            listBtn.addEventListener('click', () => {

                projectGrid.classList.add('list-view');

                listBtn.classList.add('active');

                if (gridBtn) {
                    gridBtn.classList.remove('active');
                }

                displayCards();

            });

        }


        // =========================
        // LIKE BUTTON
        // =========================
        const likeButtons =
            document.querySelectorAll('.like-btn');

        likeButtons.forEach(button => {

            button.addEventListener('click', async function() {

                const projectId =
                    this.dataset.id;

                try {

                    const response =
                        await fetch('toggle_like.php', {

                            method: 'POST',

                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },

                            body: 'project_id=' + projectId

                        });

                    const data =
                        await response.json();

                    if (data.success) {

                        const icon =
                            this.querySelector('i');

                        const count =
                            this.querySelector('.like-count');

                        count.innerText = data.total;

                        if (data.liked) {

                            this.classList.add('liked');

                            icon.classList.remove('bi-heart');

                            icon.classList.add('bi-heart-fill');

                        } else {

                            this.classList.remove('liked');

                            icon.classList.remove('bi-heart-fill');

                            icon.classList.add('bi-heart');

                        }

                    }

                } catch (error) {

                    console.log(error);

                }

            });

        });


        // =========================
        // DOWNLOAD MODAL
        // =========================
        const downloadButtons =
            document.querySelectorAll('.download-btn');

        const downloadFileName =
            document.getElementById('zipFileName');

        const downloadBtn =
            document.getElementById('downloadZipBtn');

        let currentDownloadUrl = '';

        downloadButtons.forEach(button => {

            button.addEventListener('click', function() {

                const file =
                    this.dataset.file;

                const projectId =
                    this.dataset.id;

                // FILE NAME
                if (downloadFileName) {

                    downloadFileName.value =
                        file || 'No ZIP file';

                }

                // SAVE URL
                currentDownloadUrl =
                    'toggle_download.php?id=' + projectId;

                // SHOW MODAL
                const modal =
                    new bootstrap.Modal(
                        document.getElementById('downloadModal')
                    );

                modal.show();

            });

        });


        // =========================
        // DOWNLOAD BUTTON
        // =========================
        if (downloadBtn) {

            downloadBtn.addEventListener('click', function() {

                // DOWNLOAD FILE
                window.location.href = currentDownloadUrl;

                // UPDATE COUNT LANGSUNG
                const activeButton =
                    document.querySelector(
                        '.download-btn[data-id="' +
                        currentDownloadUrl.split('=')[1] +
                        '"]'
                    );

                if (activeButton && !activeButton.dataset.downloaded) {

                    const countElement =
                        activeButton.querySelector('.download-count');

                    let currentCount =
                        parseInt(countElement.innerText);

                    activeButton.dataset.downloaded = "true";
                }

            });

        }


        // =========================
        // INIT
        // =========================
        updateUI();

    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', () => {

        const buttons = document.querySelectorAll('.menu-toggle');

        buttons.forEach(button => {

            button.addEventListener('click', function(e) {

                e.stopPropagation();

                const dropdown =
                    this.parentElement.querySelector('.custom-dropdown');

                document.querySelectorAll('.custom-dropdown')
                    .forEach(menu => {

                        if (menu !== dropdown) {
                            menu.classList.remove('show');
                        }

                    });

                dropdown.classList.toggle('show');

            });

        });

        document.addEventListener('click', () => {

            document.querySelectorAll('.custom-dropdown')
                .forEach(menu => {
                    menu.classList.remove('show');
                });

        });

    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>