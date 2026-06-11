<?php
/**
 * ============================================================================
 * ABSTRACT CLASS: Kargo (Base / Parent Class)
 * ============================================================================
 * 
 * Job 2 (Core Abstraction Specialist):
 * Kelas abstrak yang merepresentasikan entitas Kargo secara umum.
 * Mengimplementasikan pilar OOP:
 * 
 *   1. ABSTRACTION  → Kelas ini adalah abstract, tidak bisa di-instantiate langsung.
 *                      Method hitungTarifPengiriman() & validasiSOPPacking() adalah abstract.
 *   2. ENCAPSULATION → Atribut menggunakan access modifier protected/private
 *                      dengan getter & setter.
 *   3. INHERITANCE   → Subclass (KargoReguler, KargoBahanKimia, KargoPecahBelah) 
 *                      mewarisi kelas ini.
 *   4. POLYMORPHISM  → Setiap subclass meng-override method abstract sesuai 
 *                      business rule masing-masing (Dynamic Binding).
 * 
 * Tabel Database : kargo (parent table)
 *   - id_resi          VARCHAR(20) PK
 *   - pengirim         VARCHAR(100)
 *   - kota_tujuan      VARCHAR(50)
 *   - berat_barang     DECIMAL(10,2)
 *   - tarif_dasar_perKg DECIMAL(12,2)
 * 
 * @package  LogiCargo Dashboard
 * @version  1.0.0
 * ============================================================================
 */

abstract class Kargo
{
    // ── Encapsulation: Protected Attributes ──
    protected $id_resi;
    protected $pengirim;
    protected $kota_tujuan;
    protected $berat_barang;
    protected $tarif_dasar_per_kg;

    /**
     * Constructor — Inisialisasi atribut dasar kargo
     * 
     * @param string $id_resi          ID Resi unik (PK)
     * @param string $pengirim         Nama pengirim
     * @param string $kota_tujuan      Kota tujuan pengiriman
     * @param float  $berat_barang     Berat barang dalam Kg
     * @param float  $tarif_dasar_per_kg Tarif dasar per kilogram (Rp)
     */
    public function __construct($id_resi, $pengirim, $kota_tujuan, $berat_barang, $tarif_dasar_per_kg)
    {
        $this->id_resi           = $id_resi;
        $this->pengirim          = $pengirim;
        $this->kota_tujuan       = $kota_tujuan;
        $this->berat_barang      = $berat_barang;
        $this->tarif_dasar_per_kg = $tarif_dasar_per_kg;
    }

    // ══════════════════════════════════════════
    //  GETTER METHODS (Encapsulation)
    // ══════════════════════════════════════════

    public function getIdResi()      { return $this->id_resi; }
    public function getPengirim()    { return $this->pengirim; }
    public function getKotaTujuan()  { return $this->kota_tujuan; }
    public function getBeratBarang() { return $this->berat_barang; }
    public function getTarifDasar()  { return $this->tarif_dasar_per_kg; }

    // ══════════════════════════════════════════
    //  ABSTRACT METHODS (Abstraction & Polymorphism)
    // ══════════════════════════════════════════

    /**
     * hitungTarifPengiriman() — Abstract
     * Setiap subclass WAJIB mengimplementasikan rumus kalkulasi tarif sendiri.
     * Ini adalah contoh Polymorphism via Dynamic Binding.
     * 
     * @return float Total tarif pengiriman
     */
    public abstract function hitungTarifPengiriman();

    /**
     * validasiSOPPacking() — Abstract
     * Setiap subclass WAJIB mengimplementasikan validasi SOP Packing sendiri.
     * 
     * @return string Deskripsi SOP Packing
     */
    public abstract function validasiSOPPacking();
}
?>
