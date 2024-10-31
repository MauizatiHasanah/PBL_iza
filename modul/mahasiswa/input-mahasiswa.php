<?php
    include '../../koneksi.php';
    session_start();

    if (!isset($_SESSION['login'])) {
        header("Location: ../../login.php");
        exit;
    }

    var_dump($_POST);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nim = $_POST['nim'];
        $nama = $_POST['nama'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $tgl_lahir = $_POST['tgl_lahir'];
        $kelas = $_POST['id_kelas'];
        $prodi = $_POST['prodi'];
        $alamat = $_POST['alamat'];
        $password = md5('12345'); // Enkripsi password dengan md5

        // Validasi NIM unik
        $result = mysqli_query($db, "SELECT * FROM mahasiswa WHERE nim='$nim'");
        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('NIM sudah ada. Mohon Inputkan nim yang berbeda'); location.href='input-mahasiswa.php';</script>";
            exit; // Hentikan eksekusi skrip jika NIM sudah digunakan
        }

        // Mulai transaksi
        $db->begin_transaction();

        // Query pertama
        $query = "INSERT INTO mahasiswa(nim, nama, jenis_kelamin, tgl_lahir, prodi, id_kelas, alamat) VALUES ('$nim','$nama','$jenis_kelamin','$tgl_lahir','$prodi','$kelas','$alamat')";

        if ($db->query($query) === TRUE) {
            echo "Data berhasil disimpan";
        } else {
            echo "Data mahasiswa gagal disimpan : " . $db->error;
            $db->rollback(); // Rollback transaksi jika query pertama gagal
        }

        // Query kedua
        $akun = "INSERT INTO user(nim_mahasiswa,nama, password, level) VALUES ('$nim','$nama','$password','user')";

        if ($db->query($akun) === TRUE) {
            echo "Data akun berhasil disimpan";
            $db->commit(); // Commit transaksi jika keduanya berhasil
        } else {
            echo "Data akun gagal disimpan : " . $db->error;
            $db->rollback(); // Rollback transaksi jika query kedua gagal
        }
    }
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama</title>


    <!-- Favicons -->
  <link href="../assets/img/Logo Ti.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
  
  
</head>


<body>
    
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

<div class="d-flex align-items-center justify-content-between">
  <a href="#" class="logo d-flex align-items-center">
    <img src="gambar2.png" alt="">
    <span class="d-none d-lg-block">Tekinfo</span>
  </a>
  <i class="bi bi-list toggle-sidebar-btn"></i>
</div><!-- End Logo -->

<div class="search-bar">
  <form class="search-form d-flex align-items-center" method="POST" action="#">
    <input type="text" name="query" placeholder="Search" title="Enter search keyword">
    <button type="submit" title="Search"><i class="bi bi-search"></i></button>
  </form>
</div><!-- End Search Bar -->

<nav class="header-nav ms-auto">
  <ul class="d-flex align-items-center">

    <li class="nav-item d-block d-lg-none">
      <a class="nav-link nav-icon search-bar-toggle " href="#">
        <i class="bi bi-search"></i>
      </a>
    </li><!-- End Search Icon-->

    <li class="nav-item dropdown">

      <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-bell"></i>
        <span class="badge bg-primary badge-number"></span>
      </a><!-- End Notification Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
        <li class="dropdown-header">
          Kamu tidak memiliki notifikasi 
          <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">Lihat semua</span></a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

      </ul><!-- End Notification Dropdown Items -->

    </li><!-- End Notification Nav -->

    <li class="nav-item dropdown">

      <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-chat-left-text"></i>
        <span class="badge bg-success badge-number"></span>
      </a><!-- End Messages Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
        <li class="dropdown-header">
          Kamu belum memiliki pesan
          <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">Lihat semua</span></a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        

      </ul><!-- End Messages Dropdown Items -->

    </li><!-- End Messages Nav -->

    <li class="nav-item dropdown pe-3">

      <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <img src="../assets/img/icon.jpg" alt="Profile" class="rounded-circle">
        <span class="d-none d-md-block dropdown-toggle ps-2"><?=   $_SESSION['nama']?></span>
      </a><!-- End Profile Iamge Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
          <h6><?=   $_SESSION['nama']?></h6>
          <span><?=   $_SESSION['level']?></span>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="../../profil.php">
            <i class="bi bi-person"></i>
            <span>Profil saya</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="#">
            <i class="bi bi-gear"></i>
            <span>Pengaturan Akun</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="#">
            <i class="bi bi-question-circle"></i>
            <span>Butuh bantuan?</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="../../logout.php">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign Out</span>
          </a>
        </li>

      </ul><!-- End Profile Dropdown Items -->
    </li><!-- End Profile Nav -->

  </ul>
</nav><!-- End Icons Navigation -->

