<?php 
session_start();
// include 'includes/config.php'; //bisa dihapus jika tidak digunakan (KAMIS)

//Proses login
$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //untuk testing gunakan password sederhana
    $users = [
        'admin' => ['password' => 'admin123', 'role' => 'admin', 'nama' => 'Admin Utama'],
        'petugas1' => ['password' => 'petugas123', 'role' => 'petugas', 'nama' => 'Petugas Satu'],
        'peminjam1' => ['password' => 'peminjam123', 'role' => 'peminjam', 'nama' => 'Peminjam Satu']
    ];

    if (isset($users[$username]) && $users[$username]['password'] == $password) {

    // PERUBAHAN KAMIS ######## 
        $_SESSION['user_id'] = 1;
        // $idsaatini = $conn->query("SELECT id FROM user WHERE username = '$username'")->fetch_assoc()['id'];

        // $_SESSION['user_id'] = $idsaatini;

        // AKHIR PERUBAHAN KAMIS ######## 

        $_SESSION['username'] = $username;
        $_SESSION['role'] = $users[$username]['role'];
        $_SESSION['nama'] = $users[$username]['nama'];


        //redirect berdasarkan role
        if ($users[$username]['role'] == 'admin') {
            header('Location: admin/dashboard.php');
        } elseif ($users[$username]['role'] == 'petugas') {
            header('Location: petugas/dashboard.php');
        } else {
            header('Location: peminjam/dashboard.php');
        }
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Peminjaman Alat</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            padding: 40px;
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .logo {
            font-size: 50px;
            color: #667eea;
            margin-bottom: 20px;
        }

        .h1 {
            color: #333;            
            margin-bottom: 10px;
            font-size: 28px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
            line-height: 1.5;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            min: 150px;
        }

        .btn-login {
            background: #667eea;
            color: white;
        }

        .btn-register {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .demo-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
            text-align: left
        }

        .demo-info h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .account {
            background: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            border-left: 4px solid;
        }

        .account.admin { border-color: #e74c3c;}
        .account.petugas { border-color: #f39c12;}
        .account.peminjam { border-color: #2ecc71;}

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }

        /* Jika sudah login */
        .welcome-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .welcome-icon {
            font-size: 60px;
            color: #2ecc71;
            margin-bottom: 20px;
        }

        .redirect-info {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        #countdown {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin: 10px 0;
        }

        /* Style untuk form login  */
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            max-width: 400px;
            width: 100%;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-size: 24px;
        }

        .login-box input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .login-box button:hover {
            background: #5a67d8;
        }

        .error {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background: #ffe6e6;
            border-radius: 5px;
            border-left: 4px solid #e74c3c;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .back-link {
            color: #667eea;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        /* Mode login - hanya form  */
        .login-mode .logo
        .login-mode .subtitle
        .login-mode .button-group
        .login-mode .demo-info {
            display: none;
        }

    </style>
</head>
<body>
    <?php 
    //cek apakah user sudah login
    if (isset($_SESSION['user_id'])) {
        //user sudah login
        ?>
        <div class="welcome-box">
            <div class="welcome-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Selamat Datang Kembali!</h1>
            <p>Anda login sebagai: <strong><?php echo $_SESSION['nama'] ?? $_SESSION['username']; ?></strong></p>
            <p>Role: <strong><?php echo ucfirst($_SESSION['role']); ?></strong></p>

            <div class="redirect-info">
                <p>Mengarahkan ke dashboard dalam:</p>
                <div id="countdown">5</div>
                <p>detik...</p>
            </div>

            <a href="<?php echo $_SESSION['role'] == 'admin' ? 'admin/dashboard.php' : ($_SESSION['role'] == 'petugas' ? 'petugas/dashboard.php' : 'peminjam/dashboard.php'); ?>" class="btn btn-login">
                Lanjut ke Dashboard
            </a>
        </div>

        <script>
            // Countdown redirect 
            let seconds = 5;
            const countdownElement = document.getElementById('countdown');

            const countdown = setInterval(() => {
                seconds--;
                countdownElement.textContent = seconds;

                if (seconds <= 0) {
                    clearInterval(countdown);
                    window.location.href = "<?php echo $_SESSION['role'] == 'admin' ? 'admin/dashboard.php' : ($_SESSION['role'] == 'petugas' ? 'petugas/dashboard.php' : 'peminjam/dashboard.php'); ?>";

                }
            }, 1000);
        </script>
        <?php
    }

    //cek apakah mode login (ada parameter ?login=true)
    elseif (isset($_GET['login']) && $_GET['login'] == 'true') {
        //tampilkan form login
        ?>
        <div class="login-box">
            <div class="logo">
                <i class="fas fa-lock"></i>
            </div>
            <h2>Login</h2>
            <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

            <form action="" method="post">
                <input type="text" name="username" id="" placeholder="Username" required>
                <input type="password" name="password" id="" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>

            <div class="back-link">
                <h3>Akun Demo:</h3>
                <!-- atiati####  -->
                <div class="account admin">
                    <strong>Admin:</strong> admin / admin123
                </div>
                <div class="account petugas">
                    <strong>Petugas:</strong> petugas1 / petugas123
                </div>                
                <div class="account peminjam">
                    <strong>Peminjam:</strong> peminjam1 / peminjam123
                </div>

            </div>

        </div>

        <?php 
        } else {
            //user belum login - tampilkan halaman utama
        
        ?>

        <div class="container">
            <div class="logo">
                <i class="fas fa-tools"></i>
            </div>
            <h1>Website Peminjaman Alat</h1>
            <p class="subtitle">
                Sistem manajemen peminjaman alat laboratorium yang terintegrasi.
                Kelola peminjaman, pelacakan, dan laporan dengan mudah.
            </p>

            <div class="button-group">
                <a href="index.php?login=true" class="btn btn-login">Login</a>
                <a href="register.php" class="btn btn-register">Daftar</a>
            </div>

            <div class="demo-info">
                <h3>Akun Demo:</h3>
                <div class="account admin">
                    <strong>Admin:</strong> admin / admin123
                </div>
                <div class="account petugas">
                    <strong>Petugas:</strong> petugas1 / petugas123
                </div>                
                <div class="account peminjam">
                    <strong>Peminjam:</strong> peminjam1 / peminjam123
                </div>
            </div>

            <div class="footer">
                <p>C 2026 Ujian Kompetensi Keahlian (UKK)</p>
                <p>Rekayasa Perangkat Lunak</p>
            </div>
        </div>

        <?php

        }
        ?>

        <!-- Font Awesome  -->
         <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>