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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addResi'])) {
  $tanggalResi = $_POST['tanggalResi'];
  $nomorResi = strtoupper($_POST['nomorResi']);

  $cekStmt = $conn->prepare("SELECT no_resi FROM transaksi_resi WHERE no_resi = ?");
  $cekStmt->bind_param("s", $nomorResi);
  $cekStmt->execute();
  $cekStmt->store_result();

  if ($cekStmt->num_rows > 0) {
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  } 
  else {
    $stmt = $conn->prepare("INSERT INTO transaksi_resi (no_resi, tanggal_resi) VALUES (?, ?)");
    $stmt->bind_param("ss", $nomorResi, $tanggalResi);
    if ($stmt->execute()) {
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    } 
    else {
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    }
  }
}

$resi = $conn->query("SELECT * FROM transaksi_resi ORDER BY tanggal_resi");
$semuaResi = [];
while ($row = $resi->fetch_assoc()) {
  $row['tanggal_resi'] = date("d/m/Y", strtotime($row['tanggal_resi']));
  $semuaResi[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapusResi'])) {
  $delete = $_POST['hapusResi'];
  $stmt = $conn->prepare("DELETE FROM transaksi_resi WHERE no_resi = ?");
  $stmt->bind_param("s", $delete);
  if ($stmt->execute()) {
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  } 
  else {
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  }
  
}


?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resi Pengiriman</title>
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
              <h2>Entry Nomor Resi</h2>
              <form action="#" method="POST">
                <div class="mb-1">
                  <label for="tanggalResi" class="form-label">Tanggal</label>
                  <input type="date" class="form-control" id="tanggalResi" name="tanggalResi" required>
                </div>
                <div class="mb-3">
                  <label for="nomorResi" class="form-label">Nomor Resi</label>
                  <input type="text" class="form-control" id="nomorResi" name="nomorResi" required>
                </div>
                <div class="mb-3">
                  <button type = "submit" name="addResi" class="btn btn-dark text-white w-100">Entry</button>
                </div>
              </form>
            </div>
          </div>

          <div class="ps-2">
            <table class="table table-bordered mt-2">
              <thead>
                  <tr class="table-dark">
                      <th scope="col" style="width: 6%;">Tanggal Resi</th>
                      <th scope="col">Nomor Resi</th>
                      <th scope="col">Action</th>
                  </tr>
              </thead>
              <tbody>
                <?php foreach ($semuaResi as $hasil): ?>
                  <tr>
                    <td class="align-middle"><?php echo $hasil['tanggal_resi']; ?></td>
                    <td class="align-middle"><?php echo $hasil['no_resi']; ?></td>
                    <td class="align-middle">
                      <form action="#" method="POST">
                          <a href="detail_log.php?no_resi=<?php echo $hasil['no_resi']; ?>" class="btn btn-primary">Entry Log</a>
                          <button type="submit" class="btn btn-danger" name="hapusResi" value="<?php echo $hasil['no_resi']; ?>">Hapus</button>
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