<?php
require_once __DIR__ . '/../app/models/Publikasi.php';
require_once __DIR__ . '/../app/models/Dosen.php';
require_once __DIR__ . '/../app/models/Kategori.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pub = Publikasi::find($id);
if (!$pub) {
    include __DIR__ . '/../views/layouts/header.php';
    echo '<main class="container py-5"><div class="alert alert-danger shadow-sm border-0 rounded-3"><i class="bi bi-exclamation-triangle-fill me-2"></i>Publikasi tidak ditemukan.</div><div class="mt-3"><a href="publikasi.php" class="btn btn-outline-primary rounded-pill">Kembali ke Daftar</a></div></main>';
    include __DIR__ . '/../views/layouts/footer.php';
    exit;
}
$dosen = Publikasi::getDosen($id);
$kategori = Publikasi::getKategori($id);

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

    /* Breadcrumb Custom */
    .breadcrumb-item a {
        color: var(--accent);
        text-decoration: none;
        font-weight: 500;
    }
    .breadcrumb-item.active {
        color: #6c757d;
    }

    /* Main Container Card */
    .detail-container {
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 10px 40px rgba(10, 42, 67, 0.08);
        border: 1px solid rgba(63, 162, 247, 0.1);
        padding: 3rem;
        position: relative;
    }

    /* Typography */
    .pub-title-detail {
        color: var(--primary-custom);
        font-weight: 800;
        font-size: 2rem;
        line-height: 1.3;
    }

    .meta-text {
        font-size: 0.95rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Author Chip */
    .author-chip {
        display: inline-flex;
        align-items: center;
        background-color: #f8fbff;
        border: 1px solid #e0f2fe;
        color: #0c4a6e;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
        text-decoration: none;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .author-chip:hover {
        background-color: #e0f2fe;
        color: #0284c7;
        transform: translateY(-2px);
    }

    .author-chip i {
        color: var(--accent);
        margin-right: 6px;
    }

    /* Content Box */
    .abstract-box {
        background-color: #fcfcfc;
        border-left: 4px solid var(--accent);
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin: 2rem 0;
        color: #495057;
        line-height: 1.8;
        font-size: 1.05rem;
    }

    /* Buttons */
    .btn-action {
        padding: 0.7rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-download {
        background-color: #198754;
        color: white;
        border: none;
        box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);
    }
    .btn-download:hover {
        background-color: #157347;
        color: white;
        transform: translateY(-2px);
    }

    .btn-sinta {
        background-color: var(--primary-custom);
        color: white;
        border: none;
        box-shadow: 0 4px 10px rgba(10, 42, 67, 0.3);
    }
    .btn-sinta:hover {
        background-color: #0f3d61;
        color: white;
        transform: translateY(-2px);
    }
</style>

<main class="container py-5 min-vh-100">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4 ps-1">
            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="publikasi.php">Publikasi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Riset</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="detail-container">
                <!-- Header: Date & Category -->
                <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                    <div class="meta-text">
                        <i class="bi bi-calendar-event"></i>
                        <?= date('d F Y', strtotime($pub['tanggal'])) ?>
                    </div>
                    <div class="vr mx-1 d-none d-md-block"></div>
                    <div>
                        <?php if ($kategori): ?>
                            <?php foreach ($kategori as $kat): ?>
                                <span class="badge bg-light text-secondary border fw-normal px-3 py-2 rounded-pill">
                                    <i class="bi bi-tag me-1"></i><?= htmlspecialchars($kat['nama']) ?>
                                </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="badge bg-light text-muted border fw-normal px-3 py-2 rounded-pill">Uncategorized</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Title -->
                <h1 class="pub-title-detail mb-4"><?= htmlspecialchars($pub['judul']) ?></h1>

                <!-- Authors -->
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted fw-bold small mb-2" style="letter-spacing: 1px;">Peneliti / Penulis</h6>
                    <div class="d-flex flex-wrap">
                        <?php foreach ($dosen as $ds): ?>
                            <a href="dosen.php?id=<?= $ds['id'] ?>" class="author-chip">
                                <i class="bi bi-person-circle fs-5"></i>
                                <?= htmlspecialchars($ds['nama']) ?>
                            </a>
                        <?php endforeach; ?>
                        <?php if (empty($dosen)): ?>
                            <span class="text-muted fst-italic ms-1">Tim Laboratorium</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Abstract / Description -->
                <div class="mt-5">
                    <h5 class="fw-bold text-primary-custom mb-0"><i class="bi bi-file-text me-2"></i>Abstrak / Deskripsi</h5>
                    <div class="abstract-box">
                        <?= nl2br(htmlspecialchars($pub['deskripsi'])) ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top">
                    <?php if ($pub['file']): ?>
                        <a href="/lab-ba/public/uploads/publikasi/<?= htmlspecialchars($pub['file']) ?>" target="_blank" class="btn-action btn-download">
                            <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download Full PDF
                        </a>
                    <?php endif; ?>

                    <?php if ($pub['link']): ?>
                        <a href="<?= htmlspecialchars($pub['link']) ?>" target="_blank" class="btn-action btn-sinta">
                            <i class="bi bi-box-arrow-up-right me-2"></i>Lihat di Sumber (SINTA/Jurnal)
                        </a>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Back Button -->
            <div class="text-center mt-5">
                <a href="publikasi.php" class="btn btn-link text-muted text-decoration-none fw-bold">
                <a href="index.php" class="btn btn-accent w-150 mt-2 fw-bold rounded-pill">Kembali Ke Beranda</a>
                </a>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../views/layouts/footer.php'; ?>