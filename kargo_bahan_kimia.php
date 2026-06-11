<?php
/**
 * ============================================================================
 * KARGO BAHAN KIMIA — Management Page
 * ============================================================================
 * 
 * Job 4 (Controller / Driver): Halaman manajemen untuk Kargo Bahan Kimia.
 * 
 * Subclass: KargoBahanKimia extends Kargo
 *   - Atribut: tingkatBahaya (1-9), jenisSertifikasiSandi, biayaPenangananKhusus
 *   - Rumus Tarif: (Berat × tarifDasarPerKg) + (TingkatBahaya × 100.000)
 *   - SOP: Validasi berdasarkan tingkat bahaya & sertifikasi
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
            $alert_message = "Kargo Bahan Kimia <strong>{$id_resi}</strong> berhasil di-void.";
            $alert_type = "success";
        } else {
            $alert_message = "Gagal menghapus kargo <strong>{$id_resi}</strong>.";
            $alert_type = "error";
        }
    }
}

// ── Load Data: Hanya Kargo Bahan Kimia ──
$data_kimia = [];
$total_kimia = 0;
$total_revenue_kimia = 0;
$total_berat_kimia = 0;
$avg_bahaya = 0;
$count_high_risk = 0;

if ($is_db_connected) {
    $manajemen->loadAllKargo();
    $all_data = $manajemen->getAllWithTarif();
    
    foreach ($all_data as $item) {
        if ($item['jenis'] === 'Bahan Kimia') {
            $data_kimia[] = $item;
            $total_revenue_kimia += $item['tarif'];
            $total_berat_kimia   += $item['kargo']->getBeratBarang();
            $avg_bahaya          += $item['kargo']->getTingkatBahaya();
            if ($item['kargo']->getTingkatBahaya() >= 7) $count_high_risk++;
        }
    }
    $total_kimia = count($data_kimia);
    $avg_bahaya  = $total_kimia > 0 ? round($avg_bahaya / $total_kimia, 1) : 0;
}

$page_title    = "Kargo Bahan Kimia";
$page_subtitle = "Manajemen pengiriman bahan kimia berbahaya (Hazard Class 1-9)";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manajemen Kargo Bahan Kimia — LogiCargo System">
    <title>LogiCargo — Kargo Bahan Kimia</title>

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
                <span class="breadcrumb-current">Kargo Bahan Kimia</span>
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
                 KPI METRICS — Bahan Kimia Specific
                 ═══════════════════════════════════════════════ -->
            <div class="kpi-grid animate-fade-in-up animate-delay-1">
                <div class="kpi-card kpi-danger">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-danger"><i class="bi bi-exclamation-triangle-fill"></i></div>
                        <span class="kpi-badge" style="color: var(--clr-danger); background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15);">
                            <i class="bi bi-radioactive"></i> Hazardous
                        </span>
                    </div>
                    <p class="kpi-value text-danger counter-animate" data-target="<?= $total_kimia ?>">0</p>
                    <p class="kpi-label">Total Kargo Bahan Kimia</p>
                </div>

                <div class="kpi-card kpi-accent">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-accent"><i class="bi bi-cash-stack"></i></div>
                    </div>
                    <p class="kpi-value text-accent counter-animate" data-target="<?= round($total_revenue_kimia) ?>" data-prefix="Rp " data-format="true">Rp 0</p>
                    <p class="kpi-label">Revenue Bahan Kimia</p>
                </div>

                <div class="kpi-card kpi-warning">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-warning"><i class="bi bi-shield-fill-exclamation"></i></div>
                    </div>
                    <p class="kpi-value text-warning"><?= $avg_bahaya ?> <span class="fs-sm fw-medium text-muted">/ 9</span></p>
                    <p class="kpi-label">Rata-rata Tingkat Bahaya</p>
                </div>

                <div class="kpi-card kpi-danger">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-danger"><i class="bi bi-fire"></i></div>
                    </div>
                    <p class="kpi-value text-danger counter-animate" data-target="<?= $count_high_risk ?>">0</p>
                    <p class="kpi-label">High Risk (Lv.7+)</p>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════
                 DATA TABLE — Kargo Bahan Kimia
                 ═══════════════════════════════════════════════ -->
            <div class="section-card animate-fade-in-up animate-delay-3">
                <div class="section-header">
                    <div class="section-header-left">
                        <div class="section-header-icon" style="background: rgba(239,68,68,0.1); color: var(--clr-danger); border-color: rgba(239,68,68,0.2);">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div>
                            <h2 class="section-title">Daftar Kargo Bahan Kimia</h2>
                            <p class="section-subtitle">Subclass: KargoBahanKimia — Rumus: (Berat × tarif) + (TingkatBahaya × 100.000)</p>
                        </div>
                    </div>
                    <div class="filter-bar">
                        <input type="text" id="table-search" class="filter-input" placeholder="🔍 Cari...">
                        <a href="reservasi_baru.php" class="btn-logicargo btn-danger btn-sm">
                            <i class="bi bi-plus-lg"></i> Tambah Kimia
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
                                <th>Tingkat Bahaya</th>
                                <th>Sertifikasi Sandi</th>
                                <th>SOP Packing</th>
                                <th class="text-right">Total Tarif</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($total_kimia > 0): ?>
                                <?php $no = 1; foreach ($data_kimia as $item):
                                    $k = $item['kargo'];
                                    $tarif = $item['tarif'];
                                    $sop = $item['sop'];
                                    $hazard = $k->getTingkatBahaya();
                                    $hazard_class = $hazard >= 7 ? 'badge-hazard-high' : ($hazard >= 4 ? 'badge-hazard-medium' : 'badge-hazard-low');
                                    
                                    // SOP Badge
                                    $sop_class = 'badge-sop-valid';
                                    $sop_short = '✅ Valid';
                                    if ($hazard >= 7) {
                                        $sop_class = 'badge-sop-danger';
                                        $sop_short = '⚠️ Bahaya Tinggi';
                                    } elseif ($hazard >= 4) {
                                        $sop_class = 'badge-sop-warning';
                                        $sop_short = '⚠️ Sedang';
                                    }
                                ?>
                                <tr data-row
                                    data-search="<?= htmlspecialchars(strtolower($k->getIdResi() . ' ' . $k->getPengirim() . ' ' . $k->getKotaTujuan() . ' ' . $k->getJenisSertifikasi())) ?>">
                                    <td class="text-muted"><?= $no++ ?></td>
                                    <td><span class="fw-semibold cell-mono"><?= htmlspecialchars($k->getIdResi()) ?></span></td>
                                    <td><?= htmlspecialchars($k->getPengirim()) ?></td>
                                    <td><?= htmlspecialchars($k->getKotaTujuan()) ?></td>
                                    <td><?= number_format($k->getBeratBarang(), 2, ',', '.') ?></td>
                                    <td>
                                        <span class="badge-hazard <?= $hazard_class ?>">
                                            <i class="bi bi-radioactive"></i> Class <?= $hazard ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="cell-mono fs-xs"><?= htmlspecialchars($k->getJenisSertifikasi()) ?></span>
                                    </td>
                                    <td>
                                        <div class="sop-tooltip">
                                            <span class="badge-sop <?= $sop_class ?>"><?= $sop_short ?></span>
                                            <div class="sop-tooltip-text"><?= htmlspecialchars($sop) ?></div>
                                        </div>
                                    </td>
                                    <td class="text-right fw-bold">Rp <?= number_format($tarif, 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <div class="action-btn-group">
                                            <button class="action-btn btn-view" title="Lihat Detail" onclick="showToast('<?= htmlspecialchars($k->getIdResi()) ?> — Bahaya Lv.<?= $hazard ?> | <?= htmlspecialchars($k->getJenisSertifikasi()) ?> | Tarif: Rp <?= number_format($tarif,0,',','.') ?>', 'info')">
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
                                            <div class="empty-state-icon"><i class="bi bi-radioactive"></i></div>
                                            <p class="empty-state-title">Belum ada data Kargo Bahan Kimia</p>
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
