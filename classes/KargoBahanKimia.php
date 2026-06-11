<?php
/**
 * ============================================================================
 * SUBCLASS: KargoBahanKimia (extends Kargo)
 * ============================================================================
 * 
 * Job 2 (Core Abstraction Specialist) — Inheritance & Polymorphism
 * 
 * Kargo Bahan Kimia memerlukan penanganan khusus sesuai tingkat bahaya (Hazard Class 1-9)
 * dan membutuhkan sertifikasi sandi (MSDS/UN Number).
 * 
 * Atribut Tambahan:
 *   - tingkat_bahaya          : Level bahaya 1-9 (GHS Classification)
 *   - jenis_sertifikasi_sandi : Kode sertifikasi (e.g., UN-1789-CORROSIVE, MSDS-OXIDIZER)
 *   - biaya_penanganan_khusus : Biaya tambahan berdasarkan tingkat bahaya
 * 
 * Rumus Tarif (Polymorphism Overriding):
 *   Total = (berat_barang × tarif_dasar_per_kg) + (tingkat_bahaya × 100000)
 * 
 * Tabel Database : kargo_bahan_kimia (FK → kargo.id_resi)
 *   - id_resi                  VARCHAR(20) PK + FK
 *   - tingkat_bahaya           INT (1-9)
 *   - jenis_sertifikasi_sandi  VARCHAR(100)
 *   - biaya_penanganan_khusus  DECIMAL(12,2)
 * 
 * @package  LogiCargo Dashboard
 * @version  1.0.0
 * ============================================================================
 */

require_once __DIR__ . '/Kargo.php';

class KargoBahanKimia extends Kargo
{
    // ── Encapsulation: Private Attributes ──
    private $tingkat_bahaya;           // Hazard Class 1-9
    private $jenis_sertifikasi_sandi;  // Kode sertifikasi MSDS/UN
    private $biaya_penanganan_khusus;  // Biaya penanganan khusus (Rp)

    /**
     * Constructor — Memanggil parent + atribut tambahan Bahan Kimia
     */
    public function __construct($id_resi, $pengirim, $kota_tujuan, $berat_barang,
                                $tarif_dasar_per_kg, $tingkat_bahaya, $jenis_sertifikasi_sandi,
                                $biaya_penanganan_khusus)
    {
        parent::__construct($id_resi, $pengirim, $kota_tujuan, $berat_barang, $tarif_dasar_per_kg);
        $this->tingkat_bahaya          = $tingkat_bahaya;
        $this->jenis_sertifikasi_sandi = $jenis_sertifikasi_sandi;
        $this->biaya_penanganan_khusus = $biaya_penanganan_khusus;
    }

    // ══════════════════════════════════════════
    //  POLYMORPHISM OVERRIDING
    // ══════════════════════════════════════════

    /**
     * hitungTarifPengiriman() — Kargo Bahan Kimia
     * Rumus: (Berat × tarifDasarPerKg) + (TingkatBahaya × Rp100.000)
     * Job 3 (Business Logic Specialist)
     * 
     * @return float Total tarif pengiriman bahan kimia
     */
    public function hitungTarifPengiriman()
    {
        $tarif_berat = $this->berat_barang * $this->tarif_dasar_per_kg;
        $biaya_bahaya = $this->tingkat_bahaya * 100000;
        return $tarif_berat + $biaya_bahaya;
    }

    /**
     * validasiSOPPacking() — Kargo Bahan Kimia
     * Validasi sesuai tingkat bahaya dan kebutuhan sertifikasi
     * 
     * @return string Deskripsi SOP Packing
     */
    public function validasiSOPPacking()
    {
        $pesan = "🧪 SOP Bahan Kimia (Bahaya Lv.{$this->tingkat_bahaya}): ";

        if ($this->tingkat_bahaya >= 7) {
            $pesan .= "⚠️ BAHAYA TINGGI — Wajib kontainer tahan bocor, label GHS, dan dokumen MSDS.";
        } elseif ($this->tingkat_bahaya >= 4) {
            $pesan .= "⚠️ BAHAYA SEDANG — Wajib kemasan sekunder anti-tumpah dan label peringatan.";
        } else {
            $pesan .= "✅ BAHAYA RENDAH — Kemasan standar dengan label identifikasi bahan kimia.";
        }

        $pesan .= " | Sertifikasi: {$this->jenis_sertifikasi_sandi}";
        return $pesan;
    }

    // ── Getter Methods ──
    public function getTingkatBahaya()     { return $this->tingkat_bahaya; }
    public function getJenisSertifikasi()  { return $this->jenis_sertifikasi_sandi; }
    public function getBiayaPenanganan()   { return $this->biaya_penanganan_khusus; }
}
?>
