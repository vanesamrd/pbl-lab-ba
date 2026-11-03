<?php
require_once __DIR__ . '/../app/models/Berita.php';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 6;
$offset = ($page - 1) * $limit;
$search = trim($_GET['search'] ?? '');
$berita = Berita::all($limit, $offset, $search);
$total = Berita::count($search);
$totalPages = ceil($total / $limit);

include __DIR__ . '/../views/layouts/header.php';
?>

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

    /* Container Style */
    .section-container {
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 10px 40px rgba(10, 42, 67, 0.08);
        border: 1px solid rgba(63, 162, 247, 0.1);
        padding: 3rem;
        position: relative;
    }

    /* Search Box */
    .search-wrapper {
        background-color: #f8fbff;
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid #e9ecef;
    }

    /* News Card */
    .news-card {
        border: none;
        border-radius: 1rem;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        height: 100%;
        border: 1px solid #f0f0f0;
    }

    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(10, 42, 67, 0.1);
        border-color: rgba(63, 162, 247, 0.3);
    }

    /* Image Effect */
    .img-wrapper {
        overflow: hidden;
        position: relative;
        height: 200px;
    }

    .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .news-card:hover .img-wrapper img {
        transform: scale(1.05);
    }

    .date-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(4px);
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--primary-custom);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        z-index: 2;
    }

    /* Text Styling */
    .news-title {
        color: var(--primary-custom);
        font-weight: 700;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 0.8rem;
        font-size: 1.15rem;
    }

    .news-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s;
    }

    .news-title a:hover {
        color: var(--accent);
    }

    .news-excerpt {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Pagination */
    .page-link {
        color: var(--primary-custom);
        border: none;
        margin: 0 3px;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        transition: all 0.2s;
    }

    .page-item.active .page-link {
        background-color: var(--accent);
        color: white;
        box-shadow: 0 4px 10px rgba(63, 162, 247, 0.3);
    }

    .btn-search {
        background: linear-gradient(135deg, #3FA2F7, #007bff);
        border: none;
        color: white;
        font-weight: 600;
    }
    
    .btn-search:hover {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        transform: translateY(-1px);
    }
</style>

<main class="container py-5 min-vh-100">
    <div class="section-container">
        <!-- Header -->
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary-custom mb-2">Berita & Informasi</h2>
            <p class="text-muted">Update terbaru seputar kegiatan dan pengumuman laboratorium.</p>
            <div style="width: 60px; height: 4px; background: var(--accent); margin: 0 auto; border-radius: 2px;"></div>
        </div>

        <!-- Search Bar -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="search-wrapper">
                    <form class="row g-2 align-items-center" method="GET">
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0 py-2" placeholder="Cari judul berita..." value="<?= htmlspecialchars($search) ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-search rounded-pill w-100 py-2 shadow-sm">
                                Cari Berita
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- News Grid -->
        <?php if (count($berita) === 0): ?>
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-newspaper text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                </div>
                <h5 class="text-muted">Tidak ada berita ditemukan.</h5>
                <p class="text-muted small">Coba kata kunci lain atau reset pencarian Anda.</p>
                <a href="berita.php" class="btn btn-outline-secondary btn-sm rounded-pill px-4 mt-2">Lihat Semua</a>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
                <?php foreach ($berita as $b): ?>
                    <div class="col">
                        <div class="card news-card h-100">
                            <!-- Image Area -->
                            <div class="img-wrapper">
                                <div class="date-badge">
                                    <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($b['tanggal'])) ?>
                                </div>
                                <?php if ($b['gambar']): ?>
                                    <img src="/lab-ba/public/uploads/berita/<?= htmlspecialchars($b['gambar']) ?>" alt="Thumbnail">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">
                                        <i class="bi bi-image fs-1 opacity-25"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Content Area -->
                            <div class="card-body d-flex flex-column p-4">
                                <h5 class="news-title">
                                    <a href="berita_detail.php?id=<?= $b['id'] ?>">
                                        <?= htmlspecialchars($b['judul']) ?>
                                    </a>
                                </h5>
                                <div class="news-excerpt mb-4">
                                    <?= htmlspecialchars(mb_strimwidth(strip_tags($b['isi']), 0, 120, '...')) ?>
                                </div>
                                
                                <div class="mt-auto">
                                    <a href="berita_detail.php?id=<?= $b['id'] ?>" class="btn btn-outline-primary rounded-pill btn-sm fw-bold px-4">
                                        Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-4 border-top pt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link rounded-pill px-3 w-auto" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                            <a class="page-link shadow-sm" href="?<?= http_build_query(array_merge($_GET, ['page' => $p])) ?>">
                                <?= $p ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link rounded-pill px-3 w-auto" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                             <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

        <!-- Footer Action -->
        <div class="text-center mt-5">
            <a href="index.php" class="btn btn-link text-decoration-none text-muted fw-bold">
            <a href="index.php" class="btn btn-accent w-150 mt-2 fw-bold rounded-pill">Kembali Ke Beranda</a>
            </a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../views/layouts/footer.php'; ?>