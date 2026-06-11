<?php
/**
 * ============================================================================
 * SUBCLASS: KargoReguler (extends Kargo)
 * ============================================================================
 * 
 * Job 2 (Core Abstraction Specialist) — Inheritance & Polymorphism
 * 
 * Kargo Reguler adalah tipe pengiriman standar tanpa penanganan khusus.
 * 
 * Atribut Tambahan:
 *   - jenis_paket   : Koli / Dus
 *   - estimasi_hari  : Estimasi lama pengiriman (hari)
 * 
 * Rumus Tarif (Polymorphism Overriding):
 *   Total = berat_barang × tarif_dasar_per_kg
 * 
 * Tabel Database : kargo_reguler (FK → kargo.id_resi)
 *   - id_resi        VARCHAR(20) PK + FK
 *   - jenis_paket    ENUM('Koli','Dus')
 *   - estimasi_hari  INT
 * 
 * @package  LogiCargo Dashboard
 * @version  1.0.0
 * ============================================================================
 */

require_once __DIR__ . '/Kargo.php';

class KargoReguler extends Kargo
{
    // ── Encapsulation: Private Attributes ──
    private $jenis_paket;    // 'Koli' atau 'Dus'
    private $estimasi_hari;  // Estimasi hari pengiriman

    /**
     * Constructor — Memanggil parent + atribut tambahan Reguler
     */
    public function __construct($id_resi, $pengirim, $kota_tujuan, $berat_barang,
                                $tarif_dasar_per_kg, $jenis_paket, $estimasi_hari)
    {
        parent::__construct($id_resi, $pengirim, $kota_tujuan, $berat_barang, $tarif_dasar_per_kg);
        $this->jenis_paket  = $jenis_paket;
        $this->estimasi_hari = $estimasi_hari;
    }

    // ══════════════════════════════════════════
    //  POLYMORPHISM OVERRIDING
    // ══════════════════════════════════════════

    /**
     * hitungTarifPengiriman() — Kargo Reguler
     * Rumus: Berat × tarifDasarPerKg
     * Job 3 (Business Logic Specialist)
     * 
     * @return float Total tarif pengiriman reguler
     */
    public function hitungTarifPengiriman()
    {
        return $this->berat_barang * $this->tarif_dasar_per_kg;
    }

    /**
     * validasiSOPPacking() — Kargo Reguler
     * Validasi standar packing berdasarkan jenis paket
     * 
     * @return string Deskripsi SOP Packing
     */
    public function validasiSOPPacking()
    {
        if ($this->jenis_paket === 'Koli') {
            return "✅ SOP Reguler (Koli): Barang diikat rapi dengan tali dan dibungkus plastik wrapping standar.";
        } elseif ($this->jenis_paket === 'Dus') {
            return "✅ SOP Reguler (Dus): Barang dimasukkan ke dalam dus karton dengan pengisi styrofoam/kertas.";
        } else {
            return "❌ Jenis paket tidak valid. Harus 'Koli' atau 'Dus'.";
        }
    }

    // ── Getter Methods ──
    public function getJenisPaket()  { return $this->jenis_paket; }
    public function getEstimasiHari() { return $this->estimasi_hari; }
}
?>
