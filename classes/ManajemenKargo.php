<?php
/**
 * ============================================================================
 * CONTROLLER: ManajemenKargo — Polymorphic Collection Manager
 * ============================================================================
 * 
 * Job 4 (Controller / Driver Specialist):
 * Kelas ini bertindak sebagai Controller yang mengelola Polymorphic Collection
 * dari semua subclass Kargo. Menggunakan Dynamic Binding untuk memanggil 
 * method hitungTarifPengiriman() dan validasiSOPPacking() secara polimorfik.
 * 
 * Fitur Utama:
 *   - loadAllKargo()      : Membaca semua data dari DB → instansiasi ke subclass
 *   - getAllWithTarif()    : Iterasi polymorphic collection → Dynamic Binding
 *   - tambahKargo($kargo) : Insert data baru ke DB (parent + subclass)
 *   - updateKargo($kargo) : Update data existing di DB
 *   - hapusKargo($id)     : Hapus data via CASCADE constraint
 * 
 * @package  LogiCargo Dashboard
 * @version  1.0.0
 * ============================================================================
 */

require_once __DIR__ . '/Kargo.php';
require_once __DIR__ . '/KargoReguler.php';
require_once __DIR__ . '/KargoBahanKimia.php';
require_once __DIR__ . '/KargoPecahBelah.php';

class ManajemenKargo
{
    // ── Encapsulation: Private Attributes ──
    private $conn;                          // PDO Database Connection
    private $polymorphic_collection = [];   // Array of Kargo subclass objects (Polymorphic Collection)

    /**
     * Constructor
     * @param PDO|null $db_connection PDO connection object
     */
    public function __construct($db_connection)
    {
        $this->conn = $db_connection;
    }

    // ══════════════════════════════════════════
    //  DATABASE → OBJECT MAPPING (Job 1 & Job 4)
    // ══════════════════════════════════════════

    /**
     * loadAllKargo() — Membaca seluruh data dari DB dan membuat objek subclass
     * 
     * Query menggunakan JOIN untuk menggabungkan tabel parent (kargo)
     * dengan masing-masing tabel subclass (kargo_reguler, kargo_bahan_kimia, kargo_pecah_belah).
     * 
     * @return array Polymorphic Collection dari objek Kargo
     */
    public function loadAllKargo()
    {
        $this->polymorphic_collection = [];

        if ($this->conn === null) return $this->polymorphic_collection;

        // ── 1. Load Kargo Reguler (JOIN kargo + kargo_reguler) ──
        $query_reguler = "SELECT k.*, r.jenis_paket, r.estimasi_hari 
                          FROM kargo k 
                          JOIN kargo_reguler r ON k.id_resi = r.id_resi
                          ORDER BY k.id_resi ASC";
        $stmt = $this->conn->prepare($query_reguler);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $kargo = new KargoReguler(
                $row['id_resi'],
                $row['pengirim'],
                $row['kota_tujuan'],
                $row['berat_barang'],
                $row['tarif_dasar_perKg'],
                $row['jenis_paket'],
                $row['estimasi_hari']
            );
            $this->polymorphic_collection[] = $kargo;
        }

