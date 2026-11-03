<?php
require_once "../app/models/Profil.php";
require_once "../app/models/Galeri.php";
require_once "../app/models/Dosen.php";
require_once "../app/models/KontakLab.php";
require_once "../app/models/Publikasi.php";
require_once "../app/models/Berita.php";
include "../views/layouts/header.php";
?>

<style>
    /* --- CSS GLOBAL VARIABLES --- */
    :root {
        --primary-color: #0A2A43;
        --accent-color: #3FA2F7;
        --accent-hover: #217bc9;
        --bg-light: #f4f7fa;
    }

    /* --- SECTION STYLING --- */
    .section-padding {
        padding-top: 5rem;
        padding-bottom: 5rem;
    }

    /* Style Judul di Luar Card */
    .section-title-wrapper {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .section-title {
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        font-size: 2.2rem;
    }

    .section-divider {
        width: 80px;
        height: 5px;
        background: var(--accent-color);
        margin: 0 auto;
        border-radius: 10px;
    }

    /* Style Card Pembungkus Konten */
    .section-card {
        background: #fff;
        border-radius: 1.5rem;
        padding: 3rem;
        box-shadow: 0 10px 40px rgba(10, 42, 67, 0.08);
        /* Soft Shadow */
        border: 1px solid rgba(63, 162, 247, 0.15);
        /* Thin Border */
        position: relative;
        overflow: hidden;
    }

    /* --- COMPONENT STYLES --- */

    /* Hero */
    .hero-maskot-img {
        max-width: 530px;
        width: 100%;
        height: auto;
        filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.15));
        transition: transform 0.3s cubic-bezier(.4, 2, .6, 1);
    }

    .hero-maskot-img:hover {
        transform: scale(1.02) rotate(-2deg);
    }

    /* Buttons */
    .btn-accent {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
        color: #fff;
        transition: all 0.3s;
    }

    .btn-accent:hover {
        background-color: #007bff;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(63, 162, 247, 0.4);
    }

    /* Profil Items */
    .feature-item {
        padding: 1.5rem;
        border-radius: 1rem;
        transition: all 0.3s;
        height: 100%;
        background-color: #fff;
        /* White inside grey card area if needed, or transparent */
    }

    .feature-item:hover {
        transform: translateY(-5px);
    }

    .feature-icon-box {
        width: 60px;
        height: 60px;
        background: rgba(63, 162, 247, 0.1);
        color: var(--accent-color);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 1rem;
    }

    /* Dosen Cards */
    .dosen-card-simple {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 1rem;
        overflow: hidden;
        transition: 0.3s;
    }

    .dosen-card-simple:hover {
        border-color: var(--accent-color);
        box-shadow: 0 5px 15px rgba(63, 162, 247, 0.1);
    }

    /* Publikasi Items */
    .publikasi-item {
        border-left: 4px solid transparent;
        transition: 0.3s;
    }

    .publikasi-item:hover {
        border-left-color: var(--accent-color);
        background-color: #f8fbff !important;
    }

    /* Berita Cards */
    .berita-card {
        border: none;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        transition: 0.3s;
        height: 100%;
        border: 1px solid #f0f0f0;
    }

    .berita-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- 1. HERO SECTION (Full Width) -->
<section id="hero" class="text-white position-relative" style="background: linear-gradient(120deg, #0A2A43 60%, #3FA2F7 100%); min-height: 85vh; display: flex; align-items: center; overflow:hidden; padding-top: 80px;">
    <!-- SVG Shape -->
    <svg viewBox="0 0 1440 320" style="position:absolute;bottom:0;left:0;width:100%;z-index:0;opacity:0.1;pointer-events:none;">
        <path fill="#fff" fill-opacity="1" d="M0,224L60,202.7C120,181,240,139,360,144C480,149,600,203,720,197.3C840,192,960,128,1080,117.3C1200,107,1320,149,1380,170.7L1440,192L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
    </svg>
    <div class="container position-relative" style="z-index:1;">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-6 mb-5 mb-lg-0 text-lg-start text-center">
                <h1 class="display-3 fw-bold mb-3" style="letter-spacing:-1px; line-height:1.1;">Transforming Data<br><span class="text-accent" style="color: #8ecae6 !important;">into Decisions</span></h1>
                <p class="lead mb-4 text-white-50">Laboratorium Business Analytics<br>Politeknik Negeri Malang</p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-lg-start justify-content-center align-items-center">
                    <a href="/lab-ba/public/peminjaman.php" class="btn btn-lg px-5 py-3 fw-bold shadow btn-accent rounded-pill">Ajukan Peminjaman <i class="bi bi-arrow-right ms-2"></i></a>
                    <a href="#profile" class="btn btn-lg px-4 py-3 fw-bold btn-outline-light rounded-pill">Pelajari Kami</a>
                </div>
            </div>
            <div class="col-lg-5 d-flex align-items-center justify-content-lg-end justify-content-center ps-lg-5" style="min-height:340px;">
                <div class="w-100 d-flex flex-column align-items-center align-items-lg-end justify-content-center">
                    <img src="/lab-ba/public/assets/img/maskot.png" alt="Maskot Lab BA" class="img-fluid mb-3 shadow-lg animate__animated animate__fadeInRight hero-maskot-img" style="max-width:530px; width:100%; height:auto;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. PROFIL SECTION -->
