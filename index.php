<?php
require_once 'config/database.php';
require_once 'classes/ManajemenKargo.php';

// Inisialisasi database dan koneksi
$database = new Database();
$db = $database->getConnection();

// Inisialisasi controller
$manajemen = new ManajemenKargo($db);
$manajemen->loadAllKargo();
$data_kargo = $manajemen->getAllWithTarif();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogiCargo - Sistem Manajemen Reservasi & Tarif Cargo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeInDown 0.8s ease;
        }

        .header h1 {
            font-size: 2.5rem;
            color: white;
            font-weight: 800;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .header h1 i {
            margin-right: 15px;
        }

        .header p {
            color: rgba(255,255,255,0.9);
            font-size: 1.1rem;
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            padding: 10px 25px;
            border-radius: 50px;
            color: white;
            font-weight: 600;
        }

        /* Summary Cards */
        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
            animation: fadeInUp 0.8s ease;
        }

        .summary-item {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .summary-item:hover {
            transform: translateY(-5px);
        }

        .summary-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .summary-item h3 {
            color: #333;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .summary-item .value {
            font-size: 32px;
            font-weight: 800;
            color: #667eea;
        }

        /* Main Table */
        .table-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            animation: fadeInUp 0.8s ease 0.2s backwards;
        }

        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 25px;
            color: white;
        }

        .table-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .table-header h2 i {
            margin-right: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fc;
            border-bottom: 2px solid #e1e5eb;
        }

        th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 700;
            color: #4a5568;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px 20px;
            border-bottom: 1px solid #e1e5eb;
            color: #2d3748;
        }

        tr:hover {
            background: #f7fafc;
            transition: background 0.3s ease;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-reguler { background: #e3f2fd; color: #1976d2; }
        .badge-kimia { background: #ffebee; color: #c62828; }
        .badge-pecah { background: #fff3e0; color: #f57c00; }

        .tarif {
            font-weight: 800;
            color: #2e7d32;
            font-size: 18px;
        }

        .sop-preview {
            max-width: 250px;
            font-size: 12px;
            color: #718096;
            cursor: pointer;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            color: rgba(255,255,255,0.8);
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .summary {
                grid-template-columns: 1fr;
            }
            
            th, td {
                padding: 10px;
                font-size: 12px;
            }
            
            .header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>
                <i class="fas fa-truck-fast"></i>
                LogiCargo System
            </h1>
            <p>Sistem Manajemen Reservasi & Kalkulasi Tarif Cargo Ekspedisi Logistik</p>
            <div class="stats">
                <div class="stat-card">
                    <i class="fas fa-box"></i> Total Transaksi: <?= count($data_kargo) ?>
                </div>
                <div class="stat-card">
                    <i class="fas fa-calculator"></i> Real-time Kalkulasi Polimorfisme
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <?php
        $total_pendapatan = array_sum(array_column($data_kargo, 'tarif'));
        $jenis_kargo = array_count_values(array_column($data_kargo, 'jenis'));
        ?>
        
        <div class="summary">
            <div class="summary-item">
                <div class="summary-icon" style="background: #e3f2fd;">
                    <i class="fas fa-chart-line" style="color: #1976d2;"></i>
                </div>
                <h3>Total Pendapatan</h3>
                <div class="value">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></div>
            </div>
            <div class="summary-item">
                <div class="summary-icon" style="background: #e8f5e9;">
                    <i class="fas fa-box" style="color: #388e3c;"></i>
                </div>
                <h3>Kargo Reguler</h3>
                <div class="value"><?= $jenis_kargo['📦 Reguler'] ?? 0 ?></div>
            </div>
            <div class="summary-item">
                <div class="summary-icon" style="background: #ffebee;">
                    <i class="fas fa-flask" style="color: #c62828;"></i>
                </div>
                <h3>Bahan Kimia</h3>
                <div class="value"><?= $jenis_kargo['⚠️ Bahan Kimia'] ?? 0 ?></div>
            </div>
            <div class="summary-item">
                <div class="summary-icon" style="background: #fff3e0;">
                    <i class="fas fa-glass-broken" style="color: #f57c00;"></i>
                </div>
                <h3>Pecah Belah</h3>
                <div class="value"><?= $jenis_kargo['🥚 Pecah Belah'] ?? 0 ?></div>
            </div>
        </div>

        <!-- Tabel Data Kargo -->
        <div class="table-container">
            <div class="table-header">
                <h2>
                    <i class="fas fa-clipboard-list"></i>
                    Daftar Transaksi Pengiriman
                </h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Resi</th>
                        <th>Pengirim</th>
                        <th>Kota Tujuan</th>
                        <th>Berat (Kg)</th>
                        <th>Jenis Kargo</th>
                        <th>Tarif Dasar</th>
                        <th>Total Tarif</th>
                        <th>SOP Packing</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($data_kargo) > 0): ?>
                        <?php $no = 1; ?>
                        <?php foreach($data_kargo as $item): ?>
                            <?php $k = $item['kargo']; ?>
                            <?php 
                            // Safe formatting dengan pengecekan null
                            $tarifDasar = $k->getTarifDasar();
                            $totalTarif = isset($item['tarif']) ? $item['tarif'] : 0;
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= htmlspecialchars($k->getIdResi()) ?></strong></td>
                                <td><?= htmlspecialchars($k->getPengirim()) ?></td>
                                <td><?= htmlspecialchars($k->getKotaTujuan()) ?></td>
                                <td><?= $k->getBeratBarang() ?> kg</td>
                                <td>
                                    <span class="badge 
                                        <?= $item['jenis'] == '📦 Reguler' ? 'badge-reguler' : '' ?>
                                        <?= $item['jenis'] == '⚠️ Bahan Kimia' ? 'badge-kimia' : '' ?>
                                        <?= $item['jenis'] == '🥚 Pecah Belah' ? 'badge-pecah' : '' ?>">
                                        <?= $item['jenis'] ?>
                                    </span>
                                </td>
                                <td><?= !is_null($tarifDasar) ? 'Rp ' . number_format($tarifDasar, 0, ',', '.') . '/kg' : 'Rp 0/kg' ?></td>
                                <td class="tarif">Rp <?= number_format($totalTarif, 0, ',', '.') ?></td>
                                <td class="sop-preview" title="<?= htmlspecialchars($item['sop']) ?>">
                                    <i class="fas fa-shield-alt"></i> 
                                    <?= substr(htmlspecialchars($item['sop']), 0, 60) ?>...
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px;">
                                <i class="fas fa-inbox" style="font-size: 48px; color: #cbd5e0;"></i>
                                <p style="margin-top: 10px; color: #718096;">Belum ada data kargo</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>
                <i class="fas fa-code-branch"></i> Implementasi OOP: Abstraction • Encapsulation • Inheritance • Polymorphism
                <br>
                <i class="fas fa-database"></i> Database: MySQL | Dynamic Binding via Polymorphic Collection
            </p>
        </div>
    </div>
</body>
</html>