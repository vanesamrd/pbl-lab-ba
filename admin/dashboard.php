<?php
require_once "../app/utils/session.php";
checkAdminLogin();
?>

<?php include "../views/layouts/header.php"; ?>

<style>
    :root {
        --primary-custom: #0A2A43;
        --accent: #3FA2F7;
        --accent-hover: #217bc9;
        --bg-light: #f4f7fa;
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Segoe UI', Roboto, sans-serif;
    }

    /* Welcome Banner */
    .hero-banner {
        background: linear-gradient(120deg, var(--primary-custom) 0%, #1a4568 100%);
        border-radius: 1.5rem;
        padding: 3rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(10, 42, 67, 0.2);
        margin-bottom: 2.5rem;
    }

    .hero-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .maskot-dashboard {
        max-width: 120px;
        filter: drop-shadow(0 5px 15px rgba(0,0,0,0.3));
        animation: float 4s ease-in-out infinite;
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }

    /* Menu Grid Styling */
    .menu-card {
        background: #fff;
        border: 1px solid rgba(0,0,0,0.04);
        border-radius: 1.25rem;
        padding: 2rem 1.5rem;
        height: 100%;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        overflow: hidden;
        text-decoration: none !important;
    }

    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(63, 162, 247, 0.15);
        border-color: rgba(63, 162, 247, 0.3);
    }

    /* Icon Box */
    .icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background-color: #f0f7ff;
        color: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.25rem;
        transition: all 0.3s ease;
    }

    .menu-card:hover .icon-wrapper {
        background-color: var(--accent);
        color: #fff;
        transform: scale(1.1);
        box-shadow: 0 8px 20px rgba(63, 162, 247, 0.4);
    }

    .menu-title {
        color: var(--primary-custom);
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        transition: color 0.3s;
    }

    .menu-desc {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .menu-card:hover .menu-title {
        color: var(--accent);
    }

</style>

<section class="py-4 min-vh-100">
    <div class="container">

        <!-- Welcome Banner -->
        <div class="hero-banner d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
            <div class="hero-content text-center text-md-start">
                <span class="badge bg-white text-primary fw-bold mb-2 px-3 py-2 rounded-pill bg-opacity-10 text-opacity-100 border border-white border-opacity-25">
                    <i class="bi bi-shield-lock-fill me-1"></i> Administrator Area
                </span>
                <h1 class="fw-bold mb-2">Halo, <?= htmlspecialchars($_SESSION['admin_username']); ?>! 👋</h1>
                <p class="mb-0 text-white-50 fs-5" style="max-width: 600px;">
                    Selamat datang di panel kontrol <b>Laboratorium Business Analytics</b>. Kelola konten dan data sistem dengan mudah di sini.
                </p>
            </div>
            <div class="d-none d-md-block">
                <img src="../public/assets/img/maskot.png" alt="Maskot" class="maskot-dashboard">
            </div>
        </div>

        <!-- Section Title -->
        <div class="d-flex align-items-center justify-content-between mb-4 mt-5">
            <h4 class="fw-bold text-primary-custom mb-0"><i class="bi bi-grid-fill me-2 text-accent"></i>Menu Utama</h4>
            <div class="text-muted small"><?= date('l, d F Y') ?></div>
        </div>

        <!-- Menu Grid -->
        <div class="row g-4">
            <?php if (isset($adminMenus)): ?>
                <?php foreach ($adminMenus as $menu): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="/lab-ba/admin/<?= $menu['file'] ?>" class="menu-card">
                            <div class="icon-wrapper">
                                <i class="bi <?= $menu['icon'] ?>"></i>
                            </div>
                            <h5 class="menu-title"><?= $menu['label'] ?></h5>
                            <?php if (!empty($menu['desc'])): ?>
                                <p class="menu-desc mb-0"><?= $menu['desc'] ?></p>
                            <?php else: ?>
                                <p class="menu-desc mb-0">Kelola data <?= strtolower($menu['label']) ?></p>
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Konfigurasi menu admin tidak ditemukan. Silakan periksa file konfigurasi.
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php include "../views/layouts/footer.php"; ?>