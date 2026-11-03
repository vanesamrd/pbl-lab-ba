<?php

require_once __DIR__ . '/../app/controllers/PeminjamanController.php';
require_once __DIR__ . '/../app/models/JamTidakTersedia.php';

$alert = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = trim($_POST['nama'] ?? '');
  $nip = trim($_POST['nip'] ?? '');
  $tanggal = $_POST['tanggal'] ?? '';
  $mulai = $_POST['mulai'] ?? '';
  $selesai = $_POST['selesai'] ?? '';
  $keperluan = trim($_POST['keperluan'] ?? '');
  // Validasi tanggal minimal hari ini
  if ($tanggal && strtotime($tanggal) < strtotime(date('Y-m-d'))) {
    $alert = ['type' => 'danger', 'msg' => 'Tanggal peminjaman tidak boleh sebelum hari ini!'];
  }
  // Validasi semua field wajib diisi
  elseif ($nama && $nip && $tanggal && $mulai && $selesai && $keperluan) {
    // Cek slot sudah disetujui orang lain
    if (!Peminjaman::isSlotAvailable($tanggal, $mulai, $selesai)) {
      $alert = ['type' => 'danger', 'msg' => 'Slot sudah disetujui orang lain, silakan pilih waktu lain.'];
    } elseif (Peminjaman::isSlotBlockedByAdmin($tanggal, $mulai, $selesai)) {
      $alert = ['type' => 'warning', 'msg' => 'Slot tidak tersedia, silakan pilih waktu lain.'];
    } else {
      Peminjaman::create($nama, $nip, $tanggal, $mulai, $selesai, $keperluan);
      header('Location: peminjaman.php?success=1');
      exit;
    }
  } else {
    $alert = ['type' => 'danger', 'msg' => 'Semua field wajib diisi!'];
  }
}

$selectedDate = $_POST['tanggal'] ?? $_GET['tanggal'] ?? null;
$week = PeminjamanController::getWeekRange($selectedDate);
$slots = PeminjamanController::getTimeSlots();

$jadwal = PeminjamanController::getJadwal($week[0]->format('Y-m-d'), $week[6]->format('Y-m-d'));
$jamTidakTersedia = JamTidakTersedia::allByRange($week[0]->format('Y-m-d'), $week[6]->format('Y-m-d'));

$riwayat = Peminjaman::all();

include __DIR__ . '/../views/layouts/header.php';
echo '<main class="bg-light py-5 min-vh-100">';
include __DIR__ . '/../views/peminjaman.php';
echo '</main>';
include __DIR__ . '/../views/layouts/footer.php';
?>
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold text-success" id="successModalLabel"><i class="bi bi-check-circle me-2"></i>Pengajuan Berhasil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        Pengajuan peminjaman berhasil dikirim.<br>Silakan menunggu persetujuan admin.
      </div>
      <div class="modal-footer border-0 justify-content-center">
        <a href="peminjaman.php" class="btn btn-accent rounded-pill px-4">OK</a>
      </div>
    </div>
  </div>
</div>
</section>
<?php if (!empty($showSuccessModal)): ?>
  <script>
    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
    window.addEventListener('DOMContentLoaded', function() {
      successModal.show();
    });
  </script>
<?php endif; ?>
</section>

<style>
  .bg-accent {
    background-color: #3FA2F7 !important;
  }

  .text-primary-custom {
    color: #0A2A43 !important;
  }

  .btn-accent {
    background: #3FA2F7;
    color: #fff;
    border: none;
  }

  .btn-accent:hover,
  .btn-accent:focus {
    background: #2386d9;
    color: #fff;
  }
</style>