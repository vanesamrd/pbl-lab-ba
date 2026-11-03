<?php include "../views/layouts/header.php"; ?>
<?php require_once "../app/models/Profil.php"; ?>

<section class="bg-white py-5 min-vh-100">
    <div class="container">
        <div class="section-container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-1 text-primary-custom">Profil Laboratorium Business Analytics</h2>
                <div class="text-muted">Informasi lengkap tentang visi, misi, dan aktivitas Laboratorium Business Analytics</div>
            </div>
        </div>
        <div class="row g-4">
    <?php foreach (Profil::all() as $p):
        $kategori = strtolower($p['kategori']);
        // Menentukan ikon berdasarkan kategori
        $icon = 'bi bi-info-circle-fill';
        if ($kategori == 'visi') {
            $icon = 'bi bi-eye-fill';
        } elseif ($kategori == 'misi') {
            $icon = 'bi bi-bullseye';
        }
    ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-lg border-0 bg-light-blue"> <div class="card-body">
                    <h5 class="card-title mb-1 text-primary-custom">
                        <i class="<?= $icon ?> me-2 text-accent"></i> <?= htmlspecialchars($p['judul']) ?>
                    </h5>
                    <span class="badge bg-accent mb-2"><?= htmlspecialchars($p['kategori']) ?></span>
                    <div class="card-text" style="white-space:pre-line;">
                        <?= nl2br(htmlspecialchars($p['isi'])) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
    </div>
</section>

<style>
    .text-primary-custom {
        color: #0A2A43 !important;
    }

    .bg-accent {
        background-color: #3FA2F7 !important;
        color: #fff !important;
    }

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

    /* Efek Timbul untuk setiap Card */
    .card-raised {
        transition: transform 0.3s cubic-bezier(0.2, 0.8, 0.4, 1.2), box-shadow 0.3s ease, border-color 0.3s ease;
        box-shadow: 0 2px 8px rgba(10, 42, 67, 0.05), 0 1px 2px rgba(63, 162, 247, 0.03);
        border: 1px solid rgba(63, 162, 247, 0.1) !important;
        border-radius: 0.75rem !important;
    }

    .card-raised:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(10, 42, 67, 0.1), 0 2px 5px rgba(63, 162, 247, 0.1);
        border: 1px solid #3FA2F7 !important;
    }
</style>

<?php include "../views/layouts/footer.php"; ?>