<section id="profile" class="section-padding bg-light">
    <div class="container">
        <!-- Judul -->
        <div class="section-title-wrapper">
            <h2 class="section-title">Profil Laboratorium</h2>
            <div class="section-divider"></div>
        </div>

        <!-- Grid Cards (Wrapper section-card dihapus agar kartu terpisah) -->
        <div class="row g-4">
            <?php foreach (Profil::all() as $p):
                $kategori = strtolower($p['kategori']);
                $icon = 'bi-info-circle';
                $col_class = 'col-md-6 col-lg-4';

                // Logika Icon & Kolom
                if ($kategori == 'visi') {
                    $icon = 'bi-eye';
                } elseif ($kategori == 'misi') {
                    $icon = 'bi-bullseye';
                } elseif ($kategori == 'fasilitas') {
                    $icon = 'bi-cpu';
                } elseif ($kategori == 'fokus_riset') {
                    $icon = 'bi-graph-up-arrow';
                } elseif ($kategori == 'profil') {
                    $icon = 'bi-building-check';
                    $col_class = 'col-12';
                }
            ?>
                <div class="<?= $col_class ?>">
                    <div class="feature-item h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="feature-icon-box">
                                <i class="bi <?= $icon ?>"></i>
                            </div>
                            <span class="badge bg-light text-primary border border-primary-subtle rounded-pill px-3 py-2">
                                <?= ucfirst($p['kategori']); ?>
                            </span>
                        </div>

                        <h4 class="fw-bold mb-3 text-primary" style="font-size: 1.35rem;"><?= $p['judul']; ?></h4>
                        <p class="text-muted mb-0" style="line-height: 1.7;"><?= nl2br($p['isi']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 3. DOSEN SECTION -->
<section id="dosen" class="section-padding bg-white">
    <div class="container">
        <!-- Judul di Luar -->
        <div class="section-title-wrapper">
            <h2 class="section-title">Dosen Pengampu</h2>
            <div class="section-divider"></div>
        </div>

        <!-- Konten di Dalam Card -->
        <div class="section-card bg-light-subtle">
            <?php $dosenList = Dosen::all();
            $chunks = array_chunk($dosenList, 4); ?>
            <div id="carouselDosen" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner pb-4">
                    <?php foreach ($chunks as $index => $group): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <div class="row g-4 justify-content-center">
                                <?php foreach ($group as $d): ?>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="dosen-card-simple h-100 p-4 text-center" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#dosenModal<?= $d['id'] ?>">
                                            <div class="mb-3 mx-auto position-relative" style="width:100px; height:100px;">
                                                <?php if (!empty($d['foto'])): ?>
                                                    <img src="/lab-ba/public/uploads/dosen/<?= htmlspecialchars($d['foto']) ?>" class="rounded-circle w-100 h-100 object-fit-cover shadow-sm border border-3 border-white">
                                                <?php else: ?>
                                                    <div class="w-100 h-100 bg-secondary-subtle rounded-circle d-flex align-items-center justify-content-center text-secondary border border-3 border-white"><i class="bi bi-person-fill fs-1"></i></div>
                                                <?php endif; ?>
                                            </div>
                                            <h5 class="fw-bold text-primary mb-1"><?= htmlspecialchars($d['nama']) ?></h5>
                                            <p class="text-accent small fw-bold mb-0"><?= htmlspecialchars($d['keahlian']) ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="d-flex justify-content-center gap-2 mt-2">
                    <button class="btn btn-outline-primary btn-sm rounded-circle" style="width:40px;height:40px;" type="button" data-bs-target="#carouselDosen" data-bs-slide="prev"><i class="bi bi-chevron-left"></i></button>
                    <button class="btn btn-outline-primary btn-sm rounded-circle" style="width:40px;height:40px;" type="button" data-bs-target="#carouselDosen" data-bs-slide="next"><i class="bi bi-chevron-right"></i></button>
                </div>
                <<div class="text-center mt-4">
                    <a href="/lab-ba/public/dosen.php" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                        Lihat Semua Dosen <i class="bi bi-arrow-right ms-1"></i>
                    </a>
            </div>
        </div>
    </div>
    </div>
</section>

<!-- 4. PUBLIKASI SECTION -->
<section id="publikasi" class="section-padding bg-light">
    <div class="container">
        <!-- Judul di Luar -->
        <div class="section-title-wrapper">
            <h2 class="section-title">Publikasi Riset</h2>
            <div class="section-divider"></div>
        </div>

        <!-- Konten di Dalam Card -->
        <div class="section-card">
            <?php $publikasiList = Publikasi::all();
            if (count($publikasiList) === 0): ?>
                <div class="text-center py-5 text-muted">Belum ada data publikasi.</div>
            <?php else: ?>
                <div class="publikasi-list">
                    <?php
                    $recentPubs = array_slice($publikasiList, 0, 3);
                    foreach ($recentPubs as $pub): ?>
                        <div class="publikasi-item card mb-3 p-3 rounded-3 shadow-sm border-0 bg-light">
                            <div class="d-flex align-items-center flex-wrap flex-md-nowrap gap-3">
                                <?php $pub_tahun = date('Y', strtotime($pub['tanggal'])); ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fs-6"><?= htmlspecialchars($pub_tahun) ?></span>

                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold text-primary fs-5">
                                        <?php if ($pub['link']): ?>
                                            <a href="<?= htmlspecialchars($pub['link']) ?>" target="_blank" class="text-decoration-none text-primary hover-text-accent">
                                                <?= htmlspecialchars($pub['judul']) ?>
                                            </a>
                                        <?php else: ?>
                                            <?= htmlspecialchars($pub['judul']) ?>
                                        <?php endif; ?>
                                    </h6>
                                    <div class="small text-muted">
                                        <?php $dosen = Publikasi::getDosen($pub['id']);
                                        if ($dosen):
                                            echo '<i class="bi bi-people-fill me-1"></i> ' . implode(', ', array_map(function ($ds) {
                                                return htmlspecialchars($ds['nama']);
                                            }, $dosen));
                                        else:
                                            echo '<span class="text-muted">Tidak ada peneliti terdaftar</span>';
                                        endif; ?>
                                    </div>
                                </div>

                                <div class="ms-md-auto">
                                    <a href="publikasi_detail.php?id=<?= $pub['id'] ?>" class="btn btn-outline-primary rounded-pill btn-sm px-4">Detail</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-4">
                    <a href="/lab-ba/public/publikasi.php" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                        Lihat Semua Publikasi <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 5. BERITA SECTION -->
<section id="berita" class="section-padding bg-white">
    <div class="container">
        <!-- Judul di Luar -->
        <div class="section-title-wrapper">
            <h2 class="section-title">Berita Terbaru</h2>
            <div class="section-divider"></div>
        </div>

        <!-- Konten di Dalam Card -->
        <div class="section-card bg-light-subtle">
            <?php $beritaList = Berita::all(3, 0);
            if (count($beritaList) === 0): ?>
                <div class="text-center py-5 text-muted">Belum ada berita terbaru.</div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($beritaList as $b): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 berita-card bg-white">
                                <?php if (!empty($b['gambar'])): ?>
                                    <img src="/lab-ba/public/uploads/berita/<?= htmlspecialchars($b['gambar']) ?>" class="card-img-top" alt="Gambar Berita" style="height:200px; object-fit:cover;">
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column p-4">
                                    <div class="mb-2 text-accent small fw-bold text-uppercase"><i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($b['tanggal'])) ?></div>
                                    <h5 class="card-title fw-bold text-primary mb-3"><?= htmlspecialchars($b['judul']) ?></h5>
                                    <p class="card-text text-muted small flex-grow-1">
                                        <?= nl2br(htmlspecialchars(mb_strimwidth($b['isi'], 0, 100, '...'))) ?>
                                    </p>
                                    <a href="berita_detail.php?id=<?= $b['id'] ?>" class="text-primary text-decoration-none fw-bold small mt-3">Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-4">
                    <a href="/lab-ba/public/berita.php" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                        Lihat Semua Berita <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 6. GALERI SECTION -->
