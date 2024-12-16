<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db_connection.php');

$searchTerm = '';
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['search'])) {
    $searchTerm = strtoupper($_POST['search']);
    header("Location: ?search=" . urlencode($searchTerm));
    exit();
}

if (isset($_GET['search'])) {
    $searchTerm = strtoupper($_GET['search']);
    $query_all = "SELECT * FROM detail_log WHERE no_resi LIKE ? ORDER BY tanggal";
    $stmt = $conn->prepare($query_all);
    $likeTerm = "%" . $searchTerm . "%";
    $stmt->bind_param("s", $likeTerm);
    $stmt->execute();
    $result_all = $stmt->get_result();
} else {
    $query_all = "SELECT * FROM detail_log ORDER BY tanggal";
    $result_all = $conn->query($query_all);
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    
  <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">WELCOME!</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="login.php">Login admin</a>
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
              <h2 class="pb-4">Cek Pengiriman</h2>
              <form method="POST">
                <div class="input-group">
                  <input type="text" class="form-control" name="search" placeholder="Nomor Pengiriman" value="<?php echo htmlspecialchars($searchTerm); ?>">
                  <button class="btn btn-dark" type="submit">Lihat</button>
                </div>
              </form>
            </div>
          </div>

          <div class="ps-2 pt-4">
            <table class="table table-bordered mt-2">
              <thead>
                  <tr class="table-dark">
                      <th scope="col" style="width: 6%;">Tanggal</th>
                      <th scope="col">Kota</th>
                      <th scope="col">Keterangan</th>
                  </tr>
              </thead>
              <tbody>
                <?php while ($hasil = $result_all->fetch_assoc()): ?>
                  <?php $hasil['tanggal'] = date("d/m/Y", strtotime($hasil['tanggal'])); ?>
                  <tr>
                    <td class="align-middle"><?php echo $hasil['tanggal']; ?></td>
                    <td class="align-middle"><?php echo $hasil['kota']; ?></td>
                    <td class="align-middle"><?php echo $hasil['keterangan']; ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>