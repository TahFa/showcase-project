<?php
session_start();
require_once 'koneksi.php';

$isLogin = isset($_SESSION['id']);

// AMBIL PROJECT PUBLISHED
$query = mysqli_query($conn, "
    SELECT *
    FROM projects
    WHERE status = 'published'
    ORDER BY created_at DESC
    LIMIT 10
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Informatics Project Showcase</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style-public.css">

</head>

<body>

    <!-- NAVBAR -->
    <header class="navbar-custom">
        <div class="container nav-container">

            <div class="logo">
                INFOSHOWCASE
            </div>

            <nav>

                <a href="#home">Home</a>
                <a href="#projects">Projects</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>

                <a href="user/login.php" class="btn-login">
                    Login
                </a>

            </nav>

        </div>
    </header>


    <!-- HERO SECTION -->
    <section class="hero" id="home">

        <div id="particles-js"></div>

        <div class="hero-content">

            <h1>
                Platform Showcase <br>
                <span id="typing"></span>
            </h1>

            <p>
                Tempat mahasiswa informatika menampilkan karya terbaik mereka
                dari web development, AI, IoT, hingga mobile apps.
            </p>

            <div class="hero-buttons">

                <a
                    href="<?= $isLogin
                                ? 'user/dashboard.php?menu=dashboard'
                                : 'user/login.php' ?>"
                    class="btn-main">

                    Explore Projects

                </a>

                <a
                    href="<?= $isLogin
                                ? 'user/dashboard.php?menu=new_project'
                                : 'user/login.php' ?>"
                    class="btn-outline">

                    Upload Project

                </a>

            </div>

        </div>

    </section>


    <!-- PROJECT SECTION -->
    <section class="projects" id="projects">

        <div class="projects-overlay"></div>

        <div class="container-fluid project-content">

            <h2 class="section-title">Project Showcase</h2>

            <div class="coverflow">

                <button class="nav prev">&#10094;</button>

                <div class="coverflow-track">

                    <?php while ($project = mysqli_fetch_assoc($query)): ?>

                        <?php
                        $image = !empty($project['image'])
                            ? 'user/uploads/thumbnails/' . $project['image']
                            : 'https://images.unsplash.com/photo-1498050108023-c5249f4df085';

                        $projectUrl = $isLogin
                            ? 'user/dashboard.php?menu=dashboard&search=' . urlencode($project['title'])
                            : 'user/login.php';
                        ?>

                        <a
                            href="<?= $projectUrl ?>"
                            class="cover-card text-decoration-none text-dark">

                            <img src="<?= $image ?>">

                            <h4>
                                <?= htmlspecialchars($project['title']) ?>
                            </h4>

                        </a>

                    <?php endwhile; ?>

                </div>

                <button class="nav next">&#10095;</button>

            </div>

        </div>

    </section>


    <!-- ABOUT -->
    <section class="about" id="about">

        <div class="container about-container">

            <div class="about-text">

                <h2>
                    Tentang Platform
                </h2>

                <p>

                    Platform ini dibuat untuk menampilkan berbagai project mahasiswa
                    informatika seperti website, aplikasi, AI, dan teknologi lainnya
                    agar dapat dilihat oleh mahasiswa, dosen, maupun industri.

                </p>

            </div>

            <div class="about-image">

                <img src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b">

            </div>

        </div>

    </section>


    <section class="contact" id="contact">

        <div class="contact-content">

            <h2>Let's Connect 🚀</h2>
            <p>Kalau tertarik dengan project kami atau ingin bekerja sama, hubungi kami di bawah ini</p>

            <div class="contact-grid">

                <a href="mailto:kelompok1pweb@gmail.com" target="_blank" class="contact-card email">
                    <div class="icon"><i class="bi bi-envelope-fill"></i></div>
                    <h4>Email</h4>
                    <span>kelompok1pweb@gmail.com</span>
                </a>

                <a href="https://wa.me/6285603542114" target="_blank" class="contact-card whatsapp">
                    <div class="icon"><i class="bi bi-whatsapp"></i></div>
                    <h4>WhatsApp</h4>
                    <span>+62 856-0354-2114</span>
                </a>

                <a href="https://instagram.com/fattaah11" target="_blank" class="contact-card instagram">
                    <div class="icon"><i class="bi bi-instagram"></i></div>
                    <h4>Instagram</h4>
                    <span>@kelompok1PWEB</span>
                </a>

                <a href="https://github.com/Kelompok1-pweb" target="_blank" class="contact-card github">
                    <div class="icon"><i class="bi bi-github"></i></div>
                    <h4>GitHub</h4>
                    <span>github.com/Kelompok1-pweb</span>
                </a>

            </div>

        </div>

    </section>


    <!-- FOOTER -->
    <footer>

        <div class="container footer-content">

            <h3>Informatics Showcase</h3>

            <p>
                Platform berbagi project mahasiswa informatika.
            </p>

            <p class="copyright">
                © 2026 Group 1 Informatics A.
            </p>

        </div>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
    <script src="js/hero.js"></script>
    <script src="js/projects.js"></script>
    <script src="js/about.js"></script>
    <script src="js/contact.js"></script>
</body>

</html>