<section id="galeri" class="section-padding bg-light">
    <section id="galeri" class="section-padding bg-light">
        <div class="container">
            <div class="section-title-wrapper">
                <h2 class="section-title">Dokumentasi Kegiatan</h2>
                <div class="section-divider"></div>
            </div>

            <div class="section-card p-0 overflow-hidden border-0">
                <div id="carouselGaleri" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php $galeriList = Galeri::all();
                        if (empty($galeriList)): ?><div class="p-5 text-center text-muted">Belum ada galeri.</div><?php else:
                                                                                                                    $i = 0;
                                                                                                                    foreach ($galeriList as $g): ?>
                                <div class="carousel-item <?= $i == 0 ? 'active' : '' ?>">
                                    <div class="position-relative">
                                        <img src="/lab-ba/public/uploads/galeri/<?= $g['gambar']; ?>"
                                            class="d-block w-100"
                                            style="height: 550px; object-fit: cover; cursor: pointer;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#galeriModal<?= $g['id'] ?>"
                                            alt="<?= htmlspecialchars($g['judul']) ?>">
                                        <div class="position-absolute bottom-0 start-0 w-100 p-5" style="background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); pointer-events: none;">
                                            <h3 class="text-white fw-bold"><?= $g['judul']; ?></h3>
                                            <p class="text-white-50 mb-0 d-none d-md-block"><?= $g['deskripsi'] ? mb_strimwidth($g['deskripsi'], 0, 150, '...') : '' ?></p>
                                        </div>
                                    </div>
                                </div>
                        <?php $i++;
                                                                                                                    endforeach;
                                                                                                                endif; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselGaleri" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselGaleri" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                </div>
            </div>

            <!-- Tombol Lihat Semua Galeri -->
            <div class="text-center mt-4">
                <a href="/lab-ba/public/galeri.php" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                    Lihat Semua Galeri <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
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
        </div>
    </section>
