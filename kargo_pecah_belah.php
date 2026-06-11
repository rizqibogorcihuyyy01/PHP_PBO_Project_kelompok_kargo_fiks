<?php
/**
 * ============================================================================
 * KARGO PECAH BELAH — Management Page
 * ============================================================================
 * 
 * Job 4 (Controller / Driver): Halaman manajemen untuk Kargo Pecah Belah.
 * 
 * Subclass: KargoPecahBelah extends Kargo
 *   - Atribut: ketebalanBubbleWrap (lapis), biayaAsuransiWajib (Rp)
 *   - Rumus Tarif: (Berat × tarifDasarPerKg) + biayaAsuransiWajib + 5% Surcharge
 *   - SOP: Validasi berdasarkan ketebalan bubble wrap
 * 
 * @package  LogiCargo Dashboard
 * @version  1.0.0
 * ============================================================================
 */

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
            $alert_message = "Kargo Pecah Belah <strong>{$id_resi}</strong> berhasil di-void.";
            $alert_type = "success";
        } else {
            $alert_message = "Gagal menghapus kargo <strong>{$id_resi}</strong>.";
            $alert_type = "error";
        }
    }
}

// ── Load Data: Hanya Kargo Pecah Belah ──
$data_pecah = [];
$total_pecah = 0;
$total_revenue_pecah = 0;
$total_berat_pecah = 0;
$total_asuransi = 0;
$avg_bubble = 0;

if ($is_db_connected) {
    $manajemen->loadAllKargo();
    $all_data = $manajemen->getAllWithTarif();
    
    foreach ($all_data as $item) {
        if ($item['jenis'] === 'Pecah Belah') {
            $data_pecah[] = $item;
            $total_revenue_pecah += $item['tarif'];
            $total_berat_pecah   += $item['kargo']->getBeratBarang();
            $total_asuransi      += $item['kargo']->getBiayaAsuransi();
            $avg_bubble          += $item['kargo']->getKetebalanBubbleWrap();
        }
    }
    $total_pecah = count($data_pecah);
    $avg_bubble  = $total_pecah > 0 ? round($avg_bubble / $total_pecah, 1) : 0;
}