</header><!-- End Header -->

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

  <li class="nav-item">
    <a class="nav-link collapsed" href="../../index.php">
      <i class="bi bi-grid"></i>
      <span>Dashboard</span>
    </a>
  </li><!-- End Dashboard Nav -->
  <?php
      if ($_SESSION['level'] == 'admin') {
      ?>  
  <li class="nav-item">
    <a class="nav-link " data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-menu-button-wide"></i><span>Mahasiswa</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="components-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
      <li>
        <a href="data-mahasiswa.php" >
          <i class="bi bi-circle"></i><span>Data Mahasiswa</span>
        </a>
      </li>
      <li>
        <a href="#" class="active" class="active">
          <i class="bi bi-circle"></i><span>Input Data</span>
        </a>
      </li>
      <?php
      } elseif ($_SESSION['level'] == 'user') { 
      ?>
      <!-- Jika pengguna adalah user, tampilkan item menu berikut -->
      <li>
        <a href="modul/absensi/halaman-presensi.php">
          <i class="bi bi-circle"></i><span>Presensi</span>
        </a>
      </li>
    <?php
      } // akhir if
    ?>
    </ul>
  </li><!-- End Mahasiswa Nav -->

  <li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-journal-text"></i><span>Dosen</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
      <li>
        <a href="../dosen/data-dosen.php">
          <i class="bi bi-circle"></i><span>Data Dosen</span>
        </a>
      </li>
      <?php
      if ($_SESSION['level']=='admin'){
      ?>
      <li>
        <a href="../dosen/input-dosen.php">
          <i class="bi bi-circle"></i><span>Input Data</span>
        </a>
      </li>
      <?php } ?>
    </ul>
  </li><!-- End Dosen Nav -->

  <li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-layout-text-window-reverse"></i><span>Mata Kuliah</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
      <li>
        <a href="../mata_kuliah/mata-kuliah.php">
          <i class="bi bi-circle"></i><span>Jadwal Mata Kuliah</span>
        </a>
      </li>
      <?php
      if ($_SESSION['level']=='admin'){
      ?>
      <li>
        <a href="../mata_kuliah/input-matakuliah.php">
          <i class="bi bi-circle"></i><span>Input mata kuliah</span>
        </a>
      </li>
      <?php } ?>
    </ul>
  </li><!-- End Mata Kuliah Nav -->

  <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Laporan Kehadiran</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="../kehadiran/catatan-harian.php">
              <i class="bi bi-circle"></i><span>Catatan Harian</span>
            </a>
          </li>
          <li>
            <a href="../kehadiran/kompensasi.php">
              <i class="bi bi-circle"></i><span>Kompensasi</span>
            </a>
          </li>
        </ul>
      </li><!-- End Laporan kehadrian Nav -->

  <li class="nav-heading">Pages</li>

  <li class="nav-item">
    <a class="nav-link collapsed" href="../../profil.php">
      <i class="bi bi-person"></i>
      <span>Profil</span>
    </a>
  </li><!-- End Profile Page Nav -->


  <li class="nav-item">
    <a class="nav-link collapsed" href="#">
      <i class="bi bi-envelope"></i>
      <span>Kontak</span>
    </a>
  </li><!-- End Contact Page Nav -->

  <li class="nav-item">
    <a class="nav-link collapsed" href="../../buat-akun.php">
      <i class="bi bi-card-list"></i>
      <span>Register</span>
    </a>
  </li><!-- End Register Page Nav -->

  <li class="nav-item">
    <a class="nav-link collapsed" href="../../login.php">
      <i class="bi bi-box-arrow-in-right"></i>
      <span>Login</span>
    </a>
  </li><!-- End Login Page Nav -->


</ul>

</aside><!-- End Sidebar-->

<main id="main" class="main">

    <div class="pagetitle">
      <h1 text-align="center">Input data mahasiswa</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
          <li class="breadcrumb-item ">Mahasiswa</li>
          <li class="breadcrumb-item active">Input Data</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

      <div class="card">
            <div class="card-body">
              <h5 class="card-title">Identitas Mahasiswa</h5>

              <!-- General Form Elements -->
              <form action="" method="post">
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label" >Nama</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="nama" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label" >NIM</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control" name="nim" required>
                  </div>
                </div>
                

                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Jenis Kelamin</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki-laki" value="L" checked>
                      <label class="form-check-label" for="gridRadios1">
                        Laki-Laki
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="P">
                      <label class="form-check-label" for="gridRadios2">
                        Perempuan
                      </label>
                    </div>
                  </div>
                </fieldset>
                

                <div class="row mb-3">
                  <label for="inputDate" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                  <div class="col-sm-10">
                    <input type="date" class="form-control" name="tgl_lahir" required>
                  </div>
                </div>


                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Prodi</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="prodi" id="trpl" value="TRPL" required>
                      <label class="form-check-label" for="gridRadios1">
                        Teknik Rekayasa Perangkat Lunak
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="prodi" id="mi" value="MI" required>
                      <label class="form-check-label" for="gridRadios2">
                        Manajemen Informatika
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="prodi" id="tkom" value="TKOM" required>
                      <label class="form-check-label" for="gridRadios2">
                        Teknik Komputer
                      </label>
                    </div>
                  </div>
                </fieldset>

                
                <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Kelas</label>
                        <div class="col-sm-3">
                        <select name="id_kelas" class="form-select">
                            <option value="">--Pilih Kelas--</option>
                            <?php
                                $kelas = mysqli_query($db, "SELECT * FROM kelas");
                                while ($data_kelas = mysqli_fetch_array($kelas)) {
                                    echo "<option value='" . $data_kelas['id'] . "'>" . $data_kelas['nama_kelas'] . "</option>";
                                }
                            ?>

                        </select>
                        </div>
                    </div>


                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Alamat</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" style="height: 200px" name="alamat"></textarea>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="text-center">
                    <input type="submit" name="submit" class="btn btn-primary">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>    
        </div>
  </section>

</main>

<!-- --------------------- -->

    <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>
  <link href="style.css" rel="stylesheet">
</body>

</html>