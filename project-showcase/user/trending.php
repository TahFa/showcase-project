<?php
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

$username = $_SESSION['username'];
$current  = basename($_SERVER['PHP_SELF']);

// Ambil top 5 trending berdasarkan likes_count + download_count
$stmt = $conn->prepare("
    SELECT p.*, u.username AS author, c.category_name
    FROM projects p
    LEFT JOIN users u ON p.user_id = u.id
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'published'
    ORDER BY (p.likes_count + p.download_count) DESC
    LIMIT 5
");
$stmt->execute();
$result   = $stmt->get_result();
$trendings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Semua project untuk grid discover style (bawah)
$all = $conn->query("
    SELECT p.*, u.username AS author, c.category_name
    FROM projects p
    LEFT JOIN users u ON p.user_id = u.id
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'published'
    ORDER BY (p.likes_count + p.download_count) DESC
    LIMIT 9
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trending - ProjectHub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style-dashboard.css">
    <link rel="stylesheet" href="css/style-trending.css">
</head>

<body>
    <!-- CONTENT -->
    <div class="container-dashboard">

        <!-- PAGE HEADER -->
        <div class="page-header">
            <div>
                <h2 class="page-title">
                    <i class="bi bi-fire text-warning me-2"></i>Trending
                </h2>
                <p class="page-subtitle">Most liked and viewed projects right now</p>
            </div>
        </div>


        <!-- TOP 5 TRENDING LIST -->
        <div class="trending-list">

            <?php foreach ($trendings as $rank => $p):
                $tags   = array_map('trim', explode(',', $p['tech_stack'] ?? ''));
                $imgSrc = !empty($p['image'])
                    ? 'uploads/thumbnails/' . htmlspecialchars($p['image'])
                    : 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800&auto=format&fit=crop';
                $rankNum = $rank + 1;
                $rankClass = $rankNum <= 3 ? 'rank-top' : 'rank-normal';
            ?>

                <div class="trending-card">

                    <!-- Rank Number -->
                    <div class="rank-badge <?= $rankClass ?>">
                        <?php if ($rankNum === 1): ?>
                            <i class="bi bi-trophy-fill"></i>
                        <?php else: ?>
                            <?= $rankNum ?>
                        <?php endif; ?>
                    </div>

                    <!-- Image -->
                    <div class="trending-img-wrap">
                        <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($p['title']) ?>">
                    </div>

                    <!-- Info -->
                    <div class="trending-info">

                        <div class="trending-meta">
                            <span class="trending-category"><?= htmlspecialchars($p['category_name'] ?? 'General') ?></span>
                        </div>

                        <h5 class="trending-title"><?= htmlspecialchars($p['title']) ?></h5>
                        <p class="trending-desc"><?= htmlspecialchars($p['description']) ?></p>

                        <div class="trending-tags">
                            <?php foreach (array_slice($tags, 0, 3) as $tag): ?>
                                <?php if ($tag): ?>
                                    <span><?= htmlspecialchars($tag) ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                    </div>

                    <!-- Author + Stats -->
                    <div class="trending-right">

                        <div class="trending-author">
                            <div class="user-avatar"><?= strtoupper(substr($p['author'], 0, 1)) ?></div>
                            <small><?= htmlspecialchars($p['author']) ?></small>
                        </div>

                        <div class="trending-stats">
                            <div class="stat-item">
                                <i class="bi bi-heart-fill text-danger"></i>
                                <span><?= number_format($p['likes_count']) ?></span>
                            </div>
                            <div class="stat-item">
                                <i class="bi bi-download text-primary"></i>
                                <span><?= number_format($p['download_count']) ?></span>
                            </div>
                            <div class="stat-score">
                                <i class="bi bi-graph-up-arrow"></i>
                                <?= number_format($p['likes_count'] + $p['download_count']) ?>
                            </div>
                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

            <?php if (empty($trendings)): ?>
                <div class="empty-trending">
                    <i class="bi bi-bar-chart-line"></i>
                    <p>Belum ada data trending.</p>
                </div>
            <?php endif; ?>

        </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>