<?php
require_once __DIR__ . '/../app/models/Berita.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$b = Berita::find($id);
if (!$b) {
    include __DIR__ . '/../views/layouts/header.php';
    echo '<main class="container py-5"><div class="alert alert-danger">Berita tidak ditemukan.</div></main>';
    include __DIR__ . '/../views/layouts/footer.php';
    exit;
}
include __DIR__ . '/../views/layouts/header.php';
?>
<main class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="berita.php">Berita</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm mb-4">
                <?php if ($b['gambar']): ?>
                    <img src="/lab-ba/public/uploads/berita/<?= htmlspecialchars($b['gambar']) ?>" alt="Gambar Berita" class="w-100 mb-3 rounded" style="max-height:320px;object-fit:cover;">
                <?php endif; ?>
                <div class="card-body">
                    <h2 class="fw-bold text-primary-custom mb-3"><?= htmlspecialchars($b['judul']) ?></h2>
                    <div class="mb-2 text-muted small">
                        <?= date('d F Y', strtotime($b['tanggal'])) ?> | <?= htmlspecialchars($b['penulis']) ?>
                    </div>
                    <div class="mb-3">
                        <?= nl2br(htmlspecialchars($b['isi'])) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../views/layouts/footer.php'; ?>