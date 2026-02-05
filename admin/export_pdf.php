<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    exit("Akses ditolak"); 
}

$tgl_mulai = isset($_GET['mulai']) ? $_GET['mulai'] : date('Y-m-01');
$tgl_sampai = isset($_GET['sampai']) ? $_GET['sampai'] : date('Y-m-d');
$admin_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Administrator';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan_Glory_<?= $tgl_mulai ?></title>
    <style>
        /* Menghilangkan Header & Footer Browser (URL http://...) saat Print */
        @page { size: auto; margin: 0mm; }
        
        body { font-family: 'Helvetica', Arial, sans-serif; margin: 0; padding: 0; color: #000; background-color: #fff; }
        
        /* Desain Header Hitam & Oranye */
        .header { background: #000; color: #fff; padding: 40px 20px; text-align: center; border-bottom: 8px solid #e67e22; }
        .logo-box { font-size: 50px; font-weight: 900; color: #e67e22; margin-bottom: 5px; line-height: 1; }
        .header h1 { margin: 0; letter-spacing: 3px; font-size: 28px; }
        .header p { margin: 5px 0 0; font-size: 12px; opacity: 0.8; letter-spacing: 2px; text-transform: uppercase; }

        .content { padding: 40px; }
        .info { margin-bottom: 30px; font-size: 14px; border-left: 5px solid #e67e22; padding-left: 15px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f2f2f2; border: 1px solid #000; padding: 12px; font-size: 12px; text-transform: uppercase; }
        td { border: 1px solid #000; padding: 12px; font-size: 13px; }
        
        .total-row { background: #000; color: #fff; font-weight: bold; }
        .total-row td { border: 1px solid #000; color: #fff; }

        .footer { margin-top: 60px; text-align: right; display: flex; justify-content: flex-end; }
        .signature { text-align: center; width: 250px; }
        
        /* Ikon Logo Admin di Tanda Tangan */
        .admin-icon { 
            width: 60px; height: 60px; background: #e67e22; color: #fff; 
            border-radius: 50%; display: flex; align-items: center; justify-content: center; 
            margin: 0 auto 10px; font-weight: bold; font-size: 24px;
        }

        .no-print { background: #333; padding: 15px; text-align: center; }
        .btn-print { 
            padding: 12px 25px; background: #e67e22; color: white; border: none; 
            border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 14px;
        }

        @media print { 
            .no-print { display: none; }
            body { padding: 20mm; } /* Memberi ruang agar tidak terlalu mepet kertas */
            .header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">CETAK LAPORAN SEKARANG</button>
        <button class="btn-print" style="background:#555; margin-left:10px;" onclick="window.close()">TUTUP</button>
    </div>

    <div class="header">
        <div class="logo-box">G</div>
        <h1>GLORY <span style="color:#e67e22;">SPORT</span></h1>
        <p>Official Revenue Report</p>
    </div>

    <div class="content">
        <div class="info">
            <strong>Periode Laporan:</strong> <?= date('d M Y', strtotime($tgl_mulai)) ?> — <?= date('d M Y', strtotime($tgl_sampai)) ?><br>
            <strong>Status Transaksi:</strong> Terverifikasi Lunas
        </div>

        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="35%">Nama Pelanggan</th>
                    <th width="25%">Arena</th>
                    <th width="20%">Nominal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1; $grand_total = 0;
                $sql = "SELECT b.*, u.nama_lengkap, l.nama_lapangan 
                        FROM booking b 
                        JOIN user u ON b.id_user = u.id_user 
                        JOIN lapangan l ON b.id_lapangan = l.id_lapangan 
                        WHERE b.status = 'Lunas' 
                        AND b.tgl_main BETWEEN '$tgl_mulai' AND '$tgl_sampai'
                        ORDER BY b.tgl_main ASC";
                $query = mysqli_query($db, $sql);
                
                while($row = mysqli_fetch_array($query)):
                    $grand_total += $row['total_harga'];
                ?>
                <tr>
                    <td align="center"><?= $no++; ?></td>
                    <td align="center"><?= date('d/m/Y', strtotime($row['tgl_main'])) ?></td>
                    <td><strong><?= strtoupper($row['nama_lengkap']) ?></strong></td>
                    <td><?= $row['nama_lapangan'] ?></td>
                    <td align="right">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
                <tr class="total-row">
                    <td colspan="4" align="right">TOTAL PENDAPATAN BERSIH</td>
                    <td align="right">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <div class="signature">
                <p>Jambi, <?= date('d F Y') ?></p>
                <div class="admin-icon">A</div>
                <div style="margin-top: 5px;"><strong><?= strtoupper($admin_name) ?></strong></div>
                <div style="border-top: 2px solid #000; margin-top: 5px; padding-top: 5px; font-size: 12px; color: #555;">
                    Admin Glory Sport Center
                </div>
            </div>
        </div>
    </div>

</body>
</html>