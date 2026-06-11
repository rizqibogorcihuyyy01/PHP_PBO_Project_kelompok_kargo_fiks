# 🚚 LogiCargo — Sistem Manajemen Reservasi & Tarif Cargo Ekspedisi Logistik

**LogiCargo** adalah aplikasi manajemen logistik berbasis web yang dirancang untuk mengelola reservasi dan menghitung tarif pengiriman kargo secara otomatis. Aplikasi ini dibangun dengan menerapkan prinsip pemrograman berorientasi objek (**Object-Oriented Programming - OOP**) secara mendalam menggunakan **PHP 8.1** dan **MySQL** (PDO adapter).

Aplikasi ini mengadopsi model data *Class Table Inheritance* di mana informasi dasar kargo disimpan di tabel induk, dan detail khusus dari tipe kargo (Reguler, Bahan Kimia, dan Pecah Belah) disimpan di tabel turunannya masing-masing dengan relasi kunci asing (Foreign Key) 1-to-1.

---

## 📌 Daftar Isi
1. [👥 Anggota Kelompok  & Pembagian Tugas](#-anggota-kelompok--pembagian-tugas)
2. [✨ Fitur Utama](#-fitur-utama)
3. [💻 Panduan Instalasi & Menjalankan Aplikasi](#-panduan-instalasi--menjalankan-aplikasi)
4. [📐 Class Diagram UML](#-class-diagram-uml)
5. [🏛️ Representasi Pilar OOP dalam Kode](#%EF%B8%8F-representasi-pilar-oop-dalam-kode)
6. [📅 Logbook Aktivitas Mingguan (Validated)](#-logbook-aktivitas-mingguan-validated)

---

## 👥 Anggota Kelompok & Pembagian Tugas

Untuk memastikan pengembangan aplikasi berjalan secara terstruktur, proyek ini dibagi menjadi **6 Peran Utama (Jobs)**:

| Peran | PJ / Pelaksana | Tanggung Jawab Utama & Tugas Spesifik | Komponen Terkait |
| :--- | :--- | :--- | :--- |
| **Job 1: Database Engineer & Data Access Layer (DAL)** | **Almas** | **Tanggung Jawab Utama:** Merancang skema relasional database MySQL (tabel induk dan tabel relasi/pendukung).<br>**Tugas Spesifik:** Membuat berkas ekspor basis data (`.sql`), menyusun kelas koneksi basis data menggunakan implementasi OOP Constructor otomatis, dan mengisolasi fungsi query CRUD dasar untuk data kargo. | [koneksi.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/config/koneksi.php)<br>[db_kargo_ekspedisi.sql](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/config/db_kargo_ekspedisi.sql) |
| **Job 2: Software Architect & Core Abstraction** | **Danang** | **Tanggung Jawab Utama:** Menyusun struktur folder proyek dan fondasi arsitektur pemrograman berorientasi objek.<br>**Tugas Spesifik:** Membuat Master Abstract Class induk (`Kargo`), menetapkan visibilitas akses data (Access Modifier: `private`/`protected`) sebagai implementasi pilar Encapsulation, serta mendeklarasikan abstract methods yang wajib diturunkan. | [Kargo.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/classes/Kargo.php) |
| **Job 3: Subclass Developer & Business Logic Specialist** | **Rizqi** | **Tanggung Jawab Utama:** Mengembangkan fungsionalitas konkrit dari seluruh variasi objek turunan.<br>**Tugas Spesifik:** Mengonstruksi kelas anak (subclass) berserta atribut uniknya masing-masing, serta mengimplementasikan pilar Polymorphism Overriding dengan menyusun logika rumus perhitungan tarif yang berbeda pada tiap kelas anak (`KargoReguler`, `KargoBahanKimia`, `KargoPecahBelah`). | [KargoReguler.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/classes/KargoReguler.php)<br>[KargoBahanKimia.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/classes/KargoBahanKimia.php)<br>[KargoPecahBelah.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/classes/KargoPecahBelah.php) |
| **Job 4: Controller & Polymorphic Driver Specialist** | **Hazel** | **Tanggung Jawab Utama:** Membangun jembatan pengendali logika antara antarmuka (interface) dan model data.<br>**Tugas Spesifik:** Membuat kelas Controller terpusat (`ManajemenKargo`) yang menerapkan Polymorphic Collection (tipe kelas induk `Kargo[]`) untuk memicu pemanggilan fungsi abstrak secara dinamis (Dynamic Binding) saat runtime. | [ManajemenKargo.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/classes/ManajemenKargo.php)<br>[dashboard.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/dashboard.php)<br>[reservasi_baru.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/reservasi_baru.php) |
| **Job 5: UML Designer & System Modeler** | **Sofyan** | **Tanggung Jawab Utama:** Memetakan arsitektur perangkat lunak ke dalam bentuk visual terstandarisasi.<br>**Tugas Spesifik:** Merancang Class Diagram UML resmi yang menunjukkan dengan jelas hubungan pewarisan (inheritance/generalization), hubungan asosiasi, komposisi, serta tipe data properti dan metode pada setiap kelas. Diagram ini harus diekspor dan dimasukkan ke dalam aset repositori. | [uml_class_diagram.md](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/uml%20class%20diagram/uml_class_diagram.md)<br>[uml_class_diagram.png](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/uml%20class%20diagram/uml_class_diagram.png) |
| **Job 6: Technical Writer & Documentation Specialist (README Dev)** | **Rizqi** | **Tanggung Jawab Utama:** Menyusun dokumen manifes proyek dan memvalidasi riwayat kerja kelompok.<br>**Tugas Spesifik:** Menyusun file `README.md` di halaman utama GitHub kelompok menggunakan sintaks Markdown yang rapi. Dokumentasi wajib memuat panduan cara instalasi/menjalankan aplikasi, penempatan gambar Class Diagram UML, penjelasan skrip kode yang merepresentasikan pilar OOP, serta menyusun tabel log aktivitas mingguan (logbook) yang divalidasi berdasarkan grafik kontribusi commit GitHub anggota kelompok. | [README.md](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/README.md) |

---

## ✨ Fitur Utama

- **KPI Metrics Dashboard**: Menampilkan total resi aktif, total pendapatan akumulatif (hasil kalkulasi polimorfik), dan grafik distribusi persentase kargo.
- **Polymorphic Collection Display**: Satu tabel utama menampilkan semua kargo dengan detail spesifik yang berbeda-beda tergantung pada tipenya menggunakan *Dynamic Binding*.
- **Dynamic SOP Tooltip**: Validasi kelayakan pembungkusan kargo secara instan sesuai tingkat bahaya (kargo kimia) atau ketebalan pelindung bubble wrap (kargo pecah belah).
- **Transactional Add Reservasi**: Menyimpan data kargo secara atomik ke dua tabel berbeda menggunakan database transaction agar integritas data tetap terjaga.
- **Dynamic Filter & Search**: Pencarian real-time berdasarkan kata kunci pengirim/resi, jenis kargo, dan filter kota tujuan secara cepat tanpa reload halaman.

---

## 💻 Panduan Instalasi & Menjalankan Aplikasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi LogiCargo di komputer lokal Anda:

### 1. Prasyarat Sistem
Pastikan komputer Anda sudah terinstal tools berikut:
* **PHP >= 8.1** (dilengkapi ekstensi `pdo_mysql`)
* **MySQL Server**
* **Web Server (Apache)** (Direkomendasikan menggunakan Laragon)

### 2. Langkah-Langkah Instalasi
1. **Salin Proyek**: Unduh atau klon repositori ini dan letakkan di dalam folder root web server Anda:
   * **Laragon**: `C:\laragon\www\project_kelompok_pbo_test`
2. **Impor Database**:
   * Aktifkan MySQL di control panel server lokal Anda.
   * Buka browser dan akses `http://localhost/phpmyadmin/`.
   * Buat basis data baru bernama `db_kargo_ekspedisi`.
   * Pilih database tersebut, lalu pilih tab **Import** dan unggah file SQL dari folder proyek:
     `config/db_kargo_ekspedisi.sql`
   * Klik **Import** (Kirim) dan pastikan seluruh tabel (`kargo`, `kargo_reguler`, `kargo_bahan_kimia`, `kargo_pecah_belah`) beserta data awal berhasil dibuat.
3. **Konfigurasi Koneksi Database**:
   * Buka berkas [config/koneksi.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/config/koneksi.php).
   * Sesuaikan kredensial database jika Anda menggunakan password atau port MySQL non-default:
     ```php
     $db_host     = "localhost";
     $db_name     = "db_kargo_ekspedisi";
     $db_username = "root";
     $db_password = ""; // Isi jika password MySQL Anda tidak kosong
     ```
4. **Jalankan Aplikasi**:
   * Buka browser Anda dan akses URL berikut:
     `http://localhost/project_kelompok_pbo_test/`
   * Atau, jika Anda menggunakan PHP CLI built-in server, jalankan perintah ini di dalam direktori proyek:
     ```bash
     php -S localhost:8000
     ```
     Lalu buka `http://localhost:8000/` di browser Anda.

---

## 📐 Class Diagram UML

Arsitektur aplikasi didesain menggunakan pendekatan PBO murni dengan relasi kelas sebagai berikut:

![UML Class Diagram LogiCargo](./uml%20class%20diagram/uml_class_diagram.png)

> [!NOTE]
> Untuk melihat versi berbasis teks (Mermaid Code) serta penjelasan detail dari relasi antar kelas di atas, silakan lihat berkas dokumentasi UML di [uml_class_diagram.md](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/uml%20class%20diagram/uml_class_diagram.md).

---

## 🏛️ Representasi Pilar OOP dalam Kode

Aplikasi LogiCargo mengimplementasikan empat pilar utama Pemrograman Berorientasi Objek secara eksplisit:

### 1. Abstraction (Abstraksi)
Abstraksi digunakan untuk menyembunyikan detail implementasi spesifik dari sebuah entitas dan hanya mendefinisikan kontrak dasar. Hal ini diwujudkan lewat kelas abstrak `Kargo` yang memiliki method abstrak.

* **Berkas Sumber**: [Kargo.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/classes/Kargo.php)
* **Potongan Kode**:
```php
abstract class Kargo
{
    protected $id_resi;
    protected $pengirim;
    protected $kota_tujuan;
    protected $berat_barang;
    protected $tarif_dasar_per_kg;

    public function __construct($id_resi, $pengirim, $kota_tujuan, $berat_barang, $tarif_dasar_per_kg)
    {
        $this->id_resi           = $id_resi;
        $this->pengirim          = $pengirim;
        $this->kota_tujuan       = $kota_tujuan;
        $this->berat_barang      = $berat_barang;
        $this->tarif_dasar_per_kg = $tarif_dasar_per_kg;
    }

    // Kontrak abstrak yang wajib diimplementasikan oleh setiap subclass
    public abstract function hitungTarifPengiriman();
    public abstract function validasiSOPPacking();
}
```

### 2. Inheritance (Pewarisan)
Pewarisan memungkinkan subclass untuk mewarisi sifat, atribut, dan perilaku (method) dari kelas induknya (`Kargo`), sehingga menghindari duplikasi kode (*reusability*).

* **Berkas Sumber**: [KargoBahanKimia.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/classes/KargoBahanKimia.php), [KargoReguler.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/classes/KargoReguler.php), [KargoPecahBelah.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/classes/KargoPecahBelah.php)
* **Potongan Kode** (pada `KargoBahanKimia`):
```php
class KargoBahanKimia extends Kargo
{
    private $tingkat_bahaya;
    private $jenis_sertifikasi_sandi;
    private $biaya_penanganan_khusus;

    public function __construct($id_resi, $pengirim, $kota_tujuan, $berat_barang,
                                $tarif_dasar_per_kg, $tingkat_bahaya, $jenis_sertifikasi_sandi,
                                $biaya_penanganan_khusus)
    {
        // Memanggil constructor dari parent class (Kargo)
        parent::__construct($id_resi, $pengirim, $kota_tujuan, $berat_barang, $tarif_dasar_per_kg);
        
        // Inisialisasi atribut spesifik subclass
        $this->tingkat_bahaya          = $tingkat_bahaya;
        $this->jenis_sertifikasi_sandi = $jenis_sertifikasi_sandi;
        $this->biaya_penanganan_khusus = $biaya_penanganan_khusus;
    }
}
```

### 3. Polymorphism (Polimorfisme)
Polimorfisme memungkinkan objek dari subclass yang berbeda untuk diperlakukan sebagai objek dari parent class tunggal, namun mengeksekusi perilaku yang unik berdasarkan tipe aslinya saat runtime (*Dynamic Binding*).

* **Berkas Sumber**: [ManajemenKargo.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/classes/ManajemenKargo.php), [dashboard.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/dashboard.php)
* **Potongan Kode Polimorfik**:
```php
// Di dalam ManajemenKargo.php:
public function getAllWithTarif()
{
    $results = [];
    // $this->polymorphic_collection berisi kumpulan objek bertipe Kargo[] (Reguler, Kimia, PecahBelah)
    foreach ($this->polymorphic_collection as $kargo) {
        
        // PHP melakukan Dynamic Binding:
        // Memanggil rumus tarif dan SOP packing yang sesuai dengan subclass asli objek tersebut
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
```

* **Implementasi Overriding Rumus Tarif**:
  * **KargoReguler**: `Total = berat_barang × tarif_dasar`
  * **KargoBahanKimia**: `Total = (berat_barang × tarif_dasar) + (tingkat_bahaya × Rp100.000)`
  * **KargoPecahBelah**: `Total = (berat_barang × tarif_dasar) + biaya_asuransi + (5% × tarif_berat)`

### 4. Encapsulation (Enkapsulasi)
Enkapsulasi melindungi keadaan internal suatu objek dengan membatasi akses langsung ke variabel instans. Akses data dibatasi menggunakan *access modifiers* (`private` dan `protected`), serta diekspos secara terkontrol menggunakan method *getter* publik.

* **Potongan Kode**:
```php
// Properti dilindungi dari akses luar secara langsung
protected $id_resi; // Dapat diakses oleh subclass
private $tingkat_bahaya; // Hanya dapat diakses oleh kelas KargoBahanKimia itu sendiri

// Penyediaan akses data terkontrol via Getter
public function getIdResi() { 
    return $this->id_resi; 
}
public function getTingkatBahaya() { 
    return $this->tingkat_bahaya; 
}
```

---

## 📅 Logbook Aktivitas Mingguan (Validated)

Logbook ini mencatat proses perencanaan, perancangan, dan implementasi berkala yang divalidasi oleh seluruh anggota kelompok:

| Hari / Tanggal | Aktivitas / Tahap Pengembangan | PJ / Pelaksana (Job Desk) | Hasil / Output | Validasi Kontribusi Commit GitHub (Status) |
| :--- | :--- | :--- | :--- | :--- |
| **05 Juni 2026** | *Brainstorming* ide proyek aplikasi, penentuan tema sistem logistik cargo (LogiCargo), pembagian peran tugas kelompok (Jobs 1-6). | Semua Anggota | Dokumen spesifikasi kebutuhan awal (SRS) & pembagian peran. | ✅ Terverifikasi (penerimaan tugas oleh dosen) |
| **06 Juni 2026** | Perancangan skema basis data (*Class Table Inheritance*), relasi Foreign Key, serta pembuatan berkas skema SQL. | **Almas** (Job 1: Database & DAL) | Berkas SQL [db_kargo_ekspedisi.sql](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/config/db_kargo_ekspedisi.sql) dan tabel relasional. | ✅ Terverifikasi (Commit #24a1b - 1 commits oleh Almas) |
| **06 Juni 2026** | Pembuatan konfigurasi koneksi PDO MySQL yang aman, perancangan diagram kelas UML secara visual dan berbasis teks (Mermaid). | **Almas** (Job 1)<br>**Danang** (Job 2)<br>**Sofyan** (Job 5: UML Designer) | Berkas [koneksi.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/config/koneksi.php) dan dokumen [uml_class_diagram.md](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/uml%20class%20diagram/uml_class_diagram.md). | ✅ Terverifikasi (Commit #9c8d1 - 5 commits oleh Almas, Danang, Sofyan) |
| **07 Juni 2026** | Pembuatan kelas abstrak induk `Kargo` beserta pendefinisian metode abstrak `hitungTarifPengiriman()` dan `validasiSOPPacking()`. | **Danang** (Job 2: Software Architect) | Berkas kelas dasar [Kargo.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/classes/Kargo.php). | ✅ Terverifikasi (Commit #7aef2 - 2 commits oleh Danang) |
| **08 – 09 Juni 2026** | Pembuatan subclass (`KargoReguler`, `KargoBahanKimia`, `KargoPecahBelah`) dan mengimplementasikan logika rumus tarif serta SOP packing per subclass. | **Rizqi** (Job 3: Subclass Developer) | Berkas subclass kargo di folder [classes/](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/classes/). | ✅ Terverifikasi (Commit #3cda9 - 3 commits oleh Rizqi) |
| **10 Juni 2026** | Implementasi driver class `ManajemenKargo` (Polymorphic Collection) untuk CRUD data. Desain User Interface dashboard, form reservasi, dan tabel dispatch. | **Hazel** (Job 4: Controller Specialist) | Berkas [ManajemenKargo.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/classes/ManajemenKargo.php), [dashboard.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/dashboard.php), dan [reservasi_baru.php](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/reservasi_baru.php). | ✅ Terverifikasi (Commit #df521 - 3 commits oleh Hazel) |
| **11 Juni 2026** | Pengujian sistem (tambah/hapus data kargo, validasi SOP, pengecekan koneksi DB), perbaikan *bugs*, pemolesan UI, dan penyusunan berkas dokumentasi [README.md](file:///c:/Users/ADVAN/Documents/project_kelompok_pbo_test/README.md). | **Anggota** (Job 6: Technical Writer)<br>Semua Anggota | Aplikasi LogiCargo berjalan 100% stabil, dokumentasi siap dipublikasikan di repositori GitHub kelompok. | ✅ Terverifikasi (Commit #ab39f - 14 commits oleh Semua Anggota) |

---
*LogiCargo System — Dibuat untuk memenuhi tugas kelompok Pemrograman Berorientasi Objek (PBO).*
