<?php
  session_start();
  require 'koneksi.php';

  if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $password = md5($_POST['password']);

    
    $sql = "SELECT u.nim_mahasiswa, u.nama, u.level
            FROM user u 
            LEFT JOIN mahasiswa m ON u.nama = m.nama LEFT JOIN dosen d ON u.nama = d.nama
            WHERE u.nama='$nama' AND u.password ='$password'";
    $result = $db->query($sql);

    
    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();

        $_SESSION['login'] = true;
        $_SESSION['nim_mahasiswa'] = $data['nim_mahasiswa'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['level'] = $data['level'];
        

        // Cek level pengguna
        if ($data['level'] == 'admin') {
            
            header("Location: index.php");
            exit;
        } else {
            
            header("Location: index.php");
            exit;
        }
    } else {
        echo "<script>alert('Mohon periksa kembali Username & Password Anda'); location.href='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login</title>
    <link rel="stylesheet" href="Design.css">
</head>
<body>
     <h2>Login Here</h2>
      <form action="" method="post">
      <div class="container-al46">
      <label for="nama"><b>Username</b></label>
      <input type="text" placeholder="" name="nama" required>

      <label for="psw"><b>Password</b><label>
      <input type="password" placeholder=""  name="password">

      <input type="checkbox" checked="checked"><span>Remember me</span>
      <button type="submit" class="btn btn-primary" name="submit" value="login">Login</button>
      

      <span class="fpw"><a href="#">Forgot password?</a></span>
</div>
</from>
</body>
</html>
