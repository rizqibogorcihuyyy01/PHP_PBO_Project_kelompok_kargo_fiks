<?php
/**
 * ============================================================================
 * RESERVASI BARU — Unified Manifest Booking Form
 * ============================================================================
 * 
 * Job 4 (Controller / Driver): Halaman form reservasi kargo baru.
 * Form ini mendukung conditional UI state toggles berdasarkan jenis kargo
 * yang dipilih, sehingga operator dapat memasukkan parameter penanganan
 * spesifik sebelum menyimpan.
 * 
 * Fitur:
 *   - Unified form untuk semua jenis kargo
 *   - Dynamic field switching (Reguler / Kimia / Pecah Belah)
 *   - Client-side validation + Server-side validation
 *   - Auto-redirect ke halaman detail setelah berhasil
 * 
 * @package  LogiCargo Dashboard
 * @version  1.0.0
 * ============================================================================
 */

require_once 'config/koneksi.php';
require_once 'classes/ManajemenKargo.php';

$manajemen = new ManajemenKargo($koneksi);

// ── Handle POST: Create New Reservation ──
$alert_message = "";
$alert_type = "";
$form_data = []; // Preserve form data on validation error

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_db_connected) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        // Job 1 (Database): Collect form inputs
        $id_resi      = trim($_POST['id_resi'] ?? '');
        $pengirim     = trim($_POST['pengirim'] ?? '');
        $kota_tujuan  = trim($_POST['kota_tujuan'] ?? '');
        $berat_barang = floatval($_POST['berat_barang'] ?? 0);
        $tarif_dasar  = floatval($_POST['tarif_dasar_per_kg'] ?? 0);
        $jenis_kargo  = $_POST['jenis_kargo'] ?? '';

        // Preserve form data
        $form_data = $_POST;

        // Server-side validation
        if (empty($id_resi) || empty($pengirim) || empty($kota_tujuan) || $berat_barang <= 0 || $tarif_dasar <= 0 || empty($jenis_kargo)) {
            $alert_message = "Semua field dasar wajib diisi dengan benar!";
            $alert_type = "error";
        } else {
            // Job 2 (Abstraction): Instantiate appropriate subclass
            $kargo = null;

            if ($jenis_kargo === 'reguler') {
                $jenis_paket  = $_POST['jenis_paket'] ?? 'Dus';
                $estimasi_hari = intval($_POST['estimasi_hari'] ?? 1);
                if ($estimasi_hari < 1) $estimasi_hari = 1;
                
                $kargo = new KargoReguler(
                    $id_resi, $pengirim, $kota_tujuan, $berat_barang,
                    $tarif_dasar, $jenis_paket, $estimasi_hari
                );
            } elseif ($jenis_kargo === 'kimia') {
                $tingkat_bahaya = intval($_POST['tingkat_bahaya'] ?? 1);
                $sertifikasi    = trim($_POST['jenis_sertifikasi_sandi'] ?? '');
                
                // Validasi Hazard Class 1-9
                if ($tingkat_bahaya < 1 || $tingkat_bahaya > 9) {
                    $alert_message = "Tingkat Bahaya harus antara 1-9 (GHS Classification)!";
                    $alert_type = "error";
                } elseif (empty($sertifikasi)) {
                    $alert_message = "Jenis Sertifikasi Sandi wajib diisi untuk Bahan Kimia!";
                    $alert_type = "error";
                } else {
                    $biaya_penanganan = $tingkat_bahaya * 100000;
                    $kargo = new KargoBahanKimia(
                        $id_resi, $pengirim, $kota_tujuan, $berat_barang,
                        $tarif_dasar, $tingkat_bahaya, $sertifikasi, $biaya_penanganan
                    );
                }
            } elseif ($jenis_kargo === 'pecah') {
                $ketebalan = intval($_POST['ketebalan_bubble_wrap'] ?? 1);
                $asuransi  = floatval($_POST['biaya_asuransi_wajib'] ?? 0);
                
                // Validasi minimum values
                if ($ketebalan < 1) {
                    $alert_message = "Ketebalan Bubble Wrap minimal 1 lapis!";
                    $alert_type = "error";
                } elseif ($asuransi < 0) {
                    $alert_message = "Biaya Asuransi Wajib tidak boleh negatif!";
                    $alert_type = "error";
                } else {
                    $kargo = new KargoPecahBelah(
                        $id_resi, $pengirim, $kota_tujuan, $berat_barang,
                        $tarif_dasar, $ketebalan, $asuransi
                    );
                }
            }

            // Job 4 (Controller): Simpan ke database
            if ($kargo && empty($alert_message)) {
                if ($manajemen->tambahKargo($kargo)) {
                    $alert_message = "✅ Kargo baru <strong>{$id_resi}</strong> berhasil didaftarkan! Tarif: <strong>Rp " . number_format($kargo->hitungTarifPengiriman(), 0, ',', '.') . "</strong>";
                    $alert_type = "success";
                    $form_data = []; // Clear form on success
                } else {
                    $alert_message = "Gagal mendaftarkan kargo. ID Resi <strong>{$id_resi}</strong> mungkin sudah terdaftar.";
                    $alert_type = "error";
                }
            }
        }
    }
}

