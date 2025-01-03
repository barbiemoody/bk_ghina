<?php
include_once("../../../config/conn.php");
session_start();

if (isset($_SESSION['login'])) {
  $_SESSION['login'] = true;
} else {
  echo "<meta http-equiv='refresh' content='0; url=../auth/login.php'>";
  die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

if ($akses != 'admin') {
  echo "<meta http-equiv='refresh' content='0; url=../..'>";
  die();
}
?>
<?php
$title = 'Poliklinik | Obat';
// Breadcrumb section
ob_start();?>
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="<?= $base_admin; ?>">Home</a></li>
  <li class="breadcrumb-item active">Obat</li>
</ol>
<?php
$breadcrumb = ob_get_clean();
ob_flush();

// Title Section
ob_start();?>
Tambah / Edit Pasien
<?php
$main_title = ob_get_clean();
ob_flush();


// Content section
ob_start();

?>
<form class="form col" method="POST" action="" required name="myForm" onsubmit="return(validate());">
<?php
    $nama = '';
    $alamat = '';
    $no_hp = '';
    $no_ktp = '';
    $no_rm = '';
    if (isset($_GET['id'])) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM pasien WHERE id = :id");
            $stmt->bindParam(':id', $_GET['id']);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $nama = $row['nama'];
                $alamat = $row['alamat'];
                $no_hp = $row['no_hp'];
                $no_ktp = $row['no_ktp'];
                $no_rm = $row['no_rm'];
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    ?>
        <input type="hidden" required name="id" value="<?php echo $_GET['id'] ?>">

    <?php
    }

    if (isset($_GET['id'])) {
    // If an 'id' is provided (editing an existing record), you can use the existing no_rm.
    $no_rm = $no_rm;
} else {
    // If adding a new record, generate a new 'no_rm'.
    
    // Get the current month in 'YYYYMM' format
    $tahun_bulan = date("Ym");
    
    // Query to find the existing numbers for the current month
    $query_existing_numbers = "SELECT no_rm FROM pasien WHERE no_rm LIKE :tahun_bulan ORDER BY CAST(SUBSTRING(no_rm, 8) AS UNSIGNED)";
    
    $stmt = $pdo->prepare($query_existing_numbers);
    $stmt->execute([':tahun_bulan' => $tahun_bulan . '%']);
    
    // Fetch all the existing 'no_rm' for the current month
    $existing_numbers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // If no records for the current month, start with '001'
    $newQueueNumber = 1;
    
    // If there are existing records, find the next available sequential number
    if (count($existing_numbers) > 0) {
        // Get the last used number (in numeric part) from the sorted records
        $last_no_rm = $existing_numbers[count($existing_numbers) - 1]['no_rm'];
        
        // Extract the numeric part and increment it
        $last_number = (int) substr($last_no_rm, 7); // Extract number after the hyphen
        $newQueueNumber = $last_number + 1;
    }
    
    // Format the new queue number as 3 digits (e.g., 001, 002, 003)
    $no_rm = $tahun_bulan . "-" . str_pad($newQueueNumber, 3, '0', STR_PAD_LEFT);
}
    // Jika sedang dalam mode ubah, isi Nomor RM sesuai data yang diubah
    /*if (isset($_GET['id'])) {
        $no_rm = $no_rm;
    } else {
        // Jika menambahkan data baru, hitung Nomor RM sesuai format
        $tahun_bulan = date("Ym");
        $query_last_id = "SELECT MAX(CAST(SUBSTRING(no_rm, 8) AS SIGNED)) as last_queue_number FROM pasien";
        $result_last_id = $pdo->query($query_last_id);
        $row_last_id = $result_last_id->fetch(PDO::FETCH_ASSOC);
        $last_inserted_id = $row_last_id['last_queue_number'] ? $row_last_id['last_queue_number'] : 0;
        $newQueueNumber = $last_inserted_id + 1;
        $no_rm = $tahun_bulan . "-" . str_pad($newQueueNumber, 3, '0', STR_PAD_LEFT);
       
    }*/
    ?>
        <div class="row mt-3">
            <label for="nama" class="form-label fw-bold">
                Nama Pasien
            </label>
            <input type="text" class="form-control" required name="nama" id="nama" placeholder="Nama Pasien" value="<?php echo $nama ?>">
        </div>
        <div class="row mt-3">
            <label for="alamat" class="form-label fw-bold">
                Alamat
            </label>
            <input type="text" class="form-control" required name="alamat" id="alamat" placeholder="Alamat" value="<?php echo $alamat ?>">
        </div>
        <div class="row mt-3">
            <label for="no_ktp" class="form-label fw-bold">
                Nomor KTP
            </label>
            <input type="number" class="form-control" required name="no_ktp" id="no_ktp" placeholder="Nomor KTP" value="<?php echo $no_ktp ?>">
        </div>

        <div class="row mt-3">
            <label for="no_hp" class="form-label fw-bold">
                Nomor Telpon
            </label>
            <input type="number" class="form-control" required name="no_hp" id="no_hp" placeholder="Nomor Telpon" value="<?php echo $no_hp ?>">
        </div>

        <div class="row mt-3">
            <label for="no_rm" class="form-label fw-bold">
                Nomor RM
            </label>
            <input type="text" class="form-control" required name="no_rm" id="no_rm" placeholder="Nomor RM" value="<?php echo $no_rm ?>" readonly>
        </div>


        <div class="row d-flex mt-3 mb-3">
          <button type="submit" class="btn btn-primary" style="width: 3cm;" required name="simpan">Simpan</button>
        </div>
