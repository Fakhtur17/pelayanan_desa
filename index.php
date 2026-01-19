<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="assets/img/mini-logo.png">

  <title>e-SuratDesa</title>

  <link rel="stylesheet" href="assets/fontawesome-5.10.2/css/all.css">
  <link rel="stylesheet" href="assets/bootstrap-4.3.1/dist/css/bootstrap.min.css">

  <style>
    body{
      background:url('assets/img/background.jpg');
      min-height: 100vh;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      background-attachment: fixed;
      margin: 0;
    }

    /* biar menu collapse kelihatan di desktop (kamu punya sebelumnya) */
    .nav-collapse-box{
      background: rgba(0,0,0,.45);
      border: 1px solid rgba(255,255,255,.15);
      border-radius: 14px;
      padding: 10px 12px;
      backdrop-filter: blur(6px);
    }

    .navbar .nav-link{
      font-weight: 600;
      letter-spacing: .3px;
    }

    .brand-logo{
      height: 34px;
      width: auto;
    }

    /* ======= PENTING: di MOBILE jangan pakai collapse ke bawah ======= */
    @media (max-width: 991.98px){
      .navbar-collapse{
        display: none !important; /* sembunyikan menu jatuh ke bawah */
      }
    }

    /* ======= DRAWER MENU (MOBILE) ======= */
    .drawer-overlay{
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.55);
      opacity: 0;
      pointer-events: none;
      transition: .25s ease;
      z-index: 998;
    }
    .drawer-overlay.show{
      opacity: 1;
      pointer-events: auto;
    }

    .mobile-drawer{
      position: fixed;
      top: 0;
      right: -320px;
      width: 290px;
      height: 100vh;
      background: rgba(18,18,28,.92);
      border-left: 1px solid rgba(255,255,255,.14);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      box-shadow: -14px 0 40px rgba(0,0,0,.45);
      transition: .32s ease;
      z-index: 999;
      padding: 18px;
    }
    .mobile-drawer.show{
      right: 0;
    }

    .drawer-header{
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
    }

    .drawer-close{
      background: rgba(255,255,255,.08);
      border: 1px solid rgba(255,255,255,.18);
      color: #fff;
      width: 42px;
      height: 42px;
      border-radius: 14px;
      font-size: 28px;
      cursor: pointer;
      outline: none;
    }

    .drawer-menu a{
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 14px;
      border-radius: 14px;
      color: #fff;
      text-decoration: none;
      font-weight: 700;
      letter-spacing: .2px;
      margin-bottom: 8px;
      transition: .2s ease;
    }
    .drawer-menu a:hover{
      background: rgba(255,255,255,.12);
      text-decoration: none;
      color: #fff;
    }

    .drawer-divider{
      height: 1px;
      background: rgba(255,255,255,.18);
      margin: 14px 0;
    }

    .drawer-btn{
      background: rgba(255,255,255,.10);
      border: 1px solid rgba(255,255,255,.18);
      justify-content: center;
    }

    /* desktop: drawer dimatikan */
    @media (min-width: 992px){
      .drawer-overlay,
      .mobile-drawer{
        display: none;
      }
    }
  </style>
</head>

<body>
<?php
  session_start();
