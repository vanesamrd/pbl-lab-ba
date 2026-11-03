<main>
    <!-- Hero Section -->
    <section id="hero" class="bg-primary-custom text-white text-center py-5">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-5 fw-bold mb-3">Selamat Datang Di Web Laboratorium Business Analytics</h1>
                    <p class="lead mb-4">Pusat pengembangan & riset berbasis data untuk kolaborasi, IPTEK, dan pengambilan keputusan bisnis modern.</p>
                </div>
                <div class="col-lg-5">
                    <img src="../public/assets/img/logo.jpeg" alt="Lab Business Analytics" class="img-fluid rounded shadow" style="max-height:220px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Profile & Visi Misi -->
    <?php
    require_once __DIR__ . '/../app/models/Profil.php';
    $profilList = Profil::all();
    ?>
    <section id="profile" class="bg-white py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="section-title">PROFILE</h2>
                    <?php foreach ($profilList as $p): ?>
                        <?php if (strtolower($p['kategori']) == 'profile' || strtolower($p['kategori']) == 'profil'): ?>
                            <p class="mb-4"><?= nl2br($p['isi']); ?></p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="row" id="visimisi">
    <div class="col-12">
        <h3 class="section-title">VISI DAN MISI</h3>
        <div class="row g-4 mb-4">
            <?php
            $visi = null;
            $misi = null;
            foreach ($profilList as $p) {
                if (strtolower($p['kategori']) == 'visi') {
                    $visi = $p;
                } elseif (strtolower($p['kategori']) == 'misi') {
                    $misi = $p;
                }
            }
            ?>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0 bg-light-blue">
                    <div class="card-body">
                        <h4 class="card-title fw-bold text-primary-custom mb-3"><i class="bi bi-eye-fill me-2 text-accent"></i><?= htmlspecialchars($visi['kategori'] ?? 'Visi') ?></h4>
                        <p class="card-text"><?= nl2br(htmlspecialchars($visi['isi'] ?? 'Belum ada data Visi.')) ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0 bg-light-blue">
                    <div class="card-body">
                        <h4 class="card-title fw-bold text-primary-custom mb-3"><i class="bi bi-bullseye me-2 text-accent"></i><?= htmlspecialchars($misi['kategori'] ?? 'Misi') ?></h4>
                        <p class="card-text"><?= nl2br(htmlspecialchars($misi['isi'] ?? 'Belum ada data Misi.')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </section>

    <!-- Daftar Dosen -->
    <section id="dosen" class="py-5" style="background: linear-gradient(90deg, #f8f9fa 60%, #3FA2F7 40%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="section-title">Daftar Dosen</h2>
                    <p class="mb-3">Dosen-dosen kami adalah tenaga ahli yang berpengalaman di bidang analitik bisnis dan data science, siap membimbing dan berkolaborasi dengan mahasiswa.</p>
                    <a href="/lab-ba/public/profil.php" class="btn btn-accent">Lihat Profil</a>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="/lab-ba/public/assets/img/maskot.jpeg" alt="Daftar Dosen" class="img-fluid rounded shadow" style="max-height:200px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Galeri Lab -->
    <section id="galeri" class="bg-primary-custom text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="section-title text-white">Galeri Lab</h2>
                    <p class="mb-3">Lihat suasana laboratorium, aktivitas, seminar, dan pelatihan yang pernah dilaksanakan di Lab Business Analytics.</p>
                    <a href="/lab-ba/public/galeri.php" class="btn btn-accent">Selengkapnya</a>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="/lab-ba/public/assets/img/pexels-galeri.jpg" alt="Galeri Lab" class="img-fluid rounded shadow" style="max-height:200px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak Kami -->
    <section id="kontak" class="bg-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center mb-4 mb-lg-0">
                    <img src="/lab-ba/public/assets/img/contact-illustration.png" alt="Kontak" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6">
                    <h2 class="section-title">Kontak Kami</h2>
                    <p>Hubungi kami untuk info lebih lanjut, kerjasama, atau konsultasi riset dan pelatihan di bidang business analytics.</p>
                    <a href="mailto:labba@univ.ac.id" class="btn btn-accent">Email Kami</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sponsors & Testimoni -->
    <section class="bg-primary-custom text-white py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="section-title text-white">Our Sponsors</h2>
                    <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap mb-4">
                        <img src="/lab-ba/public/assets/img/sponsor-apple.png" alt="Apple" height="40">
                        <img src="/lab-ba/public/assets/img/sponsor-microsoft.png" alt="Microsoft" height="40">
                        <img src="/lab-ba/public/assets/img/sponsor-slack.png" alt="Slack" height="40">
                        <img src="/lab-ba/public/assets/img/sponsor-google.png" alt="Google" height="40">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <h2 class="section-title text-white">What Our Clients Say</h2>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">“Meningkatkan skill & kolaborasi riset di laboratorium ini sangat membantu pengembangan karir saya.”</blockquote>
                        </div>
                        <div class="card-footer bg-white border-0 d-flex align-items-center gap-2">
                            <img src="/lab-ba/public/assets/img/user1.png" alt="User 1" class="rounded-circle" width="40">
                            <span>Oberon Shaw, MCH</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">“Fasilitas dan pelatihan di Lab BA sangat lengkap dan aplikatif untuk dunia kerja.”</blockquote>
                        </div>
                        <div class="card-footer bg-white border-0 d-flex align-items-center gap-2">
                            <img src="/lab-ba/public/assets/img/user2.png" alt="User 2" class="rounded-circle" width="40">
                            <span>Oberon Shaw, MCH</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">“Saya merekomendasikan Lab BA untuk mahasiswa yang ingin belajar analitik bisnis secara praktis.”</blockquote>
                        </div>
                        <div class="card-footer bg-white border-0 d-flex align-items-center gap-2">
                            <img src="/lab-ba/public/assets/img/user3.png" alt="User 3" class="rounded-circle" width="40">
                            <span>Oberon Shaw, MCH</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-accent text-white text-center py-5">
        <div class="container">
            <h2 class="section-title text-white">Try Lab Business Analytics today</h2>
            <p class="mb-4">Gabung dan kembangkan skill analitik bisnis Anda bersama kami!</p>
            <a href="/lab-ba/public/peminjaman.php" class="btn btn-light text-accent fw-bold px-4 py-2">Daftar Sekarang</a>
        </div>
    </section>
</main>