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

$idAdmin = isset($_GET['id_admin']) ? $_GET['id_admin'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameBaru = $_POST['username'];
    $passwordBaru = $_POST['password'];
    $namaBaru = $_POST['nama'];
  
    $stmt = $conn->prepare("UPDATE user_admin SET username = ?, password = ?, nama_admin = ?, status_aktif = TRUE WHERE id_admin = ?");
    $stmt->bind_param("ssss", $usernameBaru, $passwordBaru, $namaBaru, $idAdmin);
    if ($stmt->execute()) {
        header("Location: menu_admin.php");
        exit();
    } 
    else {
        header("Location: " . $_SERVER['PHP_SELF'] . "?id_admin=" . $idAdmin);
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

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card" style="width: 40rem;">
        <h5 class="card-header bg-dark text-white text-center py-3 fs-2">EDIT ADMIN</h5>
        <div class="card-body">
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username baru" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru" required>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Admin</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama baru" required>
                </div>
                <div class="pt-2">
                    <button type="submit" class="btn btn-dark w-100">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>