</form>

<div class="row d-flex mt-3 mb-3">
  <a href="<?= $base_admin.'/Pasien' ?>">
    <button class="btn btn-secondary ml-2" style="width: 3cm;">Reset</button>
  </a>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Pasien</h3>
  </div>
  <div class="card-body">
    <table id="example1" class="table table-striped">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama</th>
          <th scope="col">Alamat</th>
          <th scope="col">No. KTP</th>
          <th scope="col">No. Telpon</th>
          <th scope="col">No. RM</th>
          <th scope="col">Kelola</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $pdo->query("SELECT * FROM pasien");
        $no = 1;
        while ($data = $result->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
              <td><?php echo $no++ ?></td>
              <td><?php echo $data['nama'] ?></td>
              <td><?php echo $data['alamat'] ?></td>
              <td><?php echo $data['no_ktp'] ?></td>
              <td><?php echo $data['no_hp'] ?></td>
              <td><?php echo $data['no_rm'] ?></td>
              <td>
                  <a class="btn btn-success" href="index.php?id=<?php echo $data['id'] ?>">Ubah</a>
                  <a class="btn btn-danger" href="index.php?id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
              </td>
          </tr>
      <?php
      }
      ?>
      </tbody>
    </table>
    <?php
      if (isset($_POST['simpan'])) {
        if (isset($_POST['id'])) {
            $stmt = $pdo->prepare("UPDATE pasien SET 
                                    nama = :nama,
                                    alamat = :alamat,
                                    no_ktp = :no_ktp,
                                    no_hp = :no_hp,
                                    no_rm = :no_rm
                                    WHERE
                                    id = :id");

            $stmt->bindParam(':nama', $_POST['nama'], PDO::PARAM_STR);
            $stmt->bindParam(':alamat', $_POST['alamat'], PDO::PARAM_STR);
            $stmt->bindParam(':no_ktp', $_POST['no_ktp'], PDO::PARAM_INT);
            $stmt->bindParam(':no_hp', $_POST['no_hp'], PDO::PARAM_INT);
            $stmt->bindParam(':no_rm', $_POST['no_rm'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
            $stmt->execute();

            header('Location:index.php');

        } 
        else {
            $stmt = $pdo->prepare("INSERT INTO pasien(nama, alamat, no_ktp, no_hp, no_rm) 
                                    VALUES (:nama, :alamat, :no_ktp, :no_hp, :no_rm)");

            $stmt->bindParam(':nama', $_POST['nama'], PDO::PARAM_STR);
            $stmt->bindParam(':alamat', $_POST['alamat'], PDO::PARAM_STR);
            $stmt->bindParam(':no_ktp', $_POST['no_ktp'], PDO::PARAM_INT);
            $stmt->bindParam(':no_hp', $_POST['no_hp'], PDO::PARAM_INT);
            $stmt->bindParam(':no_rm', $_POST['no_rm'], PDO::PARAM_STR);
            $stmt->execute();

            header('Location:index.php');
        }
    }
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
      // Step 1: Delete the patient record
      $stmt = $pdo->prepare("DELETE FROM pasien WHERE id = :id");
      $stmt->bindParam(':id', $_GET['id']);
      $stmt->execute();
  
      // Step 2: Reorder the remaining 'no_rm' values
      $tahun_bulan = date("Ym"); // Get the current year and month
      $query = "SELECT id, no_rm FROM pasien WHERE no_rm LIKE :tahun_bulan ORDER BY CAST(SUBSTRING(no_rm, 8) AS UNSIGNED)";
      $stmt = $pdo->prepare($query);
      $stmt->execute([':tahun_bulan' => $tahun_bulan . '%']);
      $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
      // Step 3: Recalculate 'no_rm' for all remaining patients
      $newQueueNumber = 1; // Start numbering from 001 for the current month
      foreach ($patients as $patient) {
          // Generate the new 'no_rm' value (format: YYYYMM-XXX)
          $new_no_rm = $tahun_bulan . '-' . str_pad($newQueueNumber, 3, '0', STR_PAD_LEFT);
  
          // Update the patient's 'no_rm' in the database
          $update_stmt = $pdo->prepare("UPDATE pasien SET no_rm = :no_rm WHERE id = :id");
          $update_stmt->bindParam(':no_rm', $new_no_rm, PDO::PARAM_STR);
          $update_stmt->bindParam(':id', $patient['id'], PDO::PARAM_INT);
          $update_stmt->execute();
  
          // Increment the queue number for the next patient
          $newQueueNumber++;
      }
  
      // Step 4: Redirect after deleting and updating no_rm
      header('Location:index.php');
  }
  
    ?>
  </div>
</div>
<?php
$content = ob_get_clean();
ob_flush();
?>

<?php include '../../../layouts/index.php'; ?>