<?php
/**
 * ============================================================================
 * KARGO REGULER — Management Page
 * ============================================================================
 * 
 * Job 4 (Controller / Driver): Halaman manajemen untuk Kargo Reguler.
 * Menampilkan data dari tabel kargo JOIN kargo_reguler.
 * 
 * Subclass: KargoReguler extends Kargo
 *   - Atribut: jenisPaket (Koli/Dus), estimasiHari
 *   - Rumus Tarif: Berat × tarifDasarPerKg
 *   - SOP: Validasi berdasarkan jenis paket
 * 
 * @package  LogiCargo Dashboard
 * @version  1.0.0
 * ============================================================================
 */

// ── Database Connection & Controller ──
require_once 'config/koneksi.php';
require_once 'classes/ManajemenKargo.php';

$manajemen = new ManajemenKargo($koneksi);

// ── Handle POST: Delete ──
$alert_message = "";
$alert_type = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_db_connected) {
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        $id_resi = trim($_POST['id_resi'] ?? '');
        if (!empty($id_resi) && $manajemen->hapusKargo($id_resi)) {
            $alert_message = "Kargo Reguler <strong>{$id_resi}</strong> berhasil di-void.";
            $alert_type = "success";
        } else {
            $alert_message = "Gagal menghapus kargo <strong>{$id_resi}</strong>.";
            $alert_type = "error";
        }
    }
}

// ── Load Data: Hanya Kargo Reguler ──
$data_reguler = [];
$total_reguler = 0;
$total_revenue_reguler = 0;
$total_berat_reguler = 0;
$avg_estimasi = 0;

if ($is_db_connected) {
    $manajemen->loadAllKargo();
    $all_data = $manajemen->getAllWithTarif();
    
    // Filter hanya Reguler
    foreach ($all_data as $item) {
        if ($item['jenis'] === 'Reguler') {
            $data_reguler[] = $item;
            $total_revenue_reguler += $item['tarif'];
            $total_berat_reguler   += $item['kargo']->getBeratBarang();
            $avg_estimasi          += $item['kargo']->getEstimasiHari();
        }
    }
    $total_reguler = count($data_reguler);
    $avg_estimasi  = $total_reguler > 0 ? round($avg_estimasi / $total_reguler, 1) : 0;
}