        // ── 2. Load Kargo Bahan Kimia (JOIN kargo + kargo_bahan_kimia) ──
        $query_kimia = "SELECT k.*, b.tingkat_bahaya, b.jenis_sertifikasi_sandi, b.biaya_penanganan_khusus 
                        FROM kargo k 
                        JOIN kargo_bahan_kimia b ON k.id_resi = b.id_resi
                        ORDER BY k.id_resi ASC";
        $stmt = $this->conn->prepare($query_kimia);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $kargo = new KargoBahanKimia(
                $row['id_resi'],
                $row['pengirim'],
                $row['kota_tujuan'],
                $row['berat_barang'],
                $row['tarif_dasar_perKg'],
                $row['tingkat_bahaya'],
                $row['jenis_sertifikasi_sandi'],
                $row['biaya_penanganan_khusus']
            );
            $this->polymorphic_collection[] = $kargo;
        }

        // ── 3. Load Kargo Pecah Belah (JOIN kargo + kargo_pecah_belah) ──
        $query_pecah = "SELECT k.*, p.ketebalan_bubbleWrap, p.biaya_asuransiWajib 
                        FROM kargo k 
                        JOIN kargo_pecah_belah p ON k.id_resi = p.id_resi
                        ORDER BY k.id_resi ASC";
        $stmt = $this->conn->prepare($query_pecah);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $kargo = new KargoPecahBelah(
                $row['id_resi'],
                $row['pengirim'],
                $row['kota_tujuan'],
                $row['berat_barang'],
                $row['tarif_dasar_perKg'],
                $row['ketebalan_bubbleWrap'],
                $row['biaya_asuransiWajib']
            );
            $this->polymorphic_collection[] = $kargo;
        }

        return $this->polymorphic_collection;
    }

    // ══════════════════════════════════════════
    //  POLYMORPHIC ITERATION — Dynamic Binding (Job 3 & Job 4)
    // ══════════════════════════════════════════

    /**
     * getAllWithTarif() — Iterasi Polymorphic Collection
     * 
     * PHP secara otomatis melakukan Dynamic Binding:
     * memanggil hitungTarifPengiriman() dan validasiSOPPacking() 
     * sesuai dengan subclass asli dari setiap objek.
     * 
     * @return array Array asosiatif berisi ['kargo', 'tarif', 'sop', 'jenis']
     */
    public function getAllWithTarif()
    {
        $results = [];
        foreach ($this->polymorphic_collection as $kargo) {
            // Dynamic Binding! PHP otomatis memanggil method sesuai subclass asli
            $tarif = $kargo->hitungTarifPengiriman();
            $sop   = $kargo->validasiSOPPacking();

            $results[] = [
                'kargo' => $kargo,
                'tarif' => $tarif,
                'sop'   => $sop,
                'jenis' => $this->getJenisKargo($kargo)
            ];
        }
        return $results;
    }

    /**
     * getJenisKargo() — Menentukan jenis kargo berdasarkan instanceof
     * 
     * @param Kargo $kargo Objek kargo
     * @return string Label jenis kargo
     */
    private function getJenisKargo($kargo)
    {
        if ($kargo instanceof KargoReguler)    return "Reguler";
        if ($kargo instanceof KargoBahanKimia) return "Bahan Kimia";
        if ($kargo instanceof KargoPecahBelah) return "Pecah Belah";
        return "Unknown";
    }

    // ══════════════════════════════════════════
    //  CRUD OPERATIONS (Job 1 & Job 4)
    // ══════════════════════════════════════════

    /**
     * tambahKargo() — Insert kargo baru ke database (Transaksi)
     * 
     * @param Kargo $kargo Objek subclass kargo
     * @return bool true jika berhasil
     */
    public function tambahKargo($kargo)
    {
        $this->conn->beginTransaction();
        try {
            // Cek apakah id_resi sudah ada
            $check = $this->conn->prepare("SELECT COUNT(*) FROM kargo WHERE id_resi = :id_resi");
            $check->execute([':id_resi' => $kargo->getIdResi()]);
            if ($check->fetchColumn() > 0) {
                $this->conn->rollBack();
                return false; // ID Resi sudah terdaftar
            }

            // Insert ke tabel parent (kargo)
            $query = "INSERT INTO kargo (id_resi, pengirim, kota_tujuan, berat_barang, tarif_dasar_perKg) 
                      VALUES (:id_resi, :pengirim, :kota_tujuan, :berat_barang, :tarif_dasar_perKg)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':id_resi'          => $kargo->getIdResi(),
                ':pengirim'         => $kargo->getPengirim(),
                ':kota_tujuan'      => $kargo->getKotaTujuan(),
                ':berat_barang'     => $kargo->getBeratBarang(),
                ':tarif_dasar_perKg' => $kargo->getTarifDasar()
            ]);

            // Insert ke tabel subclass yang sesuai (Polymorphism check)
            if ($kargo instanceof KargoReguler) {
                $q = "INSERT INTO kargo_reguler (id_resi, jenis_paket, estimasi_hari) 
                      VALUES (:id_resi, :jenis_paket, :estimasi_hari)";
                $s = $this->conn->prepare($q);
                $s->execute([
                    ':id_resi'      => $kargo->getIdResi(),
                    ':jenis_paket'  => $kargo->getJenisPaket(),
                    ':estimasi_hari' => $kargo->getEstimasiHari()
                ]);
            } elseif ($kargo instanceof KargoBahanKimia) {
                $q = "INSERT INTO kargo_bahan_kimia (id_resi, tingkat_bahaya, jenis_sertifikasi_sandi, biaya_penanganan_khusus) 
                      VALUES (:id_resi, :tingkat_bahaya, :jenis_sertifikasi_sandi, :biaya_penanganan_khusus)";
                $s = $this->conn->prepare($q);
                $s->execute([
                    ':id_resi'                  => $kargo->getIdResi(),
                    ':tingkat_bahaya'           => $kargo->getTingkatBahaya(),
                    ':jenis_sertifikasi_sandi'  => $kargo->getJenisSertifikasi(),
                    ':biaya_penanganan_khusus'  => $kargo->getBiayaPenanganan()
                ]);
            } elseif ($kargo instanceof KargoPecahBelah) {
                $q = "INSERT INTO kargo_pecah_belah (id_resi, ketebalan_bubbleWrap, biaya_asuransiWajib) 
                      VALUES (:id_resi, :ketebalan, :asuransi)";
                $s = $this->conn->prepare($q);
                $s->execute([
                    ':id_resi'    => $kargo->getIdResi(),
                    ':ketebalan'  => $kargo->getKetebalanBubbleWrap(),
                    ':asuransi'   => $kargo->getBiayaAsuransi()
                ]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    /**
     * hapusKargo() — Hapus kargo dari database (CASCADE ke subclass)
     * 
     * @param string $id_resi ID Resi yang akan dihapus
     * @return bool true jika berhasil
     */
    public function hapusKargo($id_resi)
    {
        try {
            $query = "DELETE FROM kargo WHERE id_resi = :id_resi";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id_resi' => $id_resi]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * getPolymorphicCollection() — Mengembalikan collection saat ini
     * @return array
     */
    public function getPolymorphicCollection()
    {
        return $this->polymorphic_collection;
    }
}
?>
