<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/lab-ba/app/models/Settings.php';
$S = Settings::get();
if (!$S) {
  $S = [
    'site_name' => 'Lab Business Analytics',
    'logo' => ''
  ];
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lab Business Analytics</title>
  <link rel="icon" type="image/png" href="/lab-ba/public/uploads/logo/Jti_polinema.svg.png">

  <!-- Favicon -->
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts: Poppins, Inter, Rubik -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@400;700&family=Rubik:wght@400;700&display=swap" rel="stylesheet">
  <!-- Custom Style -->
  <link rel="stylesheet" href="/lab-ba/public/assets/css/style.css">
  <!-- Bootstrap JS (agar dropdown berfungsi di admin) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Dropdown fix jika JS tidak otomatis jalan -->
  <script src="/lab-ba/public/assets/js/dropdown-fix.js"></script>
  <style>
    body {
      font-family: 'Poppins', 'Inter', 'Rubik', Arial, sans-serif;
      background-color: #f8f9fa;
    }

    .bg-primary-custom {
      background-color: #0A2A43 !important;
    }

    .text-primary-custom {
      color: #0A2A43 !important;
    }

    .bg-accent {
      background-color: #3FA2F7 !important;
    }

    .text-accent {
      color: #3FA2F7 !important;
    }

    .navbar-brand {
      font-weight: 700;
      letter-spacing: 1px;
    }

    .btn-accent {
      background-color: #3FA2F7;
      color: #fff;
      border: none;
    }

    .btn-accent:hover {
      background-color: #2196f3;
      color: #fff;
    }

    .section-title {
      font-size: 2.2rem;
      font-weight: 700;
      color: #0A2A43;
      margin-bottom: 1.5rem;
    }

    .section-subtitle {
      color: #3FA2F7;
      font-weight: 600;
    }

    html {
      scroll-behavior: smooth;
    }
  </style>
</head>


<?php
// Deteksi admin login
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$isAdmin = isset($_SESSION['admin_id']);
$adminName = $isAdmin ? $_SESSION['admin_username'] : null;
// Daftar menu sidebar admin (otomatis dari file di folder admin)
$adminMenus = [
  ['label' => 'Dashboard', 'file' => 'dashboard.php', 'icon' => 'bi-speedometer2', 'desc' => 'Ringkasan aktivitas admin'],
  ['label' => 'Manajemen Admin', 'file' => 'users/manage.php', 'icon' => 'bi-person-badge', 'desc' => 'Kelola akun admin'],
  ['label' => 'Manajemen Dosen', 'file' => 'dosen/manage.php', 'icon' => 'bi-people', 'desc' => 'Tambah dan edit data dosen'],
  ['label' => 'Manajemen Galeri', 'file' => 'galeri/manage.php', 'icon' => 'bi-images', 'desc' => 'Kelola foto dan dokumentasi'],
  ['label' => 'Manajemen Publikasi', 'file' => 'publikasi/manage.php', 'icon' => 'bi-journal-text', 'desc' => 'Atur artikel publikasi'],
  ['label' => 'Manajemen Berita', 'file' => 'berita/manage.php', 'icon' => 'bi-newspaper', 'desc' => 'Update berita terbaru'],
  ['label' => 'Manajemen Kategori', 'file' => 'kategori/manage.php', 'icon' => 'bi-tags', 'desc' => 'Pengaturan kategori data'],
  ['label' => 'Manajemen Peminjaman', 'file' => 'peminjaman/manage.php', 'icon' => 'bi-calendar-check', 'desc' => 'Kelola data peminjaman'],
  ['label' => 'Kelola Jam Tidak Tersedia', 'file' => 'kelola_jam/jam-tidak-tersedia.php', 'icon' => 'bi-clock-history', 'desc' => 'Atur jam nonaktif lab'],
  ['label' => 'Manajemen Profil', 'file' => 'profil/manage.php', 'icon' => 'bi-person-lines-fill', 'desc' => 'Edit profil laboratorium'],
  ['label' => 'Pengaturan Website', 'file' => 'pengaturan/website.php', 'icon' => 'bi-gear', 'desc' => 'Atur tampilan website'],
  ['label' => 'Pengaturan Kontak', 'file' => 'kontak/lab.php', 'icon' => 'bi-telephone', 'desc' => 'Kelola informasi kontak'],
];

?>

<body class="d-flex flex-column min-vh-100">

  <!-- Navbar -->
  <!-- Navbar -->
  <?php if ($isAdmin && strpos($_SERVER['PHP_SELF'], '/admin/') !== false): ?>
    <nav class="navbar navbar-expand-lg bg-primary-custom navbar-dark py-3 fixed-top shadow-sm">
      <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="/lab-ba/public/index.php">
          <?php if (!empty($S['logo_polinema'])): ?>
            <img src="/lab-ba/public/uploads/logo/<?= htmlspecialchars($S['logo_polinema']) ?>" alt="Polinema" style="height:32px;max-width:40px;object-fit:contain;">
          <?php endif; ?>
          <?php if (!empty($S['logo_jti'])): ?>
            <img src="/lab-ba/public/uploads/logo/<?= htmlspecialchars($S['logo_jti']) ?>" alt="JTI" style="height:32px;max-width:40px;object-fit:contain;">
          <?php endif; ?>
          <?php if (!empty($S['logo'])): ?>
            <img src="/lab-ba/public/uploads/logo/<?= htmlspecialchars($S['logo']) ?>" alt="Logo" style="height:36px;max-width:48px;object-fit:contain;">
          <?php endif; ?>
          <?= htmlspecialchars($S['site_name'] ?? 'Lab Business Analytics') ?>
        </a>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="btn btn-danger fw-semibold rounded-pill px-4" href="/lab-ba/admin/logout.php">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </nav>
  <?php else: ?>
    <nav class="navbar navbar-expand-lg bg-primary-custom navbar-dark py-3 fixed-top shadow-sm">
      <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="/lab-ba/public/index.php">
          <?php if (!empty($S['logo_polinema'])): ?>
            <img src="/lab-ba/public/uploads/logo/<?= htmlspecialchars($S['logo_polinema']) ?>" alt="Polinema" style="height:32px;max-width:40px;object-fit:contain;">
          <?php endif; ?>
          <?php if (!empty($S['logo_jti'])): ?>
            <img src="/lab-ba/public/uploads/logo/<?= htmlspecialchars($S['logo_jti']) ?>" alt="JTI" style="height:32px;max-width:40px;object-fit:contain;">
          <?php endif; ?>
          <?php if (!empty($S['logo'])): ?>
            <img src="/lab-ba/public/uploads/logo/<?= htmlspecialchars($S['logo']) ?>" alt="Logo" style="height:36px;max-width:48px;object-fit:contain;">
          <?php endif; ?>
          <?= htmlspecialchars($S['site_name'] ?? 'Lab Business Analytics') ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="#hero">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#profile">Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="#galeri">Galeri</a></li>
            <li class="nav-item"><a class="nav-link" href="#dosen">Dosen</a></li>
            <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
            <?php if (!$isAdmin): ?>
              <li class="nav-item"><a class="nav-link btn-login-nav" href="/lab-ba/public/login.php">Login</a></li>
            <?php else: ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-person-circle"></i> Hi, <?= htmlspecialchars($adminName) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                  <li><a class="dropdown-item" href="/lab-ba/admin/dashboard.php">Dashboard Admin</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="/lab-ba/admin/logout.php">Logout</a></li>
                </ul>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
  <?php endif; ?>
  <!-- End Navbar -->

  <style>
    body {
      padding-top: 74px !important;
    }

    @media (max-width: 991.98px) {
      body {
        padding-top: 62px !important;
      }
    }

    .navbar.fixed-top {
      box-shadow: 0 2px 16px 0 rgba(10, 42, 67, 0.10);
    }

    .btn-login-nav {
    padding: 0.6rem 1.8rem !important;
    margin-left: 15px;
    border-radius: 50px;
    border: 2px solid transparent;

    background: transparent;
    color: var(--primary-dark) !important;

    font-weight: 700;
    letter-spacing: 0.3px;

    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.btn-login-nav:hover {
    background-color: var(--primary-accent);
    border-color: var(--primary-accent);
    color: #ffffff !important;

    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(63, 162, 247, 0.4);
}
  </style>

  