$page_title    = "Kargo Pecah Belah";
$page_subtitle = "Manajemen pengiriman barang fragile dengan proteksi ekstra";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manajemen Kargo Pecah Belah — LogiCargo System">
    <title>LogiCargo — Kargo Pecah Belah</title>

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
                <span class="breadcrumb-current">Kargo Pecah Belah</span>
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
                 KPI METRICS — Pecah Belah Specific
                 ═══════════════════════════════════════════════ -->
            <div class="kpi-grid animate-fade-in-up animate-delay-1">
                <div class="kpi-card kpi-warning">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-warning"><i class="bi bi-shield-exclamation"></i></div>
                        <span class="kpi-badge" style="color: var(--clr-warning); background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.15);">
                            <i class="bi bi-exclamation-diamond"></i> Fragile
                        </span>
                    </div>
                    <p class="kpi-value text-warning counter-animate" data-target="<?= $total_pecah ?>">0</p>
                    <p class="kpi-label">Total Kargo Pecah Belah</p>
                </div>

                <div class="kpi-card kpi-accent">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-accent"><i class="bi bi-cash-stack"></i></div>
                    </div>
                    <p class="kpi-value text-accent counter-animate" data-target="<?= round($total_revenue_pecah) ?>" data-prefix="Rp " data-format="true">Rp 0</p>
                    <p class="kpi-label">Revenue Pecah Belah</p>
                </div>

                <div class="kpi-card kpi-info">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-info"><i class="bi bi-shield-check"></i></div>
                    </div>
                    <p class="kpi-value counter-animate" data-target="<?= round($total_asuransi) ?>" data-prefix="Rp " data-format="true">Rp 0</p>
                    <p class="kpi-label">Total Biaya Asuransi Wajib</p>
                </div>

                <div class="kpi-card kpi-purple">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-purple"><i class="bi bi-layers-fill"></i></div>
                    </div>
                    <p class="kpi-value"><?= $avg_bubble ?> <span class="fs-sm fw-medium text-muted">lapis</span></p>
                    <p class="kpi-label">Rata-rata Bubble Wrap</p>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════
                 DATA TABLE — Kargo Pecah Belah
                 ═══════════════════════════════════════════════ -->
            <div class="section-card animate-fade-in-up animate-delay-3">
                <div class="section-header">
                    <div class="section-header-left">
                        <div class="section-header-icon" style="background: rgba(245,158,11,0.1); color: var(--clr-warning); border-color: rgba(245,158,11,0.2);">
                            <i class="bi bi-shield-exclamation"></i>
                        </div>
                        <div>
                            <h2 class="section-title">Daftar Kargo Pecah Belah</h2>
                            <p class="section-subtitle">Subclass: KargoPecahBelah — Rumus: (Berat × tarif) + Asuransi + 5% Surcharge</p>
                        </div>
                    </div>
                    <div class="filter-bar">
                        <input type="text" id="table-search" class="filter-input" placeholder="🔍 Cari...">
                        <a href="reservasi_baru.php" class="btn-logicargo btn-sm" style="background: linear-gradient(135deg, var(--clr-warning), var(--clr-warning-dark)); color: #fff; border-color: var(--clr-warning);">
                            <i class="bi bi-plus-lg"></i> Tambah Pecah Belah
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
                                <th>Bubble Wrap</th>
                                <th>Asuransi Wajib</th>
                                <th>SOP Packing</th>
                                <th class="text-right">Total Tarif</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($total_pecah > 0): ?>
                                <?php $no = 1; foreach ($data_pecah as $item):
                                    $k = $item['kargo'];
                                    $tarif = $item['tarif'];
                                    $sop = $item['sop'];
                                    $bubble = $k->getKetebalanBubbleWrap();
                                    
                                    // SOP Badge
                                    $sop_class = 'badge-sop-valid';
                                    $sop_short = '✅ Premium';
                                    if ($bubble < 2) {
                                        $sop_class = 'badge-sop-danger';
                                        $sop_short = '❌ Kurang';
                                    } elseif ($bubble < 4) {
                                        $sop_class = 'badge-sop-warning';
                                        $sop_short = '⚠️ Standar';
                                    }
                                ?>
                                <tr data-row
                                    data-search="<?= htmlspecialchars(strtolower($k->getIdResi() . ' ' . $k->getPengirim() . ' ' . $k->getKotaTujuan())) ?>">
                                    <td class="text-muted"><?= $no++ ?></td>
                                    <td><span class="fw-semibold cell-mono"><?= htmlspecialchars($k->getIdResi()) ?></span></td>
                                    <td><?= htmlspecialchars($k->getPengirim()) ?></td>
                                    <td><?= htmlspecialchars($k->getKotaTujuan()) ?></td>
                                    <td><?= number_format($k->getBeratBarang(), 2, ',', '.') ?></td>
                                    <td>
                                        <span class="badge-cargo badge-pecah">
                                            <i class="bi bi-layers-fill"></i> <?= $bubble ?> lapis
                                        </span>
                                    </td>
                                    <td>Rp <?= number_format($k->getBiayaAsuransi(), 0, ',', '.') ?></td>
                                    <td>
                                        <div class="sop-tooltip">
                                            <span class="badge-sop <?= $sop_class ?>"><?= $sop_short ?></span>
                                            <div class="sop-tooltip-text"><?= htmlspecialchars($sop) ?></div>
                                        </div>
                                    </td>
                                    <td class="text-right fw-bold">Rp <?= number_format($tarif, 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <div class="action-btn-group">
                                            <button class="action-btn btn-view" title="Lihat Detail" onclick="showToast('<?= htmlspecialchars($k->getIdResi()) ?> — Bubble: <?= $bubble ?> lapis | Asuransi: Rp <?= number_format($k->getBiayaAsuransi(),0,',','.') ?> | Tarif: Rp <?= number_format($tarif,0,',','.') ?>', 'info')">
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
                                            <div class="empty-state-icon"><i class="bi bi-shield-exclamation"></i></div>
                                            <p class="empty-state-title">Belum ada data Kargo Pecah Belah</p>
                                            <p class="empty-state-text">Tambahkan data melalui <a href="reservasi_baru.php" class="text-accent fw-semibold">Reservasi Baru</a>.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
