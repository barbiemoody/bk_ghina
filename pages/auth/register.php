<?php
session_start();
include_once("../../config/conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars($_POST['nama']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $no_ktp = htmlspecialchars($_POST['no_ktp']);
    $no_hp = htmlspecialchars($_POST['no_hp']);
    $tahun_bulan = date("Ym");

    // Check if the patient already exists based on KTP
    $check_pasien = $conn->prepare("SELECT id, nama, no_rm FROM pasien WHERE no_ktp = ?");
    $check_pasien->bind_param("s", $no_ktp);
    $check_pasien->execute();
    $result_check_pasien = $check_pasien->get_result();

    if ($result_check_pasien->num_rows > 0) {
        $row = $result_check_pasien->fetch_assoc();
        if ($row['nama'] != $nama) {
            echo "<script>alert('Nama pasien tidak sesuai dengan nomor KTP yang terdaftar.');</script>";
            echo "<meta http-equiv='refresh' content='0; url=register.php'>";
            die();
        }
        $_SESSION['signup'] = true;
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $nama;
        $_SESSION['no_rm'] = $row['no_rm'];
        $_SESSION['akses'] = 'pasien';

        echo "<meta http-equiv='refresh' content='0; url=../pasien'>";
        die();
    }

    // Fetch and reorder `no_rm` values for 
    $get_rm = $conn->prepare("SELECT id FROM pasien WHERE no_rm LIKE CONCAT(?, '-%') ORDER BY CAST(SUBSTRING(no_rm, 8) AS SIGNED) ASC");
    $get_rm->bind_param("s", $tahun_bulan);
    $get_rm->execute();
    $result_rm = $get_rm->get_result();

    // Reassign `no_rm` sequentially from 001 
    $newQueueNumber = 1;
    $updatedRecords = [];

    while ($row_rm = $result_rm->fetch_assoc()) {
        $new_no_rm = $tahun_bulan . "-" . str_pad($newQueueNumber, 3, '0', STR_PAD_LEFT);
        $updatedRecords[] = [
            'id' => $row_rm['id'],
            'new_no_rm' => $new_no_rm
        ];
        $newQueueNumber++;
    }

    // Update the `no_rm` values in the database
    foreach ($updatedRecords as $record) {
        $update_rm = $conn->prepare("UPDATE pasien SET no_rm = ? WHERE id = ?");
        $update_rm->bind_param("si", $record['new_no_rm'], $record['id']);
        $update_rm->execute();
    }

    // Generate the new `no_rm` for the current patient
    $no_rm = $tahun_bulan . "-" . str_pad($newQueueNumber, 3, '0', STR_PAD_LEFT);

    // Insert the new patient record
    $insert = $conn->prepare("INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("sssss", $nama, $alamat, $no_ktp, $no_hp, $no_rm);

    if ($insert->execute()) {
        $_SESSION['signup'] = true;
        $_SESSION['id'] = $insert->insert_id;
        $_SESSION['username'] = $nama;
        $_SESSION['no_rm'] = $no_rm;
        $_SESSION['akses'] = 'pasien';

        echo "<meta http-equiv='refresh' content='0; url=../pasien'>";
        die();
    } else {
        echo "Error: " . $insert->error;
    }

    $insert->close();
    $check_pasien->close();
    $get_rm->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register Pasien</title>
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
        .login-link {
            display: block;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3 class="text-center mb-4">Register Pasien</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <input type="text" id="alamat" name="alamat" class="form-control" placeholder="Masukkan alamat" required>
            </div>
            <div class="mb-3">
                <label for="no_ktp" class="form-label">Nomor KTP</label>
                <input type="number" id="no_ktp" name="no_ktp" class="form-control" placeholder="Masukkan nomor KTP" required>
            </div>
            <div class="mb-3">
                <label for="no_hp" class="form-label">Nomor HP</label>
                <input type="number" id="no_hp" name="no_hp" class="form-control" placeholder="Masukkan nomor HP" required>
            </div>
            <button type="submit" name="register" class="btn btn-primary">Register</button>
        </form>
        <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/bk_ghina/pages/auth/login-pasien.php" class="login-link">Sudah punya akun? Login di sini</a>
    </div>
</body>

</html>


<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>
