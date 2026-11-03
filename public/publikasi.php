<?php
require_once __DIR__ . '/../app/models/Publikasi.php';
require_once __DIR__ . '/../app/models/Dosen.php';
require_once __DIR__ . '/../app/models/Kategori.php';

// Get filters (BACKEND TIDAK DIUBAH)
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 12;
$offset = ($page - 1) * $limit;
$search = trim($_GET['search'] ?? '');
$dosen_id = isset($_GET['dosen']) ? intval($_GET['dosen']) : null;
$kategori_id = isset($_GET['kategori']) ? intval($_GET['kategori']) : null;

// Get data
$publikasi = Publikasi::all($limit, $offset, $search, $dosen_id, $kategori_id);
$total = Publikasi::count($search, $dosen_id, $kategori_id);
$dosenList = Dosen::all();
$kategoriList = Kategori::all();
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

    /* Layout Container */
    .section-container {
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 10px 40px rgba(10, 42, 67, 0.08);
        border: 1px solid rgba(63, 162, 247, 0.15);
        padding: 3rem;
        position: relative;
    }

    /* Filter Box */
    .filter-box {
        background-color: #f8fbff;
        border: 1px solid #e9ecef;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        padding: 0.7rem 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(63, 162, 247, 0.15);
    }

    /* Card Styling */
    .pub-card {
        border: 1px solid #f0f0f0;
        border-radius: 1rem;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        background: #fff;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .pub-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(10, 42, 67, 0.1);
        border-color: var(--accent);
    }

    .pub-title {
        color: var(--primary-custom);
        font-weight: 700;
        line-height: 1.4;
        font-size: 1.1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Limit to 2 lines */
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .pub-title:hover {
        color: var(--accent);
    }

    .badge-author {
        background-color: #e0f2fe;
        color: #0284c7;
        font-weight: 600;
        border: 1px solid #bae6fd;
    }
    
    .badge-category {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
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
    }

    .page-item.active .page-link {
        background-color: var(--accent);
        color: white;
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
        <!-- Header Section -->
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary-custom mb-2">Arsip Publikasi & Riset</h2>
            <p class="text-muted">Jelajahi hasil penelitian dan karya ilmiah dari Laboratorium Business Analytics.</p>
            <div style="width: 60px; height: 4px; background: var(--accent); margin: 0 auto; border-radius: 2px;"></div>
        </div>

        <!-- Filter Form -->
        <div class="filter-box">
            <form class="row g-3 align-items-center" method="GET">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Judul publikasi..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($kategoriList as $kat): ?>
                            <option value="<?= $kat['id'] ?>" <?= $kategori_id == $kat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($kat['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Peneliti / Dosen</label>
                    <select name="dosen" class="form-select">
                        <option value="">Semua Dosen</option>
                        <?php foreach ($dosenList as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= $dosen_id == $d['id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label d-none d-md-block">&nbsp;</label>
                    <button type="submit" class="btn btn-search rounded-pill w-100 py-2 shadow-sm">
                        Filter Data
                    </button>
                </div>
            </form>
        </div>

        <!-- Publication Grid -->
        <?php if (count($publikasi) === 0): ?>
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-journal-x text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                </div>
                <h5 class="text-muted">Tidak ada publikasi yang ditemukan.</h5>
                <p class="text-muted small">Coba ubah kata kunci atau reset filter pencarian Anda.</p>
                <a href="publikasi.php" class="btn btn-outline-secondary btn-sm rounded-pill px-4 mt-2">Reset Filter</a>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-5">
                <?php foreach ($publikasi as $pub): ?>
                    <div class="col">
                        <div class="card pub-card h-100">
                            <div class="card-body d-flex flex-column p-4">
                                <!-- Year Badge -->
                                <div class="mb-3 d-flex justify-content-between align-items-start">
                                    <span class="badge bg-light text-secondary border">
                                        <i class="bi bi-calendar3 me-1"></i> <?= date('Y', strtotime($pub['tanggal'])) ?>
                                    </span>
                                    <a href="publikasi_detail.php?id=<?= $pub['id'] ?>" class="text-muted small"><i class="bi bi-box-arrow-up-right"></i></a>
                                </div>

                                <!-- Title -->
                                <a href="publikasi_detail.php?id=<?= $pub['id'] ?>" class="text-decoration-none">
                                    <h5 class="pub-title" title="<?= htmlspecialchars($pub['judul']) ?>">
                                        <?= htmlspecialchars($pub['judul']) ?>
                                    </h5>
                                </a>

                                <!-- Authors -->
                                <div class="mb-3">
                                    <?php $dosen = Publikasi::getDosen($pub['id']); ?>
                                    <?php if (!empty($dosen)): ?>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php foreach ($dosen as $ds): ?>
                                                <a href="publikasi.php?dosen=<?= $ds['id'] ?>" class="badge badge-author text-decoration-none fw-normal rounded-pill px-2 py-1">
                                                    <i class="bi bi-person-fill me-1"></i><?= explode(' ', $ds['nama'])[0] ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">Tim Peneliti Lab</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Categories & Footer -->
                                <div class="mt-auto pt-3 border-top border-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php $kategori = Publikasi::getKategori($pub['id']); ?>
                                            <?php if ($kategori): ?>
                                                <?php foreach ($kategori as $kat): ?>
                                                    <a href="publikasi.php?kategori=<?= $kat['id'] ?>" class="badge badge-category text-decoration-none fw-normal rounded-1">
                                                        <?= htmlspecialchars($kat['nama']) ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="d-grid mt-3">
                                        <a href="publikasi_detail.php?id=<?= $pub['id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-5 border-top pt-4">
                <ul class="pagination justify-content-center">
                    <!-- Prev Button -->
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link rounded-pill px-3 w-auto" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                            <i class="bi bi-chevron-left me-1"></i> Prev
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                            <a class="page-link shadow-sm" href="?<?= http_build_query(array_merge($_GET, ['page' => $p])) ?>">
                                <?= $p ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next Button -->
                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link rounded-pill px-3 w-auto" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                            Next <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

        <!-- Back Button Area -->
        <div class="text-center mt-5">
            <a href="index.php" class="btn btn-link text-decoration-none text-muted fw-bold">
            <a href="index.php" class="btn btn-accent w-150 mt-2 fw-bold rounded-pill">Kembali Ke Beranda</a>
            </a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../views/layouts/footer.php'; ?>