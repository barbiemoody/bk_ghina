<?php
include_once("../../../config/conn.php");
session_start();

// Cek sesi login
if (!isset($_SESSION['login'])) {
    echo "<meta http-equiv='refresh' content='0; url=../auth/login.php'>";
    die();
}

$_SESSION['login'] = true;
$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

// Cek akses admin
if ($akses !== 'admin') {
    echo "<meta http-equiv='refresh' content='0; url=../..'>";
    die();
}

$title = 'Poliklinik | Obat';

// Breadcrumb Section
ob_start(); ?>
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="<?= $base_admin; ?>">Home</a></li>
    <li class="breadcrumb-item active">Obat</li>
</ol>
<?php
$breadcrumb = ob_get_clean();

// Title Section
ob_start(); ?>
Obat
<?php
$main_title = ob_get_clean();

// Content Section
ob_start();
?>
<form class="form col" method="POST" action="" name="myForm">
    <?php
    $nama_obat = '';
    $kemasan = '';
    $harga = '';
    if (isset($_GET['id'])) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM obat WHERE id = :id");
            $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $nama_obat = htmlspecialchars($row['nama_obat']);
                $kemasan = htmlspecialchars($row['kemasan']);
                $harga = htmlspecialchars($row['harga']);
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']); ?>">
    <?php
    }
    ?>
    <div class="row mt-3">
        <label for="nama_obat" class="form-label fw-bold">Nama Obat</label>
        <input type="text" class="form-control" name="nama_obat" id="nama_obat" placeholder="Nama Obat" value="<?= $nama_obat ?>" required>
    </div>
    <div class="row mt-3">
        <label for="kemasan" class="form-label fw-bold">Kemasan</label>
        <input type="text" class="form-control" name="kemasan" id="kemasan" placeholder="Kemasan" value="<?= $kemasan ?>" required>
    </div>
    <div class="row mt-3">
        <label for="harga" class="form-label fw-bold">Harga</label>
        <input type="number" class="form-control" name="harga" id="harga" placeholder="Harga" value="<?= $harga ?>" required>
    </div>
    <div class="row d-flex mt-3 mb-3">
    <button type="submit" class="btn btn-primary rounded-pill" style="width: 3cm; background-color:rgb(104, 197, 152); color: white;" name="simpan">Simpan</button>
</div>

</form>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Obat</h3>
    </div>
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Obat</th>
                    <th scope="col">Kemasan</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Kelola</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $stmt = $pdo->query("SELECT * FROM obat");
                    $no = 1;
                    while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>
                                <td>' . $no++ . '</td>
                                <td>' . htmlspecialchars($data['nama_obat']) . '</td>
                                <td>' . htmlspecialchars($data['kemasan']) . '</td>
                                <td>Rp. ' . number_format($data['harga'], 0, ',', '.') . '</td>
                                <td>
                                    <a class="btn btn-success rounded-pill px-3" href="index.php?page=obat&id=' . $data['id'] . '">Edit</a>
                                    <a class="btn btn-danger rounded-pill px-3" href="index.php?page=obat&id=' . $data['id'] . '&aksi=hapus" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')">Hapus</a>
                                </td>
                            </tr>';
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </tbody>
        </table>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
            try {
                if (isset($_POST['id'])) {
                    $stmt = $pdo->prepare("UPDATE obat SET nama_obat = :nama_obat, kemasan = :kemasan, harga = :harga WHERE id = :id");
                    $stmt->bindParam(':nama_obat', $_POST['nama_obat']);
                    $stmt->bindParam(':kemasan', $_POST['kemasan']);
                    $stmt->bindParam(':harga', $_POST['harga']);
                    $stmt->bindParam(':id', $_POST['id']);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO obat (nama_obat, kemasan, harga) VALUES (:nama_obat, :kemasan, :harga)");
                    $stmt->bindParam(':nama_obat', $_POST['nama_obat']);
                    $stmt->bindParam(':kemasan', $_POST['kemasan']);
                    $stmt->bindParam(':harga', $_POST['harga']);
                }
                $stmt->execute();
                echo "<meta http-equiv='refresh' content='0; url=index.php?page=obat'>";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        if (isset($_GET['aksi']) && $_GET['aksi'] === 'hapus') {
            try {
                $stmt = $pdo->prepare("DELETE FROM obat WHERE id = :id");
                $stmt->bindParam(':id', $_GET['id']);
                $stmt->execute();
                echo "<meta http-equiv='refresh' content='0; url=index.php?page=obat'>";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include '../../../layouts/index.php';
