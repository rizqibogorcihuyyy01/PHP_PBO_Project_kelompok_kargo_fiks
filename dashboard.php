<?php
/**
 * ============================================================================
 * DASHBOARD — Halaman Utama Admin Logistics
 * ============================================================================
 * 
 * Job 4 (Controller / Driver Specialist):
 * Dashboard utama menampilkan KPI operasional yang dihitung secara polimorfik
 * langsung dari database MySQL via Polymorphic Collection.
 * 
 * KPI Metrics:
 *   1. Total Active Shipments  — SELECT COUNT(*) FROM kargo
 *   2. Total Freight Revenue   — Akumulasi hitungTarifPengiriman() polimorfik
 *   3. Cargo Distribution      — Total per subclass (Reguler, Kimia, Pecah Belah)
 * 
 * Dispatch Table:
 *   - ID Resi, Pengirim, Kota Tujuan, Berat
 *   - Cargo Class Badge (Green/Red/Yellow)
 *   - Dynamic Specific Attributes
 *   - SOP Packing Status (validasiSOPPacking() abstraction)
 *   - Polymorphic Total Cost
 * 
 * @package  LogiCargo Dashboard
 * @version  1.0.0
 * ============================================================================
 */

// ── Job 1 (Database): Koneksi ke database ──
require_once 'config/koneksi.php';

// ── Job 4 (Controller): Load ManajemenKargo controller ──
require_once 'classes/ManajemenKargo.php';

// ── Inisialisasi Controller & Load Data ──
$manajemen = new ManajemenKargo($koneksi);

// ── Handle POST Actions (Delete) ──
$alert_message = "";
$alert_type = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_db_connected) {
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        $id_resi = trim($_POST['id_resi'] ?? '');
        if (!empty($id_resi) && $manajemen->hapusKargo($id_resi)) {
            $alert_message = "Kargo dengan ID Resi <strong>{$id_resi}</strong> berhasil dihapus (Void).";
            $alert_type = "success";
        } else {
            $alert_message = "Gagal menghapus kargo dengan ID Resi <strong>{$id_resi}</strong>.";
            $alert_type = "error";
        }
    }
}

// ── Job 3 (Business Logic): Load semua data kargo dari DB via OOP ──
$data_kargo = [];
if ($is_db_connected) {
    $manajemen->loadAllKargo();
    $data_kargo = $manajemen->getAllWithTarif();
}

// ── Hitung KPI Statistik — Polymorphic Calculation ──
$total_pengiriman = count($data_kargo);
$total_pendapatan = 0;
$count_reguler = 0;
$count_kimia   = 0;
$count_pecah   = 0;

// Job 3: PHP Loop — Polymorphic accumulation (Dynamic Binding)
foreach ($data_kargo as $item) {
    $total_pendapatan += $item['tarif']; // hitungTarifPengiriman() via Dynamic Binding
    
    switch ($item['jenis']) {
        case 'Reguler':     $count_reguler++; break;
        case 'Bahan Kimia': $count_kimia++;   break;
        case 'Pecah Belah': $count_pecah++;   break;
    }
}

// ── Collect unique cities for filter ──
$kota_list = [];
foreach ($data_kargo as $item) {
    $kota = $item['kargo']->getKotaTujuan();
    if (!in_array($kota, $kota_list)) {
        $kota_list[] = $kota;
    }
}
sort($kota_list);

