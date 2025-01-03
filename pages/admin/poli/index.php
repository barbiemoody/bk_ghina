<?php
include_once("../../../config/conn.php");
session_start();

// Redirect jika tidak login
if (!isset($_SESSION['login'])) {
    echo "<meta http-equiv='refresh' content='0; url=../auth/login.php'>";
    die();
}

$_SESSION['login'] = true;

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

// Redirect jika bukan admin
if ($akses !== 'admin') {
    echo "<meta http-equiv='refresh' content='0; url=../..'>";
    die();
}

$title = 'Poliklinik | Poli';
// Breadcrumb section
ob_start(); ?>
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="<?= $base_admin; ?>">Home</a></li>
    <li class="breadcrumb-item active">Poli</li>
</ol>
<?php
$breadcrumb = ob_get_clean();

// Title Section
ob_start(); ?>
Mengelola Poli
<?php
$main_title = ob_get_clean();

// Content Section
ob_start();
?>
<form class="form col" method="POST" action="" name="myForm">
    <?php
    $nama_poli = '';
    $keterangan = '';
    if (isset($_GET['id'])) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM poli WHERE id = :id");
            $stmt->bindParam(':id', $_GET['id']);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $nama_poli = htmlspecialchars($row['nama_poli']);
                $keterangan = htmlspecialchars($row['keterangan']);
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']) ?>">
    <?php
    }
    ?>
    <div class="row mt-3">
        <label for="nama_poli" class="form-label fw-bold">Nama Poli</label>
        <input type="text" class="form-control" name="nama_poli" id="nama_poli" placeholder="Nama poli" value="<?= $nama_poli ?>" required>
    </div>
    <div class="row mt-3">
        <label for="keterangan" class="form-label fw-bold">Keterangan</label>
        <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" value="<?= $keterangan ?>" required>
    </div>
    <div class="row d-flex mt-3 mb-3">
        <button type="submit" class="btn btn-primary rounded-pill" style="width: 3cm;" name="simpan">Simpan</button>
    </div>
</form>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Mengelola Poli</h3>
    </div>
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Poli</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Kelola</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $stmt = $pdo->query("SELECT * FROM poli");
                    $no = 1;
                    while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>
                                <td>' . $no++ . '</td>
                                <td>' . htmlspecialchars($data['nama_poli']) . '</td>
                                <td>' . htmlspecialchars($data['keterangan']) . '</td>
                                <td>
                                    <a class="btn btn-success rounded-pill px-3" href="index.php?page=poli&id=' . $data['id'] . '">Edit</a>
                                    <a class="btn btn-danger rounded-pill px-3" href="index.php?page=poli&id=' . $data['id'] . '&aksi=hapus">Hapus</a>
                                </td>
                            </tr>';
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    try {
        if (isset($_POST['id'])) {
            $stmt = $pdo->prepare("UPDATE poli SET 
                                    nama_poli = :nama_poli,
                                    keterangan = :keterangan
                                    WHERE id = :id");
            $stmt->bindParam(':nama_poli', $_POST['nama_poli']);
            $stmt->bindParam(':keterangan', $_POST['keterangan']);
            $stmt->bindParam(':id', $_POST['id']);
            $stmt->execute();
        } else {
            $stmt = $pdo->prepare("INSERT INTO poli(nama_poli, keterangan) 
                                    VALUES (:nama_poli, :keterangan)");
            $stmt->bindParam(':nama_poli', $_POST['nama_poli']);
            $stmt->bindParam(':keterangan', $_POST['keterangan']);
            $stmt->execute();
        }
        echo "<meta http-equiv='refresh' content='0; url=index.php?page=poli'>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    try {
        $stmt = $pdo->prepare("DELETE FROM poli WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        echo "<meta http-equiv='refresh' content='0; url=index.php?page=poli'>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
$content = ob_get_clean();
include '../../../layouts/index.php';
