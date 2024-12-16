<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db_connection.php');

$is_logged_in = isset($_SESSION['username']) && !empty($_SESSION['username']);

if($is_logged_in){ 
    $username = $_SESSION['nama_admin'];
}else{
    header("Location: index.php" );
    exit();
}

$noResi = isset($_GET['no_resi']) ? $_GET['no_resi'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addLog'])) {
    $tanggalLog = $_POST['tanggalLog'];
    $kotaLog = ucwords(strtolower($_POST['kotaLog']));
    $keteranganLog = $_POST['keteranganLog'];
  
    $stmt = $conn->prepare("INSERT INTO detail_log (no_resi, tanggal, kota, keterangan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $noResi, $tanggalLog, $kotaLog, $keteranganLog);
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?no_resi=" . $noResi);
        exit();
    } 
    else {
        header("Location: " . $_SERVER['PHP_SELF'] . "?no_resi=" . $noResi);
        exit();
    }
  }

$resi = $conn->prepare("SELECT * FROM detail_log WHERE no_resi = ? ORDER BY tanggal");
$resi->bind_param("s", $noResi);
$resi->execute();
$result = $resi->get_result();
$semuaLog = [];
while ($row = $result->fetch_assoc()) {
    $row['tanggal'] = date("d/m/Y", strtotime($row['tanggal']));
    $semuaLog[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapusLog'])) {
    $delete = $_POST['hapusLog'];
    $stmt = $conn->prepare("DELETE FROM detail_log WHERE id_detail = ?");
    $stmt->bind_param("i", $delete);
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?no_resi=" . $noResi);
        exit();
    } 
    else {
        header("Location: " . $_SERVER['PHP_SELF'] . "?no_resi=" . $noResi);
        exit();
    }
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List Entry Log - <?php echo $noResi ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    
  <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Halo, <?php echo $username; ?></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="index_admin.php">Data Resi Pengiriman</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="menu_admin.php">User Admin</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
              </li>
            </ul>
          </div>
        </div>
    </nav>

    <div class="container-fluid pt-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex">
            <div class="ps-2">
              <h2>Entry Log <?php echo $noResi ?></h2>
              <form action="#" method="POST">
                <div class="mb-1">
                  <label for="tanggalLog" class="form-label">Tanggal</label>
                  <input type="date" class="form-control" id="tanggalLog" name="tanggalLog" required>
                </div>
                <div class="mb-1">
                  <label for="kotaLog" class="form-label">Kota</label>
                  <input type="text" class="form-control" id="kotaLog" name="kotaLog" required>
                </div>
                <div class="mb-3">
                  <label for="keteranganLog" class="form-label">Keterangan</label>
                  <textarea type="text" class="form-control" id="keteranganLog" name="keteranganLog" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                  <button type = "submit" name="addLog" class="btn btn-dark text-white w-100">Entry</button>
                </div>
              </form>
            </div>
          </div>

          <div class="ps-2">
            <table class="table table-bordered mt-2">
              <thead>
                  <tr class="table-dark">
                      <th scope="col" style="width: 6%;">Tanggal</th>
                      <th scope="col">Kota</th>
                      <th scope="col">Keterangan</th>
                      <th scope="col">Action</th>
                  </tr>
              </thead>
              <tbody>
                <?php foreach ($semuaLog as $hasil): ?>
                  <tr>
                    <td class="align-middle"><?php echo $hasil['tanggal']; ?></td>
                    <td class="align-middle"><?php echo $hasil['kota']; ?></td>
                    <td class="align-middle"><?php echo $hasil['keterangan']; ?></td>
                    <td class="align-middle">
                      <form action="#" method="POST">
                        <button type="submit" class="btn btn-danger" name="hapusLog" value="<?php echo $hasil['id_detail']; ?>">Hapus</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>  













    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>