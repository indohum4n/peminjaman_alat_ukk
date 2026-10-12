<?php 
session_start();
include '../includes/config.php';
include '../includes/auth.php';
include '../includes/functions.php';

checkAuth('admin');

$user_id = $_SESSION['user_id'];

//ajukan peminjaman
if(isset($_POST['ajukan'])) {
    $alat_id = $_POST['alat_id'];
    $jumlah = $_POST['jumlah'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];

    //cek stok
    $stok = $conn->query("SELECT stok from alat where id = $alat_id")->fetch_assoc()['stok'];

    if($jumlah <= $stok) {
        $stmt = $conn->prepare("INSERT into peminjaman (alat_id, user_id, jumlah, tanggal_pinjam, status) values (?,?,?,?,'diajukan')");
        $stmt->bind_param("iiis", $alat_id, $user_id, $jumlah, $tanggal_pinjam);

        if($stmt->execute()) {
            logActivity($user_id, "Mengajukan peminjaman alat ID: $alat_id");
            $message = showAlert('success', 'Pengajuan permintaan berhasil! Menunggu persetujuan petugas.');
        } else {
            $message = showAlert('danger', 'Gagal mengajukan peminjaman!');
        }
    } else {
        $message = showAlert('danger', 'Stok tidak mencukupi! Stok tersedia: '. $stok);
    }
}

//ambil data alat yang tersedia
$alat = $conn->query("
    a.*, k.nama_kategori
    from alat a
    left join kategori k on a.kategori_id = k.id
    where a.stok > 0
    order by a.nama_alat
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam Alat - Peminjam</title>
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
        <h2>Pinjam Alat</h2>

        <?php if(isset($message)) echo $message; ?>

        <div class="card">
            <h3>Ajukan Peminjaman</h3>
            <form action="" method="post" class="form">
                <div class="form-group">
                    <label for="">Pilih Alat</label>
                    <select name="alat_id" id="" class="form-control" required>
                        <option value="">-- Pilih Alat --</option>
                        <?php while($row = $alat->fetch_assoc()): ?>
                            <option value="<?php $row['id']; ?>">
                                <?php echo $row['nama_alat']; ?> (Stok: <?php echo $row['stok']; ?>) - <?php echo $row['nama_kategori']; ?>
                            </option>
                            <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Jumlah</label>
                    <input type="number" name="jumlah" id="" class="form-control" min="1" required>
                </div>

                <div class="form-group">
                    <label for="">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" id="" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <button type="submit" name="ajukan" class="btn btn-primary">Ajukan Peminjaman</button>
            </form>
        </div>

        <div class="card">
            <h3>Alat Tersedia</h3>
            <?php 
            
            $alat->data_seek(0); //Reset pointer result
            if($alat->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $alat->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['nama_alat']; ?></td>
                            <td><?php echo $row['nama_kategori']; ?></td>
                            <td><?php echo $row['stok']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>Tidak ada alat yang tersedia untuk dipinjam.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
