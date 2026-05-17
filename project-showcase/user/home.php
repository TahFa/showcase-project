<?php

require_once 'koneksi.php';

$user_id = $_SESSION['id'];

$query = mysqli_query($conn, "

    SELECT 
        p.*,
        u.username,
        c.category_name,

        (
            SELECT COUNT(*)
            FROM likes
            WHERE project_id = p.id
        ) as total_likes,

        (
            SELECT COUNT(*)
            FROM likes
            WHERE project_id = p.id
            AND user_id = '$user_id'
        ) as user_liked

    FROM projects p

    JOIN users u
    ON u.id = p.user_id

    LEFT JOIN categories c
    ON c.id = p.category_id

    WHERE p.status = 'published'

    ORDER BY p.created_at DESC

");

?>

<div class="container-dashboard">

    <!-- SEARCH -->
    <div class="top-search-wrapper">

        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search projects...">
        </div>

        <div class="view-icons">
            <button id="gridViewBtn" class="active">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </button>

            <button id="listViewBtn">
                <i class="bi bi-list-ul"></i>
            </button>

        </div>

    </div>


    <div class="filter-section">

        <button class="active" data-category="all">
            All
        </button>

        <?php
        $categoryQuery = mysqli_query(
            $conn,
            "SELECT * FROM categories ORDER BY category_name ASC"
        );

        while ($cat = mysqli_fetch_assoc($categoryQuery)) :
        ?>

            <button
                data-category="<?= strtolower($cat['category_name']); ?>">

                <?= htmlspecialchars($cat['category_name']); ?>

            </button>

        <?php endwhile; ?>

    </div>


    <!-- FILTER BOTTOM -->
    <div class="filter-bottom">

        <div></div>

        <div class="showing-data" id="showingData">
            Showing 1-6 projects
        </div>

    </div>


    <!-- PROJECT GRID -->
    <div class="project-grid" id="projectGrid">

        <?php while ($p = mysqli_fetch_assoc($query)) : ?>

            <?php

            $tags = array_map(
                'trim',
                explode(',', $p['tech_stack'] ?? '')
            );

            $image = !empty($p['image'])
                ? 'uploads/thumbnails/' . $p['image']
                : 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=1200';

            ?>

            <div class="project-card" data-category="<?= strtolower($p['category_name'] ?? ''); ?>">

                <!-- IMAGE -->
                <div class="card-image-wrapper">

                    <img src="<?= $image; ?>" alt="thumbnail">

                </div>


                <!-- CONTENT -->
                <div class="project-content">

                    <h5>
                        <?= htmlspecialchars($p['title']); ?>
                    </h5>

                    <p>
                        <?= htmlspecialchars($p['description']); ?>
                    </p>


                    <!-- TAG -->
                    <div class="tags">

                        <?php foreach ($tags as $tag): ?>

                            <?php if (!empty($tag)): ?>

                                <span>
                                    <?= htmlspecialchars($tag); ?>
                                </span>

                            <?php endif; ?>

                        <?php endforeach; ?>

                    </div>
                    

                    <!-- FOOTER -->
                    <div class="project-footer">

                        <!-- USER -->
                        <div class="user-info">

                            <div class="user-avatar">
                                <?= strtoupper(substr($p['username'], 0, 1)); ?>
                            </div>

                            <small>
                                <?= htmlspecialchars($p['username']); ?>
                            </small>

                        </div>


                        <!-- STATS -->
                        <!-- STATS -->
                        <div class="stats">

                            <!-- LIKE -->
                            <button
                                class="like-btn <?= $p['user_liked'] ? 'liked' : ''; ?>"
                                data-id="<?= $p['id']; ?>">

                                <i class="
            bi
            <?= $p['user_liked']
                ? 'bi-heart-fill'
                : 'bi-heart'; ?>
        "></i>

                                <span class="like-count">
                                    <?= $p['total_likes']; ?>
                                </span>

                            </button>


                            <!-- DOWNLOAD -->
                            <button
                                class="download-btn"
                                data-id="<?= $p['id']; ?>"
                                data-file="<?= htmlspecialchars($p['zip_file']); ?>">

                                <i class="bi bi-download"></i>

                                <span class="download-count">
                                    <?= $p['download_count']; ?>
                                </span>

                            </button>


                            <!-- DEMO -->
                            <?php if (!empty($p['demo_link'])) : ?>

                                <a href="<?= htmlspecialchars($p['demo_link']); ?>"
                                    target="_blank"
                                    class="demo-btn">

                                    <i class="bi bi-box-arrow-up-right"></i>

                                </a>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

        <?php endwhile; ?>

    </div>


    <!-- PAGINATION -->
    <div class="pagination-wrapper mt-4 mb-5">

        <button id="prevPage" class="pagination-btn">
            Prev
        </button>

        <div id="paginationNumbers" class="d-flex gap-2"></div>

        <button id="nextPage" class="pagination-btn">
            Next
        </button>

    </div>

    <!-- MODAL DOWNLOAD -->
    <div class="modal fade" id="downloadModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-custom">

                <div class="modal-header border-0">
                    <h5 class="modal-title">Download Project</h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="mb-4">

                        <label class="form-label">
                            File ZIP
                        </label>

                        <input type="text"
                            id="zipFileName"
                            class="form-control input-custom"
                            readonly>

                    </div>

                    <div class="d-flex justify-content-end">

                        <!-- INI YANG PENTING -->
                        <button
                            type="button"
                            id="downloadZipBtn"
                            class="btn-download-zip">

                            <i class="bi bi-download"></i>
                            Download ZIP

                        </button>

                    </div>

                </div>

            </div>
        </div>
    </div>

</div>