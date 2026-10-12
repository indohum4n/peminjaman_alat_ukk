<?php 
session_start();
include '../includes/config.php';
include '../includes/auth.php';
include '../includes/functions.php';

checkAuth(); //bisa diakses dengan admin dan petugas

$alat = $conn->query("
    a.*, k.nama_kategori
    from alat a
    left join kategori k on a.kategori_id = k.id
    order by a.nama_alat
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Alat - Petugas</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="sidebar">
        <h3>Menu Petugas</h3>
        <a href="dashboard.php">Dashboard</a>        
        <a href="alat.php">Kelola Alat</a>        
        <a href="peminjaman.php">Kelola Peminjaman</a>
        <a href="pengembalian.php">Pengembalian</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="content">
        <h2>Data Alat</h2>

        <div class="card">
            <h3>Daftar Alat Tersedia</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $alat->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nama_alat']; ?></td>
                            <td><?php echo $row['nama_kategori'] ?? '-'; ?></td>
                            <td><?php echo $row['stok']; ?></td>
                            <td>
                                <?php if($row['stok'] > 0): ?>
                                    <span class="badge badge-success">Tersedia</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Habis</span>
                                <?php endif; ?>
                            </td>                            
                        </tr>
                        <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>