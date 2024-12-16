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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addAdmin'])) {
  $usernameAdmin = $_POST['usernameAdmin'];
  $passwordAdmin = $_POST['passwordAdmin'];
  $namaAdmin = $_POST['namaAdmin'];

  $cekStmt = $conn->prepare("SELECT username FROM user_admin WHERE username = ?");
  $cekStmt->bind_param("s", $usernameAdmin);
  $cekStmt->execute();
  $cekStmt->store_result();

  if ($cekStmt->num_rows > 0) {
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  } 
  else {
    $stmt = $conn->prepare("INSERT INTO user_admin (username, password, nama_admin, status_aktif) VALUES (?, ?, ?, TRUE)");
    $stmt->bind_param("sss", $usernameAdmin, $passwordAdmin, $namaAdmin);
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

$admin = $conn->query("SELECT * FROM user_admin");
$semuaAdmin = [];
while ($row = $admin->fetch_assoc()) {
  $semuaAdmin[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['changeStatus'])) {
  $idAdmin = $_POST['current_id'];
  $currentStatus = $_POST['current_status'];
  $newStatus = $currentStatus == 1 ? 0 : 1;

  // Update status_aktif di database
  $stmt = $conn->prepare("UPDATE user_admin SET status_aktif = ? WHERE id_admin = ?");
  $stmt->bind_param("ii", $newStatus, $idAdmin);
  $stmt->execute();
  if ($stmt->execute()) {
    if( $_SESSION['id_admin'] == $idAdmin) {
      header("Location: logout.php");
      exit();
    }
    else {
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    }
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
    <title>Menu Admin</title>
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
              <h2>Entry Admin Baru</h2>
              <form action="#" method="POST">
                <div class="mb-1">
                  <label for="usernameAdmin" class="form-label">Username</label>
                  <input type="text" class="form-control" id="usernameAdmin" name="usernameAdmin" required>
                </div>
                <div class="mb-1">
                  <label for="passwordAdmin" class="form-label">Password</label>
                  <input type="password" class="form-control" id="passwordAdmin" name="passwordAdmin" required>
                </div>
                <div class="mb-3">
                  <label for="namaAdmin" class="form-label">Nama Admin</label>
                  <input type="text" class="form-control" id="namaAdmin" name="namaAdmin" required>
                </div>
                <div class="mb-3">
                  <button type = "submit" name="addAdmin" class="btn btn-dark text-white w-100">Entry</button>
                </div>
              </form>
            </div>
          </div>

          <div class="ps-2">
            <table class="table table-bordered mt-2">
              <thead>
                  <tr class="table-dark">
                      <th scope="col" style="width: 6%;">Username</th>
                      <th scope="col">Nama admin</th>
                      <th scope="col">Action</th>
                  </tr>
              </thead>
              <tbody>
                <?php foreach ($semuaAdmin as $hasil): ?>
                  <tr>
                    <td class="align-middle"><?php echo $hasil['username']; ?></td>
                    <td class="align-middle"><?php echo $hasil['nama_admin']; ?></td>
                    <td class="align-middle">
                      <form action="#" method="POST">
                        <a href="edit_admin.php?id_admin=<?php echo $hasil['id_admin']; ?>" class="btn btn-primary">Edit</a>
                        <input type="hidden" name="current_id" value="<?php echo $hasil['id_admin']; ?>">
                        <input type="hidden" name="current_status" value="<?php echo $hasil['status_aktif']; ?>">
                        <button type="submit" name="changeStatus" class="btn btn-<?php echo $hasil['status_aktif'] ? "danger" : "success"; ?>">
                            <?php echo $hasil['status_aktif'] ? "Nonaktifkan" : "Aktifkan"; ?>
                        </button>
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