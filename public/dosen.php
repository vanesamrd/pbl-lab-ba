<?php
require_once "../app/models/Dosen.php";
include "../views/layouts/header.php";
$dosenList = Dosen::all();
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

    /* Dosen Card */
    .dosen-card {
        border: none;
        border-radius: 1rem;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        height: 100%;
        border: 1px solid #f0f0f0;
        position: relative;
    }

    .dosen-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(10, 42, 67, 0.12);
        border-color: rgba(63, 162, 247, 0.3);
    }

    .img-wrapper {
        height: 280px; /* Fixed height for consistency */
        overflow: hidden;
        background-color: #f8f9fa;
        position: relative;
    }

    .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center; /* Focus on face */
        transition: transform 0.5s ease;
    }

    .dosen-card:hover .img-wrapper img {
        transform: scale(1.05);
    }

    .card-body {
        padding: 1.5rem;
        text-align: center;
        background: #fff;
    }

    .dosen-name {
        color: var(--primary-custom);
        font-weight: 700;
        font-size: 1.15rem;
        margin-bottom: 0.5rem;
    }

    .dosen-role {
        color: var(--accent);
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Modal Styling */
    .modal-content.rounded-4 {
        border-radius: 1.5rem !important;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .modal-header-custom {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 10;
    }

    .btn-close-custom {
        background-color: rgba(255,255,255,0.8);
        border-radius: 50%;
        padding: 0.8rem;
        backdrop-filter: blur(4px);
        transition: 0.2s;
    }
    
    .btn-close-custom:hover {
        background-color: #fff;
    }
</style>

<section class="py-5 min-vh-100">
    <div class="container">
        <div class="section-container">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary-custom mb-2">Tim Pengajar</h2>
                <p class="text-muted">Profil dosen dan staf ahli Laboratorium Business Analytics.</p>
                <div style="width: 60px; height: 4px; background: var(--accent); margin: 0 auto; border-radius: 2px;"></div>
            </div>

            <!-- Dosen Grid -->
            <div class="row g-4 justify-content-center">
                <?php foreach ($dosenList as $d): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="dosen-card" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#dosenModal<?= $d['id'] ?>">
                            <div class="img-wrapper">
                                <?php if (!empty($d['foto'])): ?>
                                    <img src="/lab-ba/public/uploads/dosen/<?= htmlspecialchars($d['foto']) ?>" alt="<?= htmlspecialchars($d['nama']) ?>">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                        <i class="bi bi-person-fill" style="font-size: 6rem; opacity: 0.2;"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Overlay Icon -->
                                <div class="position-absolute bottom-0 end-0 m-3">
                                    <div class="bg-white rounded-circle shadow-sm p-2 text-primary" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-info-lg"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <h5 class="dosen-name"><?= htmlspecialchars($d['nama']) ?></h5>
                                <p class="dosen-role"><?= htmlspecialchars($d['keahlian']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-5">
                <a href="index.php" class="btn btn-link text-muted text-decoration-none fw-bold">
                <a href="index.php" class="btn btn-accent w-150 mt-2 fw-bold rounded-pill">Kembali Ke Beranda</a>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Dosen Modals -->
<?php foreach ($dosenList as $d): ?>
    <div class="modal fade" id="dosenModal<?= $d['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 overflow-hidden">
                <div class="row g-0">
                    <!-- Modal Image (Left/Top) -->
                    <div class="col-lg-5 position-relative bg-light">
                        <?php if (!empty($d['foto'])): ?>
                            <img src="/lab-ba/public/uploads/dosen/<?= htmlspecialchars($d['foto']) ?>" class="w-100 h-100 object-fit-cover" style="min-height: 300px;">
                        <?php else: ?>
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted" style="min-height: 300px;">
                                <i class="bi bi-person-fill fs-1"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Modal Content (Right/Bottom) -->
                    <div class="col-lg-7 position-relative">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                        
                        <div class="p-4 p-md-5 h-100 d-flex flex-column justify-content-center">
                            <h3 class="fw-bold text-primary-custom mb-1"><?= htmlspecialchars($d['nama']) ?></h3>
                            <span class="badge bg-light text-primary border border-primary-subtle align-self-start mb-4 px-3 py-2 rounded-pill">
                                <?= htmlspecialchars($d['keahlian']) ?>
                            </span>
                            
                            <h6 class="text-uppercase text-muted small fw-bold mb-2">Profil Singkat</h6>
                            <p class="text-muted mb-0" style="line-height: 1.8;">
                                <?= !empty($d['deskripsi']) ? nl2br(htmlspecialchars($d['deskripsi'])) : '<em class="text-muted small">Belum ada deskripsi profil untuk dosen ini.</em>' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php include "../views/layouts/footer.php"; ?>