// ── Page Meta ──
$page_title    = "Kargo Reguler";
$page_subtitle = "Manajemen pengiriman paket reguler (Koli & Dus)";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manajemen Kargo Reguler — LogiCargo System">
    <title>LogiCargo — Kargo Reguler</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <?php include 'includes/header.php'; ?>

        <div class="content-area">

            <!-- Breadcrumbs -->
            <div class="breadcrumb-container animate-fade-in-up">
                <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <span class="breadcrumb-separator"><i class="bi bi-chevron-right"></i></span>
                <span class="breadcrumb-current">Kargo Reguler</span>
            </div>

            <!-- Alert -->
            <?php if (!empty($alert_message)): ?>
                <div class="alert-banner alert-<?= $alert_type ?> animate-fade-in-up">
                    <i class="bi <?= $alert_type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' ?>"></i>
                    <span><?= $alert_message ?></span>
                    <button class="alert-close"><i class="bi bi-x-lg"></i></button>
                </div>
            <?php endif; ?>

            <!-- ═══════════════════════════════════════════════
                 KPI METRICS — Reguler Specific
                 ═══════════════════════════════════════════════ -->
            <div class="kpi-grid animate-fade-in-up animate-delay-1">
                <div class="kpi-card kpi-success">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-success"><i class="bi bi-box-seam-fill"></i></div>
                        <span class="kpi-badge kpi-badge-success"><i class="bi bi-check2"></i> Aktif</span>
                    </div>
                    <p class="kpi-value text-success counter-animate" data-target="<?= $total_reguler ?>">0</p>
                    <p class="kpi-label">Total Kargo Reguler</p>
                </div>

                <div class="kpi-card kpi-accent">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-accent"><i class="bi bi-cash-stack"></i></div>
                    </div>
                    <p class="kpi-value text-accent counter-animate" data-target="<?= round($total_revenue_reguler) ?>" data-prefix="Rp " data-format="true">Rp 0</p>
                    <p class="kpi-label">Revenue Reguler</p>
                </div>

                <div class="kpi-card kpi-info">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-info"><i class="bi bi-speedometer"></i></div>
                    </div>
                    <p class="kpi-value"><?= number_format($total_berat_reguler, 1, ',', '.') ?> <span class="fs-sm fw-medium text-muted">kg</span></p>
                    <p class="kpi-label">Total Berat Barang</p>
                </div>

                <div class="kpi-card kpi-warning">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-warning"><i class="bi bi-clock-history"></i></div>
                    </div>
                    <p class="kpi-value"><?= $avg_estimasi ?> <span class="fs-sm fw-medium text-muted">hari</span></p>
                    <p class="kpi-label">Rata-rata Estimasi</p>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════
                 DATA TABLE — Kargo Reguler
                 ═══════════════════════════════════════════════ -->
            <div class="section-card animate-fade-in-up animate-delay-3">
                <div class="section-header">
                    <div class="section-header-left">
                        <div class="section-header-icon" style="background: rgba(16,185,129,0.1); color: var(--clr-success); border-color: rgba(16,185,129,0.2);">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <div>
                            <h2 class="section-title">Daftar Kargo Reguler</h2>
                            <p class="section-subtitle">Subclass: KargoReguler — Rumus: Berat × tarifDasarPerKg</p>
                        </div>
                    </div>
                    <div class="filter-bar">
                        <input type="text" id="table-search" class="filter-input" placeholder="🔍 Cari...">
                        <a href="reservasi_baru.php" class="btn-logicargo btn-success btn-sm">
                            <i class="bi bi-plus-lg"></i> Tambah Reguler
                        </a>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="data-table" id="cargo-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Resi</th>
                                <th>Pengirim</th>
                                <th>Kota Tujuan</th>
                                <th>Berat (kg)</th>
                                <th>Jenis Paket</th>
                                <th>Estimasi</th>
                                <th>SOP Packing</th>
                                <th class="text-right">Total Tarif</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($total_reguler > 0): ?>
                                <?php $no = 1; foreach ($data_reguler as $item):
                                    $k = $item['kargo'];
                                    $tarif = $item['tarif'];
                                    $sop = $item['sop'];
                                ?>
                                <tr data-row
                                    data-search="<?= htmlspecialchars(strtolower($k->getIdResi() . ' ' . $k->getPengirim() . ' ' . $k->getKotaTujuan())) ?>">
                                    <td class="text-muted"><?= $no++ ?></td>
                                    <td><span class="fw-semibold cell-mono"><?= htmlspecialchars($k->getIdResi()) ?></span></td>
                                    <td><?= htmlspecialchars($k->getPengirim()) ?></td>
                                    <td><?= htmlspecialchars($k->getKotaTujuan()) ?></td>
                                    <td><?= number_format($k->getBeratBarang(), 2, ',', '.') ?></td>
                                    <td>
                                        <span class="badge-cargo badge-reguler">
                                            <i class="bi <?= $k->getJenisPaket() === 'Koli' ? 'bi-box' : 'bi-box-seam' ?>"></i>
                                            <?= htmlspecialchars($k->getJenisPaket()) ?>
                                        </span>
                                    </td>
                                    <td><?= $k->getEstimasiHari() ?> hari</td>
                                    <td>
                                        <div class="sop-tooltip">
                                            <span class="badge-sop badge-sop-valid">✅ Valid</span>
                                            <div class="sop-tooltip-text"><?= htmlspecialchars($sop) ?></div>
                                        </div>
                                    </td>
                                    <td class="text-right fw-bold">Rp <?= number_format($tarif, 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <div class="action-btn-group">
                                            <button class="action-btn btn-view" title="Lihat Detail" onclick="showToast('<?= htmlspecialchars($k->getIdResi()) ?> — <?= htmlspecialchars($k->getPengirim()) ?> → <?= htmlspecialchars($k->getKotaTujuan()) ?> | Tarif: Rp <?= number_format($tarif,0,',','.') ?>', 'info')">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="action-btn btn-delete" title="Void Shipment" onclick="confirmDelete('<?= htmlspecialchars($k->getIdResi()) ?>', '<?= htmlspecialchars(addslashes($k->getPengirim())) ?>')">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10">
                                        <div class="empty-state">
                                            <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                                            <p class="empty-state-title">Belum ada data Kargo Reguler</p>
                                            <p class="empty-state-text">Tambahkan data melalui <a href="reservasi_baru.php" class="text-accent fw-semibold">Reservasi Baru</a>.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div><!-- /content-area -->
    </div><!-- /main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
