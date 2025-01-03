<?php
session_start();
include_once("../../config/conn.php");

// Redirect jika sudah login
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    echo "<meta http-equiv='refresh' content='0; url=../..'>";
    die();
}

// Proses login
if (isset($_POST['klik'])) {
    $username = trim($_POST['nama']);
    $password = trim($_POST['alamat']);
    
    // Login sebagai admin
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['login'] = true;
        $_SESSION['id'] = null;
        $_SESSION['username'] = 'admin';
        $_SESSION['akses'] = 'admin';
        echo "<meta http-equiv='refresh' content='0; url=../admin'>";
        die();
    } else {
        // Login sebagai dokter
        try {
            $cek_username = $pdo->prepare("SELECT * FROM dokter WHERE nama = :nama");
            $cek_username->bindParam(':nama', $username, PDO::PARAM_STR);
            $cek_username->execute();

            if ($cek_username->rowCount() === 1) {
                $baris = $cek_username->fetch(PDO::FETCH_ASSOC);
                if ($password === $baris['alamat']) { // Cek password
                    $_SESSION['login'] = true;
                    $_SESSION['id'] = $baris['id'];
                    $_SESSION['username'] = $baris['nama'];
                    $_SESSION['akses'] = 'dokter';
                    echo "<meta http-equiv='refresh' content='0; url=../dokter/index.php'>";
                    die();
                } else {
                    $_SESSION['error'] = 'Password tidak cocok.';
                }
            } else {
                $_SESSION['error'] = 'Username tidak ditemukan.';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }
    }

    // Jika gagal login
    echo "<meta http-equiv='refresh' content='0; url=login.php'>";
    die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Poliklinik | Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f2f5;
        }

        .container {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
            background: white;
            padding: 20px;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
        }

        .form-control {
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .alert {
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3 class="text-center mb-4">Login ke Sistem Poliklinik</h3>
        <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php } ?>
        <form method="POST">
            <div class="mb-3">
                <label for="nama" class="form-label">Username</label>
                <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Password</label>
                <input type="password" id="alamat" name="alamat" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="klik" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>

</html>
