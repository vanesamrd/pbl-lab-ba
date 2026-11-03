<?php
session_start();
require_once "../app/config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];

        require_once "../app/utils/log.php";
        logActivity("Login ke sistem");

        header("Location: /lab-ba/admin/dashboard.php");
        exit;
    } else {
        $message = "Username atau Password salah!";
    }
}
?>

<?php include_once "../views/layouts/header.php"; ?>

<!-- Background Wrapper (Static) -->
<div class="login-wrapper">
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        
        <!-- Large Glass Card -->
        <div class="card glass-card border-0">
            <div class="card-body p-5">
                <div class="text-center mb-5">
                    <div class="maskot-container mb-3">
                        <img src="/lab-ba/public/assets/img/maskot.png" alt="Lab BA" class="hero-maskot-img">
                    </div>
                    <h2 class="mb-1 text-primary-custom fw-bold">Admin Portal</h2>
                    <p class="text-muted fs-5">Silakan login untuk masuk ke sistem</p>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-danger alert-dismissible fade show text-center py-3 shadow-sm border-0 mb-4" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i><?= $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" autocomplete="off" class="login-form">
                    <div class="mb-4 input-group-custom">
                        <label class="form-label fw-bold text-secondary">Username</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0 ps-3"><i class="bi bi-person text-primary fs-4"></i></span>
                            <input type="text" name="username" class="form-control bg-light border-start-0 ps-0" placeholder="Masukkan username" required autofocus>
                        </div>
                    </div>

                    <div class="mb-5 input-group-custom">
                        <label class="form-label fw-bold text-secondary">Password</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0 ps-3"><i class="bi bi-lock text-primary fs-4"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-start-0 ps-0" placeholder="Masukkan password" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-login btn-lg py-3">
                            <span class="fs-5">Login Sekarang</span> <i class="bi bi-arrow-right-short ms-2 fs-4 align-middle"></i>
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-5">
                    <a href="index.php" class="btn-link-custom text-decoration-none fs-6">
                    <a href="index.php" class="btn btn-accent w-150 mt-2 fw-bold rounded-pill">Kembali Ke Beranda</a>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* 1. Global & Background Styles (Static) */
.login-wrapper {
    /* Background statis yang bersih dan profesional */
    background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%); 
    /* Alternatif warna biru gelap jika suka: background: linear-gradient(135deg, #102a43 0%, #243b53 100%); */
    min-height: 100vh;
    display: flex;
    align-items: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* 2. Card Styling (Wider & Cleaner) */
.glass-card {
    width: 100%;
    max-width: 550px; /* Diperbesar dari 420px */
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1); /* Soft heavy shadow */
    overflow: hidden;
    transition: transform 0.3s ease;
}

/* 3. Mascot Styling (Bigger) */
.maskot-container {
    display: inline-block;
}
.hero-maskot-img {
    width: 110px; /* Diperbesar dari 80px */
    height: auto;
    filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
}

/* 4. Form Inputs (Chunky/Large) */
.input-group-text {
    border-color: #e0e0e0;
    border-top-left-radius: 12px;
    border-bottom-left-radius: 12px;
}

.form-control {
    border-color: #e0e0e0;
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
    padding-top: 15px;
    padding-bottom: 15px;
    font-size: 1.1rem;
}

.form-control:focus {
    box-shadow: none;
    border-color: #54a9ff;
    background-color: #fff;
}

.input-group-lg > .input-group-text {
    padding-left: 20px;
}

/* 5. Button Styling (Large) */
.btn-login {
    border-radius: 12px;
    font-weight: 700;
    background-image: linear-gradient(135deg, #54a9ff, #114b8c);
    color: #fff;
    border: none;
    box-shadow: 0 10px 20px rgba(84, 169, 255, 0.3);
    transition: all 0.3s ease;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(84, 169, 255, 0.4);
    color: #fff;
}

.btn-login:active {
    transform: scale(0.98);
}

/* 6. Typography & Link */
.text-primary-custom {
    color: #114b8c;
    letter-spacing: -0.5px;
}

.btn-link-custom {
    color: #829ab1;
    font-weight: 600;
    transition: color 0.3s;
}

.btn-link-custom:hover {
    color: #114b8c;
}
</style>

<?php include_once "../views/layouts/footer.php"; ?>