</section>

<!-- 7. FOOTER CONTACT -->
<section id="kontak" class="text-white py-5" style="background-color: var(--primary-color);">
    <div class="container">
        <div class="row gy-5 align-items-center">
            <div class="col-lg-5">
                <h2 class="fw-bold mb-4">Hubungi Kami</h2>
                <p class="text-white-50 mb-4">Kami siap membantu kebutuhan akademik dan penelitian Anda di Laboratorium Business Analytics.</p>

                <?php $kontak = KontakLab::get(); ?>
                <div class="d-flex mb-4">
                    <div class="icon-box me-3 rounded-circle bg-white bg-opacity-10 d-flex align-items-center justify-content-center" style="width:45px;height:45px;flex-shrink:0;">
                        <i class="bi bi-geo-alt-fill text-accent"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Alamat</h6>
                        <span class="small text-white-50"><?= nl2br(htmlspecialchars($kontak['alamat'] ?? '-')) ?></span>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="icon-box me-3 rounded-circle bg-white bg-opacity-10 d-flex align-items-center justify-content-center" style="width:45px;height:45px;flex-shrink:0;">
                        <i class="bi bi-envelope-fill text-accent"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Email</h6>
                        <a href="mailto:<?= htmlspecialchars($kontak['email'] ?? '') ?>" class="text-white-50 text-decoration-none small"><?= htmlspecialchars($kontak['email'] ?? '-') ?></a>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="icon-box me-3 rounded-circle bg-white bg-opacity-10 d-flex align-items-center justify-content-center" style="width:45px;height:45px;flex-shrink:0;">
                        <i class="bi bi-telephone-fill text-accent"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Telepon/WhatsApp</h6>
                        <span class="small text-white-50"><?= htmlspecialchars($kontak['telepon'] ?? '-') ?></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 offset-lg-1">
                <div class="section-card p-1 bg-white border-0 shadow-lg">
                    <?php if (!empty($kontak['maps_embed'])): ?>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden"> <?= $kontak['maps_embed'] ?> </div>
                    <?php else: ?>
                        <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center rounded-3">
                            <span class="text-muted">Peta tidak tersedia</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Modals Dosen -->
<?php foreach ($dosenList as $d): ?>
    <div class="modal fade" id="dosenModal<?= $d['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body text-center pb-5">
                    <?php if (!empty($d['foto'])): ?><img src="/lab-ba/public/uploads/dosen/<?= htmlspecialchars($d['foto']) ?>" class="rounded-circle mb-3 shadow-sm" style="width:120px;height:120px;object-fit:cover;"><?php endif; ?>
                    <h4 class="fw-bold text-primary mb-1"><?= htmlspecialchars($d['nama']) ?></h4>
                    <span class="badge bg-light text-primary mb-3"><?= htmlspecialchars($d['keahlian']) ?></span>
                    <p class="text-muted px-4"><?= nl2br(htmlspecialchars($d['deskripsi'] ?? '')) ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php include "../views/layouts/footer.php"; ?>