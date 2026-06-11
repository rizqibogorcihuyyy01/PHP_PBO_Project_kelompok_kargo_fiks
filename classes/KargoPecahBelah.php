<?php
/**
 * ============================================================================
 * SUBCLASS: KargoPecahBelah (extends Kargo)
 * ============================================================================
 * 
 * Job 2 (Core Abstraction Specialist) — Inheritance & Polymorphism
 * 
 * Kargo Pecah Belah memerlukan perlindungan ekstra (bubble wrap)
 * dan dikenakan biaya asuransi wajib.
 * 
 * Atribut Tambahan:
 *   - ketebalan_bubble_wrap : Ketebalan bubble wrap dalam mm/lapis
 *   - biaya_asuransi_wajib  : Biaya asuransi wajib (Rp)
 * 
 * Rumus Tarif (Polymorphism Overriding):
 *   Total = (berat × tarif_dasar) + biaya_asuransi_wajib + (5% × tarif_berat)
 * 
 * Tabel Database : kargo_pecah_belah (FK → kargo.id_resi)
 *   - id_resi               VARCHAR(20) PK + FK
 *   - ketebalan_bubbleWrap  INT
 *   - biaya_asuransiWajib   DECIMAL(12,2)
 * 
 * @package  LogiCargo Dashboard
 * @version  1.0.0
 * ============================================================================
 */

require_once __DIR__ . '/Kargo.php';

class KargoPecahBelah extends Kargo
{
    // ── Encapsulation: Private Attributes ──
    private $ketebalan_bubble_wrap;  // Ketebalan bubble wrap (lapis)
    private $biaya_asuransi_wajib;   // Biaya asuransi wajib (Rp)

    /**
     * Constructor — Memanggil parent + atribut tambahan Pecah Belah
     */
    public function __construct($id_resi, $pengirim, $kota_tujuan, $berat_barang,
                                $tarif_dasar_per_kg, $ketebalan_bubble_wrap, $biaya_asuransi_wajib)
    {
        parent::__construct($id_resi, $pengirim, $kota_tujuan, $berat_barang, $tarif_dasar_per_kg);
        $this->ketebalan_bubble_wrap = $ketebalan_bubble_wrap;
        $this->biaya_asuransi_wajib  = $biaya_asuransi_wajib;
    }

    // ══════════════════════════════════════════
    //  POLYMORPHISM OVERRIDING
    // ══════════════════════════════════════════

    /**
     * hitungTarifPengiriman() — Kargo Pecah Belah
     * Rumus: (Berat × tarifDasarPerKg) + biayaAsuransiWajib + Surcharge Fragile (5% tarif berat)
     * Job 3 (Business Logic Specialist)
     * 
     * @return float Total tarif pengiriman pecah belah
     */
    public function hitungTarifPengiriman()
    {
        $tarif_berat       = $this->berat_barang * $this->tarif_dasar_per_kg;
        $surcharge_fragile = $tarif_berat * 0.05; // 5% dari tarif berat
        return $tarif_berat + $this->biaya_asuransi_wajib + $surcharge_fragile;
    }

    /**
     * validasiSOPPacking() — Kargo Pecah Belah
     * Validasi packing fragile berdasarkan ketebalan bubble wrap
     * 
     * @return string Deskripsi SOP Packing
     */
    public function validasiSOPPacking()
    {
        $pesan = "📦 SOP Pecah Belah (Bubble Wrap: {$this->ketebalan_bubble_wrap} lapis): ";

        if ($this->ketebalan_bubble_wrap >= 4) {
            $pesan .= "✅ PREMIUM — Bubble wrap tebal, box kayu/peti, dan label FRAGILE di semua sisi.";
        } elseif ($this->ketebalan_bubble_wrap >= 2) {
            $pesan .= "✅ STANDAR — Bubble wrap cukup, dus karton tebal, dan label FRAGILE.";
        } else {
            $pesan .= "❌ KURANG — Ketebalan bubble wrap tidak memenuhi standar minimum (min. 2 lapis).";
        }

        $pesan .= " | Asuransi: Rp " . number_format($this->biaya_asuransi_wajib, 0, ',', '.');
        return $pesan;
    }

    // ── Getter Methods ──
    public function getKetebalanBubbleWrap() { return $this->ketebalan_bubble_wrap; }
    public function getBiayaAsuransi()       { return $this->biaya_asuransi_wajib; }
}
?>