?>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
    <a class="navbar-brand ml-4 mt-1" href="./">
      <img src="assets/img/e-SuratDesa.png" class="brand-logo" alt="e-SuratDesa">
    </a>

    <!-- tombol titik tiga (mobile -> buka drawer) -->
    <button class="navbar-toggler mr-4 mt-3" type="button"
            onclick="openDrawer()"
            aria-label="Menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- MENU DESKTOP (tetap seperti punya kamu) -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarTogglerDemo02">
      <ul class="navbar-nav ml-auto mt-lg-3 mr-4 text-right nav-collapse-box">

        <li class="nav-item active">
          <a class="nav-link" href="./">
            <i class="fas fa-home"></i>&nbsp;HOME
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="surat/">
            <i class="fas fa-envelope"></i>&nbsp;BUAT SURAT
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="tentang/">
            <i class="fas fa-info-circle"></i>&nbsp;TENTANG <b>e-SuratDesa</b>
          </a>
        </li>

        <li class="nav-item ml-lg-4 mt-2 mt-lg-0">
          <?php
            if(empty($_SESSION['username'])){
              echo '<a class="btn btn-dark" href="login/"><i class="fas fa-sign-in-alt"></i>&nbsp;LOGIN</a>';
            }else if(isset($_SESSION['lvl'])){
              echo '<a class="btn btn-transparent text-light" href="admin/"><i class="fa fa-user-cog"></i> '.$_SESSION['lvl'].'</a> ';
              echo '<a class="btn btn-transparent text-light" href="login/logout.php"><i class="fas fa-power-off"></i></a>';
            }
          ?>
        </li>

      </ul>
    </div>
  </nav>
  <!-- /NAVBAR -->

  <!-- DRAWER MENU (MOBILE) -->
  <div id="drawerOverlay" class="drawer-overlay" onclick="closeDrawer()"></div>

  <div id="mobileDrawer" class="mobile-drawer">
    <div class="drawer-header">
      <img src="assets/img/e-SuratDesa.png" alt="e-SuratDesa" style="height:28px;">
      <button class="drawer-close" onclick="closeDrawer()" aria-label="Tutup">&times;</button>
    </div>

    <div class="drawer-menu">
      <a href="./"><i class="fas fa-home"></i> Home</a>
      <a href="surat/"><i class="fas fa-envelope"></i> Buat Surat</a>
      <a href="tentang/"><i class="fas fa-info-circle"></i> Tentang</a>

      <div class="drawer-divider"></div>

      <?php
        if(empty($_SESSION['username'])){
          echo '<a class="drawer-btn" href="login/"><i class="fas fa-sign-in-alt"></i> Login</a>';
        }else if(isset($_SESSION['lvl'])){
          echo '<a class="drawer-btn" href="admin/"><i class="fa fa-user-cog"></i> '.$_SESSION['lvl'].'</a>';
          echo '<a class="drawer-btn" href="login/logout.php"><i class="fas fa-power-off"></i> Logout</a>';
        }
      ?>
    </div>
  </div>
  <!-- /DRAWER -->

  <!-- HERO / KONTEN -->
  <div class="container" style="padding-top:50px; padding-bottom:120px" align="center">
    <img src="assets/img/logo_banjarnegara.png" alt="Logo Banjarnegara">
    <hr>

    <div class="text-light" style="font-size:18pt">
      <strong>WEB APLIKASI PELAYANAN SURAT ADMINISTRASI DESA</strong>
    </div>

    <?php
      include('config/koneksi.php');
      $qTampilDesa = mysqli_query($connect, "SELECT * FROM profil_desa WHERE id_profil_desa = '1'");
      foreach($qTampilDesa as $row){
    ?>
      <div class="text-light" style="font-size:15pt; text-transform: uppercase;">
        <strong>DESA <?php echo $row['nama_desa']; ?></strong>
      </div>
      <div class="text-light" style="font-size:15pt; text-transform: uppercase;">
        <strong><?php echo $row['kota']; ?></strong>
      </div>
      <hr>
    <?php } ?>

    <a href="surat/" class="btn btn-outline-light" style="font-size:15pt">
      <i class="fas fa-envelope"></i> BUAT SURAT
    </a>
  </div>

  <!-- FOOTER -->
  <div class="footer bg-transparent text-center mb-3">
    <span class="text-light">
      <strong>&copy; <a href="./" class="text-decoration-none text-white">e-SuratDesa</a>.</strong>
    </span>
  </div>

  <!-- JS DRAWER -->
  <script>
    function openDrawer(){
      document.getElementById('mobileDrawer').classList.add('show');
      document.getElementById('drawerOverlay').classList.add('show');
    }
    function closeDrawer(){
      document.getElementById('mobileDrawer').classList.remove('show');
      document.getElementById('drawerOverlay').classList.remove('show');
    }
    document.addEventListener('keydown', function(e){
      if(e.key === 'Escape') closeDrawer();
    });
  </script>

  <!-- JS BOOTSTRAP (boleh tetap ada) -->
  <script src="assets/AdminLTE/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="assets/bootstrap-4.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