// ── Page Meta ──
$page_title    = "Dashboard Utama";
$page_subtitle = "Sistem Manajemen Reservasi & Tarif Cargo Ekspedisi Logistik";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard Admin Sistem Manajemen Reservasi & Tarif Cargo Ekspedisi Logistik — LogiCargo System">
    <title>LogiCargo — Dashboard Admin</title>

    <!-- Google Fonts: Inter & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom CSS Design System -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    
    <!-- ═══════════════════════════════════════════════════════
         SIDEBAR NAVIGATION (Modular Include)
         ═══════════════════════════════════════════════════════ -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- ═══════════════════════════════════════════════════════
         MAIN CONTENT AREA
         ═══════════════════════════════════════════════════════ -->
    <div class="main-content">

        <!-- ── Top Header Bar ── -->
        <?php include 'includes/header.php'; ?>

        <!-- ── Content Area ── -->
        <div class="content-area">

            <!-- Alert Banner (PHP-driven) -->
            <?php if (!empty($alert_message)): ?>
                <div class="alert-banner alert-<?= $alert_type ?> animate-fade-in-up">
                    <i class="bi <?= $alert_type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' ?>"></i>
                    <span><?= $alert_message ?></span>
                    <button class="alert-close" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
                </div>
            <?php endif; ?>


            <!-- ═══════════════════════════════════════════════
                 SECTION 1: KPI METRIC CARDS
                 Job 3 (Business Logic): Polymorphic Calculations
                 ═══════════════════════════════════════════════ -->
            <div class="kpi-grid animate-fade-in-up animate-delay-1">

                <!-- Card: Total Resi Aktif -->
                <div class="kpi-card kpi-accent">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-accent">
                            <i class="bi bi-receipt-cutoff"></i>
                        </div>
                        <span class="kpi-badge kpi-badge-success">
                            <i class="bi bi-arrow-up-short"></i> Aktif
                        </span>
                    </div>
                    <p class="kpi-value counter-animate" data-target="<?= $total_pengiriman ?>" data-format="true">0</p>
                    <p class="kpi-label">Total Resi / Pengiriman</p>
                </div>

                <!-- Card: Total Pendapatan (Polymorphic) -->
                <div class="kpi-card kpi-success">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-success">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <span class="kpi-badge kpi-badge-info">
                            <i class="bi bi-calculator"></i> Polimorfisme
                        </span>
                    </div>
                    <p class="kpi-value text-success counter-animate" data-target="<?= round($total_pendapatan) ?>" data-prefix="Rp " data-format="true">Rp 0</p>
                    <p class="kpi-label">Total Pendapatan Cargo (Dynamic Binding)</p>
                </div>

                <!-- Card: Cargo Distribution -->
                <div class="kpi-card kpi-purple" style="grid-column: span 2;">
                    <div class="kpi-header">
                        <div class="kpi-icon icon-purple">
                            <i class="bi bi-boxes"></i>
                        </div>
                        <span class="kpi-label fw-semibold" style="text-transform: uppercase; letter-spacing: 0.08em;">Distribusi Tipe Cargo</span>
                    </div>
                    <div class="kpi-distribution">
                        <!-- Reguler -->
                        <div class="kpi-dist-item">
                            <div class="kpi-dist-bar-wrapper">
                                <div class="kpi-dist-bar bar-success" style="width: <?= $total_pengiriman > 0 ? round($count_reguler / $total_pengiriman * 100) : 0 ?>%"></div>
                            </div>
                            <p class="kpi-dist-value text-success counter-animate" data-target="<?= $count_reguler ?>">0</p>
                            <p class="kpi-dist-label">Reguler</p>
                        </div>
                        <!-- Bahan Kimia -->
                        <div class="kpi-dist-item">
                            <div class="kpi-dist-bar-wrapper">
                                <div class="kpi-dist-bar bar-danger" style="width: <?= $total_pengiriman > 0 ? round($count_kimia / $total_pengiriman * 100) : 0 ?>%"></div>
                            </div>
                            <p class="kpi-dist-value text-danger counter-animate" data-target="<?= $count_kimia ?>">0</p>
                            <p class="kpi-dist-label">Bahan Kimia</p>
                        </div>
                        <!-- Pecah Belah -->
                        <div class="kpi-dist-item">
                            <div class="kpi-dist-bar-wrapper">
                                <div class="kpi-dist-bar bar-warning" style="width: <?= $total_pengiriman > 0 ? round($count_pecah / $total_pengiriman * 100) : 0 ?>%"></div>
                            </div>
                            <p class="kpi-dist-value text-warning counter-animate" data-target="<?= $count_pecah ?>">0</p>
                            <p class="kpi-dist-label">Pecah Belah</p>
                        </div>
                    </div>
                </div>

            </div><!-- /kpi-grid -->


            <!-- ═══════════════════════════════════════════════
                 SECTION 2: DISPATCH & SOP STATUS TABLE
                 Job 4 (Controller): Polymorphic Collection Display
                 ═══════════════════════════════════════════════ -->
            <div class="section-card animate-fade-in-up animate-delay-3">
                <!-- Section Header + Filters -->
                <div class="section-header">
                    <div class="section-header-left">
                        <div class="section-header-icon">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                        <div>
                            <h2 class="section-title">Dispatch & SOP Status — Polymorphic Collection</h2>
                            <p class="section-subtitle">Menampilkan kalkulasi tarif logistik dinamis via Dynamic Binding</p>
                        </div>
                    </div>
                    <div class="filter-bar">
                        <input type="text" id="table-search" class="filter-input" placeholder="🔍 Cari resi, pengirim, kota...">
                        <select id="filter-jenis" class="filter-select">
                            <option value="">Semua Jenis</option>
                            <option value="Reguler">📦 Reguler</option>
                            <option value="Bahan Kimia">⚠️ Bahan Kimia</option>
                            <option value="Pecah Belah">🥚 Pecah Belah</option>
                        </select>
                        <select id="filter-kota" class="filter-select">
                            <option value="">Semua Kota</option>
                            <?php foreach ($kota_list as $kota): ?>
                                <option value="<?= htmlspecialchars($kota) ?>"><?= htmlspecialchars($kota) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span id="filter-count" class="fs-xs text-muted"><?= $total_pengiriman ?> data ditampilkan</span>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-wrapper">
                    <table class="data-table" id="cargo-table">
                        <thead>
                            <tr>
                                <th>ID Resi / Pengirim</th>
                                <th>Kota Tujuan / Berat</th>
                                <th>Jenis Kargo</th>
                                <th>Detail Spesifik</th>
                                <th>SOP Packing</th>
                                <th class="text-right">Total Tarif</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($total_pengiriman > 0): ?>
                                <?php foreach ($data_kargo as $item):
                                    $k = $item['kargo'];
                                    $tarif = $item['tarif'];
                                    $sop = $item['sop'];
                                    $jenis = $item['jenis'];

                                    // ── Badge Class berdasarkan jenis ──
                                    $badge_class = '';
                                    $badge_icon  = '';
                                    if ($jenis === 'Reguler') {
                                        $badge_class = 'badge-reguler';
                                        $badge_icon  = 'bi-box-seam-fill';
                                    } elseif ($jenis === 'Bahan Kimia') {
                                        $badge_class = 'badge-kimia';
                                        $badge_icon  = 'bi-exclamation-triangle-fill';
                                    } elseif ($jenis === 'Pecah Belah') {
                                        $badge_class = 'badge-pecah';
                                        $badge_icon  = 'bi-shield-exclamation';
                                    }

                                    // ── Detail Spesifik per tipe kargo (Job 2: OOP Attributes) ──
                                    $detail_html = '';
                                    if ($k instanceof KargoReguler) {
                                        $detail_html = '<span class="cell-primary">Paket: ' . htmlspecialchars($k->getJenisPaket()) . '</span>'
                                                     . '<span class="cell-secondary">Estimasi: ' . $k->getEstimasiHari() . ' hari</span>';
                                    } elseif ($k instanceof KargoBahanKimia) {
                                        $hazard_level = $k->getTingkatBahaya();
                                        $hazard_class = $hazard_level >= 7 ? 'badge-hazard-high' : ($hazard_level >= 4 ? 'badge-hazard-medium' : 'badge-hazard-low');
                                        $detail_html = '<span class="badge-hazard ' . $hazard_class . '">Lv.' . $hazard_level . '</span>'
                                                     . '<span class="cell-secondary">' . htmlspecialchars($k->getJenisSertifikasi()) . '</span>';
                                    } elseif ($k instanceof KargoPecahBelah) {
                                        $detail_html = '<span class="cell-primary">Bubble Wrap: ' . $k->getKetebalanBubbleWrap() . ' lapis</span>'
                                                     . '<span class="cell-secondary">Asuransi: Rp ' . number_format($k->getBiayaAsuransi(), 0, ',', '.') . '</span>';
                                    }

                                    // ── SOP Status Badge ──
                                    $sop_badge_class = 'badge-sop-valid';
                                    $sop_short = '✅ Valid';
                                    if ($k instanceof KargoBahanKimia) {
                                        if ($k->getTingkatBahaya() >= 7) {
                                            $sop_badge_class = 'badge-sop-danger';
                                            $sop_short = '⚠️ Bahaya Tinggi';
                                        } else {
                                            $sop_badge_class = 'badge-sop-warning';
                                            $sop_short = '⚠️ Penanganan Khusus';
                                        }
                                    } elseif ($k instanceof KargoPecahBelah) {
                                        if ($k->getKetebalanBubbleWrap() < 2) {
                                            $sop_badge_class = 'badge-sop-danger';
                                            $sop_short = '❌ Kurang';
                                        } else {
                                            $sop_badge_class = 'badge-sop-warning';
                                            $sop_short = '⚠️ Fragile';
                                        }
                                    }
                                ?>
                                <tr data-row
                                    data-jenis="<?= htmlspecialchars($jenis) ?>"
                                    data-kota="<?= htmlspecialchars($k->getKotaTujuan()) ?>"
                                    data-search="<?= htmlspecialchars(strtolower($k->getIdResi() . ' ' . $k->getPengirim() . ' ' . $k->getKotaTujuan() . ' ' . $jenis)) ?>">
                                    <!-- ID Resi / Pengirim -->
                                    <td>
                                        <span class="cell-primary cell-mono"><?= htmlspecialchars($k->getIdResi()) ?></span>
                                        <span class="cell-secondary"><?= htmlspecialchars($k->getPengirim()) ?></span>
                                    </td>
                                    <!-- Kota Tujuan / Berat -->
                                    <td>
                                        <span class="cell-primary"><?= htmlspecialchars($k->getKotaTujuan()) ?></span>
                                        <span class="cell-secondary"><?= number_format($k->getBeratBarang(), 2, ',', '.') ?> kg</span>
                                    </td>
                                    <!-- Jenis Kargo Badge -->
                                    <td>
                                        <span class="badge-cargo <?= $badge_class ?>">
                                            <i class="bi <?= $badge_icon ?>"></i> <?= $jenis ?>
                                        </span>
                                    </td>
                                    <!-- Detail Spesifik -->
                                    <td><?= $detail_html ?></td>
                                    <!-- SOP Packing Status -->
                                    <td>
                                        <div class="sop-tooltip">
                                            <span class="badge-sop <?= $sop_badge_class ?>"><?= $sop_short ?></span>
                                            <div class="sop-tooltip-text"><?= htmlspecialchars($sop) ?></div>
                                        </div>
                                    </td>
                                    <!-- Total Tarif (Polymorphic) -->
                                    <td class="text-right">
                                        <span class="fw-bold">Rp <?= number_format($tarif, 0, ',', '.') ?></span>
                                    </td>
                                    <!-- Actions -->
                                    <td class="text-center">
                                        <div class="action-btn-group">
                                            <button class="action-btn btn-view" title="Lihat Detail" onclick="showToast('Detail <?= htmlspecialchars($k->getIdResi()) ?> — Total: Rp <?= number_format($tarif, 0, ',', '.') ?>', 'info')">
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
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                                            <p class="empty-state-title">Belum ada data kargo</p>
                                            <p class="empty-state-text">
                                                <?php if (!$is_db_connected): ?>
                                                    Database tidak terhubung. Pastikan MySQL berjalan dan database <strong>db_kargo_ekspedisi</strong> sudah diimport.
                                                <?php else: ?>
                                                    Silakan tambahkan data kargo baru melalui halaman <a href="reservasi_baru.php" class="text-accent fw-semibold">Reservasi Baru</a>.
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div><!-- /section-card -->

        </div><!-- /content-area -->
    </div><!-- /main-content -->

    <!-- Bootstrap 5 JS Bundle (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="assets/js/script.js"></script>
</body>
</html>
