<?php
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

$user_id  = $_SESSION['id'];
$username = $_SESSION['username'];
$profile_picture = $_SESSION['profile_picture'] ?? 'default.png';

// Fetch user's projects
$stmt = $conn->prepare("
    SELECT p.*, c.category_name
    FROM projects p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.user_id = ?
    ORDER BY p.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$projects = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>


<!-- CONTENT -->
<div class="container-dashboard">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <h2 class="page-title">My Projects</h2>
            <p class="page-subtitle">Manage and organize your creative work</p>
        </div>
    </div>


    <!-- PROJECT GRID -->
    <?php if (count($projects) === 0): ?>

        <div class="empty-state">
            <i class="bi bi-folder-x"></i>
            <h5>No projects yet</h5>
            <p>Start by adding your first project!</p>
        </div>

    <?php else: ?>

        <div class="project-grid" id="projectGrid">

            <?php foreach ($projects as $p):
                $tags       = array_map('trim', explode(',', $p['tech_stack'] ?? ''));
                $statusClass = $p['status'] === 'published' ? 'badge-published' : 'badge-draft';
                $statusLabel = $p['status'] === 'published' ? 'Published' : 'Draft';
                $imgSrc      = !empty($p['image']) ? 'uploads/thumbnails/' . htmlspecialchars($p['image']) : 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?q=80&w=1200&auto=format&fit=crop';
            ?>

                <div class="project-card">

                    <div class="card-image-wrapper">

                        <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($p['title']) ?>">

                        <!-- Status Badge -->
                        <span class="status-badge <?= $statusClass ?>"><?= $statusLabel ?></span>

                        <!-- Action Dropdown -->
                        <div class="card-menu">

                            <button
                                class="card-menu-btn menu-toggle"
                                type="button">

                                <i class="bi bi-three-dots-vertical"></i>

                            </button>

                            <div class="custom-dropdown">

                                <?php if ($p['status'] == 'published'): ?>

                                    <a
                                        class="dropdown-link"
                                        href="project_status.php?id=<?= $p['id'] ?>&status=private">

                                        <i class="bi bi-eye-slash text-warning"></i>
                                        Private

                                    </a>

                                <?php else: ?>

                                    <a
                                        class="dropdown-link"
                                        href="project_status.php?id=<?= $p['id'] ?>&status=published">

                                        <i class="bi bi-eye text-success"></i>
                                        Publish

                                    </a>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                    <div class="project-content">

                        <h5><?= htmlspecialchars($p['title']) ?></h5>

                        <p><?= htmlspecialchars($p['description']) ?></p>

                        <div class="tags">
                            <?php foreach ($tags as $tag): ?>
                                <?php if ($tag): ?>
                                    <span><?= htmlspecialchars($tag) ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <div class="project-footer">

                            <div class="stats">
                                <span><i class="bi bi-heart-fill text-danger"></i> <?= $p['likes_count'] ?></span>
                                <span><i class="bi bi-download text-primary"></i> <?= $p['download_count'] ?></span>
                            </div>

                            <div class="action-icons">
                                <button class="icon-btn edit-btn"
                                    onclick="openEditModal(
                                            <?= $p['id'] ?>,
                                            '<?= addslashes($p['title']) ?>',
                                            '<?= addslashes($p['description']) ?>',
                                            '<?= addslashes($p['tech_stack']) ?>',
                                            '<?= addslashes($p['demo_link'] ?? '') ?>'
                                        )">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <a href="proses_delete_project.php?id=<?= $p['id'] ?>"
                                    class="icon-btn delete-btn"
                                    onclick="return confirm('Yakin hapus project ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>


<!-- MODAL ADD PROJECT -->
<div class="modal fade" id="modalAddProject" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-custom">

            <div class="modal-header border-0">
                <h5 class="modal-title">Add New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="proses_add_project.php" method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label">Project Title</label>
                        <input type="text" name="title" class="form-control input-custom" placeholder="e.g. E-Commerce App" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control input-custom" rows="3" placeholder="Describe your project..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control input-custom" required>
                            <option value="">-- Select Category --</option>
                            <?php
                            $cats = $conn->query("SELECT * FROM categories ORDER BY category_name");
                            while ($cat = $cats->fetch_assoc()):
                            ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tech Stack <small class="text-muted">(comma separated)</small></label>
                        <input type="text" name="tech_stack" class="form-control input-custom" placeholder="e.g. React, Node.js, MySQL">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Image</label>
                            <input type="file" name="image" class="form-control input-custom" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Demo Link <small class="text-muted">(optional)</small></label>
                            <input type="url" name="demo_link" class="form-control input-custom" placeholder="https://...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control input-custom">
                            <option value="published">Published</option>
                            <option value="hidden">Draft</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-submit">Save Project</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>


<!-- MODAL EDIT PROJECT -->
<div class="modal fade" id="modalEditProject" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-custom">

            <div class="modal-header border-0">
                <h5 class="modal-title">Edit Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form action="proses_edit_project.php"
                    method="POST"
                    enctype="multipart/form-data">

                    <input type="hidden" name="id" id="editId">

                    <!-- TITLE -->
                    <div class="mb-3">

                        <label class="form-label">
                            Project Title
                        </label>

                        <input
                            type="text"
                            name="title"
                            id="editTitle"
                            class="form-control input-custom"
                            required>

                    </div>


                    <!-- DESCRIPTION -->
                    <div class="mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="editDescription"
                            class="form-control input-custom"
                            rows="4"
                            required></textarea>

                    </div>


                    <!-- TECH STACK -->
                    <div class="mb-3">

                        <label class="form-label">
                            Tech Stack
                            <small class="text-muted">
                                (comma separated)
                            </small>
                        </label>

                        <input
                            type="text"
                            name="tech_stack"
                            id="editTechStack"
                            class="form-control input-custom">

                    </div>


                    <!-- IMAGE + ZIP -->
                    <div class="row">

                        <!-- IMAGE -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Ganti Gambar
                                <small class="text-muted">
                                    (opsional)
                                </small>
                            </label>

                            <input
                                type="file"
                                name="image"
                                class="form-control input-custom"
                                accept="image/*">

                        </div>


                        <!-- ZIP -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Ganti File ZIP
                                <small class="text-muted">
                                    (opsional)
                                </small>
                            </label>

                            <input
                                type="file"
                                name="zip_file"
                                class="form-control input-custom"
                                accept=".zip">

                        </div>

                    </div>


                    <!-- DEMO LINK -->
                    <div class="mb-3">

                        <label class="form-label">
                            Demo Link
                        </label>

                        <input
                            type="url"
                            name="demo_link"
                            id="editDemoLink"
                            class="form-control input-custom"
                            placeholder="https://example.com">

                    </div>


                    <!-- BUTTON -->
                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <button
                            type="button"
                            class="btn btn-cancel"
                            data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="btn btn-submit">
                            Update Project
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</div>


<script>
    function openEditModal(
        id,
        title,
        description,
        techStack,
        demoLink
    ) {

        document.getElementById('editId').value = id;

        document.getElementById('editTitle').value = title;

        document.getElementById('editDescription').value =
            description;

        document.getElementById('editTechStack').value =
            techStack;

        document.getElementById('editDemoLink').value =
            demoLink;

        const modal = new bootstrap.Modal(
            document.getElementById('modalEditProject')
        );

        modal.show();

    }
</script>