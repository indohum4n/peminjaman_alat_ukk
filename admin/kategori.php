<?php 
session_start();
include '../includes/config.php';
include '../includes/auth.php';
include '../includes/functions.php';

checkAuth('admin');

// Tambah kategori
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_kategori'];

    $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori) values (?)");
    $stmt->bind_param("s", $nama);

    if($stmt->execute()) {
        logActivity($_SESSION['user_id'], "Menambah kategori: $nama");
        $message = showAlert('success', 'Kategori berhasil ditambahkan!');
    }
}

//edit kategori
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_kategori'];

    $stmt = $conn->prepare("UPDATE kategori SET nama_kategori = ? WHERE id = ?");
    $stmt->bind_param("si", $nama, $id);

    if($stmt->execute()) {
        logActivity($_SESSION['user_id'], "Mengedit kategori ID $id: $nama");
        $message = showAlert('success', 'Kategori berhasil diupdate!');

    } else {
        $message = showAlert('danger', 'Gagal mengupdate kategori');
    }
}

//hapus kategori
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    //cek apakah kategori digunakan
    $check = $conn->query("SELECT COUNT(*) as total FROM alat where kategori_id = $id");

    // Yokpo iki pak, value ndek njobo #####
    $count = $check->fetch_assoc()['total']; 

    if ($count == 0) {
        $conn->query("DELETE FROM kategori where id = $id");
        logActivity($_SESSION['user_id'], "Menghapus kategori ID: $id");
        $message = showAlert('success', 'Kategori berhasil dihapus!');
    } else {
        $message = showAlert('danger', 'Kategori tidak bisa dihapus karena masih digunakan!');
    }
}

$kategori = $conn->query("SELECT * FROM kategori ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .modal {display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4);}
        .modal-content {background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 50%;}
        .close {color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Menu Admin</h3>
        <a href="dashboard.php">Dashboard</a>
        <a href="user.php">Kelola User</a>
        <a href="alat.php">Kelola Alat</a>
        <a href="kategori.php">Kelola Kategori</a>
        <a href="peminjaman.php">Kelola Peminjaman</a>
        <a href="laporan.php">Kelola Laporan</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="content">
        <h2>Kelola Kategori Alat</h2>

        <?php if (isset($message)) echo $message; ?>

        <div class="card">
            <h3>Tambah Kategori Baru</h3>
            <form action="" method="POST" class="form-inline">
                <input type="text" name="nama_kategori" id="" placeholder="Nama Kategori">
                <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
            </form>
        </div>

        <div class="card">
            <h3>Daftar Kategori</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Alat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $kategori->fetch_assoc()): $count = $conn->query("SELECT COUNT (*) as total FROM alat where kategori_id = {$row['id']}")->fetch_assoc()['total']; ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nama_kategori']; ?></td>
                        <td><?php echo $count; ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editKategori(<?php echo $row['id']; ?>, '<?php echo $row['nama_kategori']; ?>')">Edit</button>
                            <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin menghapus kategori ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit -->
     <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Edit Kategori</h3>
            <form action="" method="post" id="editForm">
                <input type="hidden" name="id" id="editId">
                <div class="form-group">
                    <label for="">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="editNama" class="form-control" required>
                </div>
                <button type="submit" name="edit" class="btn btn-primary">Update</button>
            </form>
        </div>
     </div>


     <script>
        function editKategori(id, nama) {
            document.getElementById('editId').value = id;
            document.getElementById('editNama').value = nama;
            document.getElementById('editModal').value = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        //close modal while clicking outside
        window.onclick =function(event) {
            var modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

     </script>


</body>
</html>


