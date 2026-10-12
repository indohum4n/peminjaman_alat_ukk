<?php 
session_start();
include '../includes/config.php';
include '../includes/auth.php';
include '../includes/functions.php';

checkAuth('admin');

$user_id = $_SESSION['user_id'];

//statistik peminjam
$total_pinjaman = $conn->query("SELECT count(*) as total FROM peminjaman where user_id = $user_id")->fetch_assoc()['total'];
$pinjaman_aktif = $conn->query("SELECT count(*) as total FROM peminjaman where user_id = $user_id and status = 'disetujui' and tanggal_kembali is null")->fetch_assoc()['total'];
$total_denda = $conn->query("SELECT sum(denda) as total FROM peminjaman where user_id = $user_id")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peminjam</title>
    <link rel="stylesheet" href="../style.css">
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
        <h2>Dashboard Peminjam</h2>
        <p>Selamat datang<?php $_SESSION['nama'] ?? $_SESSION['username']; ?>!</p>

        <!-- Ati-Ati##### -->
        <div class="stats">
            <div class="stat-card">
                <h3>Total Pinjaman</h3>
                <p><?php echo $total_pinjaman; ?></p>
            </div>
            <div class="stat-card">
                <h3>Pinjaman Aktif</h3>
                <p><?php echo $pinjaman_aktif; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Denda</h3>
                <p>Rp <?php echo number_format($total_denda, 0, ',', '.'); ?></p>
            </div>
        </div>

        <div class="card">
            <h3>Pinjaman Aktif Anda</h3>
            <?php 
            $pinjaman = $conn->query("
            SELECT p.*, a.nama_alat
            FROM peminjaman p
            join alat a on p.alat_id = a.id
            where p.user_id = $user_id
            and p.status = 'disetujui'
            and p.tanggal_kembali is NULL
            ORDER BY p.tanggal_pinjam DESC
            ");

            if($pinjaman->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Alat</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pinjam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $pinjaman->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['nama_alat']; ?></td>
                            <td><?php echo $row['jumlah']; ?></td>
                            <td><?php echo formatDate($row['tanggal_pinjam']); ?></td>
                            <td><span class="badge badge-success">Aktif</span></td>
                        </tr>
                        <?php endwhile ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>Anda tidak memiliki pinjaman aktif</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>