$page_title    = "Reservasi Baru";
$page_subtitle = "Form pendaftaran kargo baru — Unified Manifest Booking";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Reservasi Kargo Baru — LogiCargo System">
    <title>LogiCargo — Reservasi Baru</title>

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
                <span class="breadcrumb-current">Reservasi Baru</span>
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
                 UNIFIED MANIFEST BOOKING FORM
                 Job 2 (Abstraction): OOP-integrated form
                 Job 4 (Controller): Dynamic field switching
                 ═══════════════════════════════════════════════ -->
            <div class="section-card animate-fade-in-up animate-delay-2">
                <div class="section-header">
                    <div class="section-header-left">
                        <div class="section-header-icon" style="background: rgba(16,185,129,0.1); color: var(--clr-success); border-color: rgba(16,185,129,0.2);">
                            <i class="bi bi-plus-circle-fill"></i>
                        </div>
                        <div>
                            <h2 class="section-title">Form Reservasi Cargo Baru</h2>
                            <p class="section-subtitle">Input data pengiriman — Atribut OOP terintegrasi dengan Database</p>
                        </div>
                    </div>
                </div>

                <div class="section-body">
                    <?php if (!$is_db_connected): ?>
                        <div class="alert-banner alert-error">
                            <i class="bi bi-database-x"></i>
                            <span>Database tidak terhubung. Form tidak dapat diproses. Pastikan MySQL berjalan dan database <strong>db_kargo_ekspedisi</strong> sudah diimport.</span>
                        </div>
                    <?php else: ?>

                    <form id="form-reservasi" method="POST" action="reservasi_baru.php">
                        <input type="hidden" name="action" value="create">

                        <!-- ── Row 1: Informasi Dasar Kargo ── -->
                        <!-- Job 2: Atribut dari Abstract Class Kargo -->
                        <div class="mb-4">
                            <p class="fs-xs fw-bold text-accent mb-3" style="text-transform: uppercase; letter-spacing: 0.1em;">
                                <i class="bi bi-database-fill me-1"></i> Informasi Dasar (Abstract Class: Kargo)
                            </p>
                            <div class="form-grid form-grid-3">
                                <div class="form-group">
                                    <label class="form-label" for="input-id-resi">ID Resi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control-custom" id="input-id-resi" name="id_resi" 
                                           placeholder="e.g. KRG-091" required
                                           value="<?= htmlspecialchars($form_data['id_resi'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="input-pengirim">Nama Pengirim <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control-custom" id="input-pengirim" name="pengirim" 
                                           placeholder="e.g. PT Maju Jaya" required
                                           value="<?= htmlspecialchars($form_data['pengirim'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="input-kota">Kota Tujuan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control-custom" id="input-kota" name="kota_tujuan" 
                                           placeholder="e.g. Jakarta" required
                                           value="<?= htmlspecialchars($form_data['kota_tujuan'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- ── Row 2: Numerik & Jenis Kargo ── -->
                        <div class="mb-4">
                            <div class="form-grid form-grid-3">
                                <div class="form-group">
                                    <label class="form-label" for="input-berat">Berat Barang (kg) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control-custom" id="input-berat" name="berat_barang" 
                                           placeholder="0.00" min="0.01" step="0.01" required
                                           value="<?= htmlspecialchars($form_data['berat_barang'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="input-tarif">Tarif Dasar Per Kg (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control-custom" id="input-tarif" name="tarif_dasar_per_kg" 
                                           placeholder="15000" min="100" required
                                           value="<?= htmlspecialchars($form_data['tarif_dasar_per_kg'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="select-jenis-kargo">Jenis Kargo <span class="text-danger">*</span></label>
                                    <select class="form-control-custom" id="select-jenis-kargo" name="jenis_kargo" 
                                            onchange="toggleDynamicFields()" required>
                                        <option value="" disabled <?= empty($form_data['jenis_kargo'] ?? '') ? 'selected' : '' ?>>— Pilih Jenis Kargo —</option>
                                        <option value="reguler" <?= ($form_data['jenis_kargo'] ?? '') === 'reguler' ? 'selected' : '' ?>>📦 Kargo Reguler</option>
                                        <option value="kimia" <?= ($form_data['jenis_kargo'] ?? '') === 'kimia' ? 'selected' : '' ?>>⚠️ Kargo Bahan Kimia</option>
                                        <option value="pecah" <?= ($form_data['jenis_kargo'] ?? '') === 'pecah' ? 'selected' : '' ?>>🥚 Kargo Pecah Belah</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- ═════════════════════════════════════════
                             DYNAMIC FIELDS — Conditional UI Toggles
                             Job 2: Subclass-specific attributes
                             ═════════════════════════════════════════ -->

                        <!-- ── Dynamic Fields: Kargo Reguler ── -->
                        <!-- Subclass: KargoReguler (+jenisPaket, +estimasiHari) -->
                        <div id="fields-reguler" class="dynamic-fields dynamic-fields-reguler <?= ($form_data['jenis_kargo'] ?? '') === 'reguler' ? 'open' : '' ?>">
                            <p class="dynamic-fields-title text-success">
                                <i class="bi bi-check-circle-fill"></i> Atribut Tambahan: Kargo Reguler
                            </p>
                            <div class="form-grid form-grid-2">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="input-jenis-paket">Jenis Paket</label>
                                    <select class="form-control-custom" id="input-jenis-paket" name="jenis_paket">
                                        <option value="Dus" <?= ($form_data['jenis_paket'] ?? '') === 'Dus' ? 'selected' : '' ?>>📦 Dus</option>
                                        <option value="Koli" <?= ($form_data['jenis_paket'] ?? '') === 'Koli' ? 'selected' : '' ?>>📦 Koli</option>
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label" for="input-estimasi">Estimasi Hari Pengiriman</label>
                                    <input type="number" class="form-control-custom" id="input-estimasi" name="estimasi_hari" 
                                           placeholder="3" min="1" max="30"
                                           value="<?= htmlspecialchars($form_data['estimasi_hari'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- ── Dynamic Fields: Kargo Bahan Kimia ── -->
                        <!-- Subclass: KargoBahanKimia (+tingkatBahaya, +jenisSertifikasiSandi) -->
                        <div id="fields-kimia" class="dynamic-fields dynamic-fields-kimia <?= ($form_data['jenis_kargo'] ?? '') === 'kimia' ? 'open' : '' ?>">
                            <p class="dynamic-fields-title text-danger">
                                <i class="bi bi-exclamation-triangle-fill"></i> Atribut Tambahan: Kargo Bahan Kimia
                            </p>
                            <div class="form-grid form-grid-2">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="input-bahaya">Tingkat Bahaya (Hazard Class 1-9) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control-custom" id="input-bahaya" name="tingkat_bahaya" 
                                           placeholder="1 — 9" min="1" max="9"
                                           value="<?= htmlspecialchars($form_data['tingkat_bahaya'] ?? '') ?>">
                                    <small class="text-muted fs-xs mt-1 d-block">GHS Classification: 1=Explosive, 2=Gas, 3=Flammable, 4=Solid, 5=Oxidizer, 6=Toxic, 7=Radioactive, 8=Corrosive, 9=Misc</small>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label" for="input-sertifikasi">Jenis Sertifikasi Sandi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control-custom" id="input-sertifikasi" name="jenis_sertifikasi_sandi" 
                                           placeholder="e.g. UN-1789-CORROSIVE"
                                           value="<?= htmlspecialchars($form_data['jenis_sertifikasi_sandi'] ?? '') ?>">
                                    <small class="text-muted fs-xs mt-1 d-block">Format: MSDS-[TYPE]-[CODE] atau UN-[NUMBER]-[CLASS]</small>
                                </div>
                            </div>
                        </div>

                        <!-- ── Dynamic Fields: Kargo Pecah Belah ── -->
                        <!-- Subclass: KargoPecahBelah (+ketebalanBubbleWrap, +biayaAsuransiWajib) -->
                        <div id="fields-pecah" class="dynamic-fields dynamic-fields-pecah <?= ($form_data['jenis_kargo'] ?? '') === 'pecah' ? 'open' : '' ?>">
                            <p class="dynamic-fields-title text-warning">
                                <i class="bi bi-shield-exclamation"></i> Atribut Tambahan: Kargo Pecah Belah
                            </p>
                            <div class="form-grid form-grid-2">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="input-bubble">Ketebalan Bubble Wrap (lapis) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control-custom" id="input-bubble" name="ketebalan_bubble_wrap" 
                                           placeholder="1 — 5" min="1" max="10"
                                           value="<?= htmlspecialchars($form_data['ketebalan_bubble_wrap'] ?? '') ?>">
                                    <small class="text-muted fs-xs mt-1 d-block">Min. 2 lapis = Standar | Min. 4 lapis = Premium</small>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label" for="input-asuransi">Biaya Asuransi Wajib (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control-custom" id="input-asuransi" name="biaya_asuransi_wajib" 
                                           placeholder="50000" min="0"
                                           value="<?= htmlspecialchars($form_data['biaya_asuransi_wajib'] ?? '') ?>">
                                    <small class="text-muted fs-xs mt-1 d-block">Biaya asuransi wajib untuk barang pecah belah</small>
                                </div>
                            </div>
                        </div>

                        <!-- ── Action Buttons ── -->
                        <div class="d-flex align-center gap-3 mt-4" style="flex-wrap: wrap;">
                            <button type="submit" class="btn-logicargo btn-primary">
                                <i class="bi bi-send-fill"></i> Simpan Reservasi
                            </button>
                            <button type="reset" class="btn-logicargo btn-outline" onclick="resetDynamicFields()">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset Form
                            </button>
                            <a href="dashboard.php" class="btn-logicargo btn-outline">
                                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                            </a>
                        </div>

                    </form>

                    <?php endif; ?>
                </div>
            </div>


            <!-- ═══════════════════════════════════════════════
                 TARIFF FORMULA REFERENCE CARD
                 Job 3 (Business Logic): Quick reference for operators
                 ═══════════════════════════════════════════════ -->
            <div class="section-card animate-fade-in-up animate-delay-4">
                <div class="section-header">
                    <div class="section-header-left">
                        <div class="section-header-icon" style="background: rgba(139,92,246,0.1); color: var(--clr-purple); border-color: rgba(139,92,246,0.2);">
                            <i class="bi bi-calculator-fill"></i>
                        </div>
                        <div>
                            <h2 class="section-title">Referensi Rumus Tarif — Polymorphism Overriding</h2>
                            <p class="section-subtitle">Job 3 (Business Logic Specialist): Kalkulasi tarif per subclass</p>
                        </div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="form-grid form-grid-3">
                        <!-- Reguler -->
                        <div style="padding: 1.25rem; border-radius: var(--radius-xl); background: rgba(16,185,129,0.04); border: 1px solid rgba(16,185,129,0.15);">
                            <p class="fw-bold text-success mb-2"><i class="bi bi-box-seam-fill me-1"></i> Kargo Reguler</p>
                            <p class="fs-sm text-muted mb-2">hitungTarifPengiriman()</p>
                            <p class="fw-semibold fs-sm" style="font-family: 'JetBrains Mono', monospace; color: var(--clr-text);">
                                Total = Berat × tarifDasarPerKg
                            </p>
                        </div>
                        <!-- Kimia -->
                        <div style="padding: 1.25rem; border-radius: var(--radius-xl); background: rgba(239,68,68,0.04); border: 1px solid rgba(239,68,68,0.15);">
                            <p class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> Kargo Bahan Kimia</p>
                            <p class="fs-sm text-muted mb-2">hitungTarifPengiriman()</p>
                            <p class="fw-semibold fs-sm" style="font-family: 'JetBrains Mono', monospace; color: var(--clr-text);">
                                Total = (Berat × tarif) + (Bahaya × 100.000)
                            </p>
                        </div>
                        <!-- Pecah Belah -->
                        <div style="padding: 1.25rem; border-radius: var(--radius-xl); background: rgba(245,158,11,0.04); border: 1px solid rgba(245,158,11,0.15);">
                            <p class="fw-bold text-warning mb-2"><i class="bi bi-shield-exclamation me-1"></i> Kargo Pecah Belah</p>
                            <p class="fs-sm text-muted mb-2">hitungTarifPengiriman()</p>
                            <p class="fw-semibold fs-sm" style="font-family: 'JetBrains Mono', monospace; color: var(--clr-text);">
                                Total = (Berat × tarif) + Asuransi + 5%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>

    <!-- Re-trigger dynamic fields if form had errors (preserve selection state) -->
    <?php if (!empty($form_data['jenis_kargo'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toggleDynamicFields();
        });
    </script>
    <?php endif; ?>
</body>
</html>
