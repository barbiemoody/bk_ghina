<?php
session_start();
include_once("../../config/conn.php");

// Redirect jika sudah login
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: ../..");
    exit();
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['nama']);
    $password = trim($_POST['alamat']);

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Username atau Password tidak boleh kosong.';
        header("Location: login.php");
        exit();
    }

    // Login sebagai admin
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['login'] = true;
        $_SESSION['id'] = null;
        $_SESSION['username'] = 'admin';
        $_SESSION['akses'] = 'admin';
        header("Location: ../admin/index.php");
        exit();
    }

    // Login sebagai pasien
    try {
        $stmt = $pdo->prepare("SELECT * FROM pasien WHERE nama = :nama");
        $stmt->bindParam(':nama', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $baris = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($password === $baris['alamat']) { // Validasi password
                $_SESSION['login'] = true;
                $_SESSION['id'] = $baris['id'];
                $_SESSION['username'] = $baris['nama'];
                $_SESSION['no_rm'] = $baris['no_rm'];
                $_SESSION['akses'] = 'pasien';
                header("Location: ../pasien/index.php");
                exit();
            } else {
                $_SESSION['error'] = 'Password tidak cocok.';
            }
        } else {
            $_SESSION['error'] = 'Username tidak ditemukan.';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }

    // Jika login gagal
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login Pasien</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
            padding: 20px 0;
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

        .register-link {
            display: block;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3 class="text-center mb-4">Login Pasien</h3>
        <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
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
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <a href="register.php" class="register-link">Belum punya akun? Daftar di sini</a>
    </div>
</body>

</html>
