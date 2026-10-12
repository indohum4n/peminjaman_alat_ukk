<?php
session_start();
include '../includes/config.php';
include '../includes/auth.php';
include '../includes/functions.php';

checkAuth('admin');

$user_id = $_SESSION['user_id'];

$riwayat = $conn->query("
    SELECT p.*, a.nama_alat
    FROM peminjaman p
    join alat a on p.alat_id = a.id
    where p.user_id = $user_id
    order by p.tanggal_pinjam desc
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pinjam - Peminjaman</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            color: white;
        }
        .status-diajukan {background: #6c757d;}
        .status-disetujui {background: #28a745;}
        .status-ditolak {background: #dc3545;}
        .status-dikembalikan {background: #007bff;}
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Menu Peminjam</h3>
        <a href="dashboard.php">Dashboard</a>
        <a href="pinjam.php">Pinjam Alat</a>
        <a href="riwayat.php">Riwayat Pinjam</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="content">
        <h2>Riwayat Peminjaman Anda</h2>

        <?php if ($riwayat->num_rows > 0): ?>
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Alat</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $riwayat->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['nama_alat']; ?></td>
                                <td><?php echo $row['jumlah']; ?></td>
                                <td><?php echo formatDate($row['tanggal_pinjam']); ?></td>
                                <td><?php echo $row['tanggal_kembali'] ? formatDate($row['tanggal_kembali']): '-'; ?></td>
                                <td>
                                    <span class="badge-success status-<?php echo $row['status']; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td>Rp <?php echo number_format($row['denda'], 0, ',', '.' ); ?></td>
                            </tr>
                            <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="card">
                    <p>Anda belum memiliki riwayat peminjaman.</p>
                </div>
                <?php endif; ?>
    </div>
</body>
</html>