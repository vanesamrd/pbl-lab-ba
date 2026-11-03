<?php include "../views/layouts/header.php"; ?>
<?php require_once "../app/models/Galeri.php"; ?>

<section class="bg-white py-5 min-vh-100">
    <div class="container">
        <div class="section-container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-1 text-primary-custom">Galeri Kegiatan Laboratorium</h2>
                <div class="text-muted">Dokumentasi aktivitas, event, dan suasana Laboratorium Business Analytics</div>
            </div>
            <div class="row g-4">
                <?php $galeriList = Galeri::all();
            foreach ($galeriList as $g): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0 galeri-card" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#galeriModal<?= $g['id'] ?>">
                        <img src="/lab-ba/public/uploads/galeri/<?= htmlspecialchars($g['gambar']) ?>" class="card-img-top" alt="<?= htmlspecialchars($g['judul']) ?>" style="object-fit:cover;max-height:240px;">
                        <div class="card-body">
                            <h5 class="card-title mb-1 text-primary-custom"><?= htmlspecialchars($g['judul']) ?></h5>
                            <div class="text-muted small mb-2">Tanggal: <?= htmlspecialchars($g['tanggal'] ?? '-') ?></div>
                            <div class="card-text text-truncate" style="max-width:100%;">
                                <?= nl2br(htmlspecialchars(mb_strimwidth($g['deskripsi'] ?? '', 0, 80, '...'))) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Galeri Modals -->
        <?php foreach ($galeriList as $g): ?>
            <div class="modal fade" id="galeriModal<?= $g['id'] ?>" tabindex="-1" aria-labelledby="galeriModalLabel<?= $g['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content rounded-4 overflow-hidden shadow-lg border-0" style="background:rgba(255,255,255,0.98);">
                        <div class="position-relative">
                            <img src="/lab-ba/public/uploads/galeri/<?= htmlspecialchars($g['gambar']) ?>" class="w-100" style="max-height:340px;object-fit:cover;object-position:center;">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3 bg-white rounded-circle p-2" data-bs-dismiss="modal" aria-label="Close" style="z-index:2;"></button>
                        </div>
                        <div class="p-4 p-md-5 text-center">
                            <h3 class="fw-bold mb-2 text-primary-custom" id="galeriModalLabel<?= $g['id'] ?>">
                                <i class="bi bi-image me-2 text-accent"></i><?= htmlspecialchars($g['judul']) ?>
                            </h3>
                            <div class="mb-3 text-muted small"><i class="bi bi-calendar-event me-1"></i> <?= htmlspecialchars($g['tanggal'] ?? '-') ?></div>
                            <div class="mb-3 fs-5 px-2 py-3 rounded-3" style="background:rgba(63,162,247,0.07);display:inline-block;min-width:180px;">
                                <i class="bi bi-chat-left-text me-2 text-accent"></i><?= nl2br(htmlspecialchars($g['deskripsi'] ?? '')) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <a href="index.php" class="btn btn-accent w-150 mt-2 fw-bold rounded-pill">Kembali Ke Beranda</a>
            
        <div class="text-center mt-3">
    </div>
    </div>

</section>

<style>
    .section-container {
        /* Bayangan awal yang timbul */
        box-shadow: 0 4px 12px rgba(10, 42, 67, 0.08), 0 1px 4px rgba(63, 162, 247, 0.05);
        /* Border Light Blue Tipis */
        border: 1px solid rgba(63, 162, 247, 0.2) !important;
        /* Membulatkan sudut */
        border-radius: 1.5rem; 
        padding: 3rem !important; /* Tambah padding agar konten tidak mepet border */
        background-color: #fff;
        transition: all 0.3s ease;
    }

    /* Efek hover pada Container (opsional, untuk tampilan lebih menarik) */
    .section-container:hover {
        box-shadow: 0 8px 18px rgba(10, 42, 67, 0.1), 0 2px 6px rgba(63, 162, 247, 0.1);
    }

    .text-primary-custom {
        color: #0A2A43 !important;
    }

    .galeri-card:hover {
        box-shadow: 0 8px 32px 0 rgba(10, 42, 67, 0.13), 0 1.5px 8px 0 rgba(63, 162, 247, 0.10);
        transform: translateY(-2px) scale(1.01);
        transition: box-shadow .2s, transform .2s;
    }

    .modal-content.rounded-4 {
        border-radius: 1.5rem !important;
        box-shadow: 0 8px 40px 0 rgba(10, 42, 67, 0.18), 0 1.5px 8px 0 rgba(63, 162, 247, 0.10);
        animation: galeriPop .4s cubic-bezier(.4, 2, .6, 1) both;
    }

    .text-accent {
        color: #3FA2F7 !important;
    }

    .modal-content img.w-100 {
        border-bottom-left-radius: 1.5rem;
        border-bottom-right-radius: 1.5rem;
    }

    @media (max-width: 767.98px) {
        .modal-content.rounded-4 {
            border-radius: 1rem !important;
        }

        .modal-content img.w-100 {
            border-bottom-left-radius: 1rem;
            border-bottom-right-radius: 1rem;
            max-height: 180px;
        }

        .p-md-5 {
            padding: 1.5rem !important;
        }
    }

    .btn-return {
    display: inline-block;
    padding: 0.7rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    background-image: linear-gradient(135deg, rgb(255, 0 , 0), rgb(192, 20, 20));
    color: #fff;
    box-shadow: 0 10px 25px rgba(16, 75, 140, 0.25);
    transition: 0.3s ease;
}

    @keyframes galeriPop {
        0% {
            transform: scale(.85) translateY(40px);
            opacity: 0;
        }

        100% {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }
</style>

<?php include "../views/layouts/footer.php"; ?>