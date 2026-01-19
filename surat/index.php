<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="../assets/img/mini-logo.png">
  <title>e-SuratDesa</title>

  <link rel="stylesheet" href="../assets/fontawesome-5.10.2/css/all.css">
  <link rel="stylesheet" href="../assets/bootstrap-4.3.1/dist/css/bootstrap.min.css">

  <style>
    /* ====== KONSISTEN CARD MENU SURAT ====== */
    .menu-surat-wrap { padding-top: 30px; padding-bottom: 60px; min-height: 100%; }

    .menu-surat-card {
      height: 100%;
      display: flex;
      flex-direction: column;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 6px 18px rgba(0,0,0,.08);
      border: 0;
      transition: transform .15s ease, box-shadow .15s ease;
    }

    .menu-surat-card:hover{
      transform: translateY(-3px);
      box-shadow: 0 10px 24px rgba(0,0,0,.12);
    }

    .menu-surat-card .card-img-top{
      height: 200px;
      object-fit: cover;
    }

    .menu-surat-card .card-body{
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 18px;
    }

    .menu-surat-title{
      min-height: 56px;
      font-size: 14.5px;
      font-weight: 700;
      letter-spacing: .3px;
      margin-bottom: 14px;

      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .menu-surat-btn{
      border-radius: 8px;
      font-weight: 600;
      padding: 10px 14px;
    }

    @media (max-width: 575.98px){
      .menu-surat-card .card-img-top{ height: 140px; }
      .menu-surat-title{ min-height: auto; -webkit-line-clamp: 3; }
    }

    /* ============================
       PERBAIKAN TITIK TIGA (DRAWER)
       ============================ */

    /* Mobile: matikan collapse menu bootstrap (biar ga turun ke bawah) */
    @media (max-width: 991.98px){
      #navbarTogglerDemo02{ display: none !important; }
    }

    /* Overlay gelap */
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

    /* Drawer panel */
    .mobile-drawer{
      position: fixed;
      top: 0;
      right: -320px;
      width: 290px;
      height: 100vh;
      background: rgba(20,20,30,.95);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-left: 1px solid rgba(255,255,255,.15);
      box-shadow: -12px 0 35px rgba(0,0,0,.45);
      padding: 18px;
      transition: .32s ease;
      z-index: 999;
    }
    .mobile-drawer.show{ right: 0; }

    .drawer-header{
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
    }

    .drawer-close{
      width: 42px;
      height: 42px;
      border-radius: 14px;
      background: rgba(255,255,255,.08);
      border: 1px solid rgba(255,255,255,.18);
      color: #fff;
      font-size: 28px;
      cursor: pointer;
      line-height: 0;
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
      color: #fff;
      text-decoration: none;
    }

    .drawer-divider{
      height: 1px;
      background: rgba(255,255,255,.18);
      margin: 14px 0;
    }

    .drawer-btn{
      background: rgba(255,255,255,.12);
      border: 1px solid rgba(255,255,255,.18);
      justify-content: center;
    }
    .img-surat{
    height: 140px;          /* UBAH INI kalau mau lebih kecil */
    object-fit: contain;   /* logo tidak kepotong */
    padding: 12px;
    background: #f8f9fa;   /* biar rapi */
  }

    /* Desktop: drawer dimatikan */
    @media (min-width: 992px){
      .drawer-overlay, .mobile-drawer{ display: none; }
    }
  </style>
</head>

<body class="bg-light">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-info">
    <a class="navbar-brand ml-4 mt-1" href="../">
      <img src="../assets/img/e-SuratDesa.png" alt="e-SuratDesa">
    </a>

    <!-- TOMBOL TITIK TIGA (MOBILE -> buka drawer) -->
    <button class="navbar-toggler mr-4 mt-3"
            type="button"
            onclick="openDrawer()"
            aria-label="Menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- MENU DESKTOP (TETAP NORMAL) -->
    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
      <ul class="navbar-nav ml-auto mt-lg-3 mr-5 position-relative text-right">
        <li class="nav-item">
          <a class="nav-link" href="../">HOME</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="#"><i class="fas fa-envelope"></i>&nbsp;BUAT SURAT</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../tentang/">TENTANG <b>e-SuratDesa</b></a>
        </li>
        <li class="nav-item active ml-5">
          <?php
            if(empty($_SESSION['username'])){
              echo '<a class="btn btn-light text-info" href="../login/"><i class="fas fa-sign-in-alt"></i>&nbsp;LOGIN</a>';
            }else if(isset($_SESSION["lvl"])){
              echo '<a class="btn btn-transparent text-light" href="../admin/"><i class="fa fa-user-cog"></i> '.$_SESSION["lvl"].'</a>';
              echo '<a class="btn btn-transparent text-light" href="../login/logout.php"><i class="fas fa-power-off"></i></a>';
            }
          ?>
        </li>
      </ul>
    </div>
  </nav>

  <!-- DRAWER MOBILE (MENU GESER SAMPING) -->
  <div id="drawerOverlay" class="drawer-overlay" onclick="closeDrawer()"></div>

  <div id="mobileDrawer" class="mobile-drawer">
    <div class="drawer-header">
      <img src="../assets/img/e-SuratDesa.png" alt="e-SuratDesa" style="height:26px;">
      <button class="drawer-close" onclick="closeDrawer()" aria-label="Tutup">&times;</button>
    </div>

    <div class="drawer-menu">
      <a href="../"><i class="fas fa-home"></i> Home</a>
      <a href="#"><i class="fas fa-envelope"></i> Buat Surat</a>
      <a href="../tentang/"><i class="fas fa-info-circle"></i> Tentang</a>

      <div class="drawer-divider"></div>

      <?php
        if(empty($_SESSION['username'])){
          echo '<a class="drawer-btn" href="../login/"><i class="fas fa-sign-in-alt"></i> Login</a>';
        }else if(isset($_SESSION["lvl"])){
          echo '<a class="drawer-btn" href="../admin/"><i class="fa fa-user-cog"></i> '.$_SESSION["lvl"].'</a>';
          echo '<a class="drawer-btn" href="../login/logout.php"><i class="fas fa-power-off"></i> Logout</a>';
        }
      ?>
    </div>
  </div>
  <!-- /DRAWER -->

  <!-- KONTEN -->
  <div class="container-fluid">
    <div class="menu-surat-wrap">
      <div>
        <?php
          if(isset($_GET['pesan'])){
            if($_GET['pesan']=="berhasil"){
              echo "<div class='alert alert-success'><center>Berhasil membuat surat. Silahkan ambil surat di Kantor Desa dalam 2-3 hari kerja!</center></div>";
            }
          }
        ?>
      </div>

      <div class="row">

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-4 d-flex">
          <div class="card menu-surat-card w-100">
            <img src="../assets/img/menu_surat.png" class="card-img-top" alt="Surat Keterangan">
            <div class="card-body text-center">
              <h5 class="menu-surat-title">SURAT KETERANGAN</h5>
              <a href="surat_keterangan/" class="btn btn-info menu-surat-btn">BUAT SURAT</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-4 d-flex">
          <div class="card menu-surat-card w-100">
            <img src="../assets/img/menu_surat.png" class="card-img-top" alt="Surat Akta Kelahiran">
            <div class="card-body text-center">
              <h5 class="menu-surat-title">SURAT AKTA KELAHIRAN</h5>
              <a href="surat_akta_kelahiran/" class="btn btn-info menu-surat-btn">BUAT SURAT</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-4 d-flex">
          <div class="card menu-surat-card w-100">
            <img src="../assets/img/menu_surat.png" class="card-img-top" alt="Surat Keterangan Domisili">
            <div class="card-body text-center">
              <h5 class="menu-surat-title">SURAT KETERANGAN DOMISILI</h5>
              <a href="surat_keterangan_domisili/" class="btn btn-info menu-surat-btn">BUAT SURAT</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-4 d-flex">
          <div class="card menu-surat-card w-100">
            <img src="../assets/img/menu_surat.png" class="card-img-top" alt="Surat Kepemilikan Kendaraan Bermotor">
            <div class="card-body text-center">
              <h5 class="menu-surat-title">SURAT KETERANGAN KEPEMILIKAN KENDARAAN BERMOTOR</h5>
              <a href="surat_keterangan_kepemilikan_kendaraan_bermotor/" class="btn btn-info menu-surat-btn">BUAT SURAT</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-4 d-flex">
          <div class="card menu-surat-card w-100">
            <img src="../assets/img/menu_surat.png" class="card-img-top" alt="Surat Keterangan Perhiasan">
            <div class="card-body text-center">
              <h5 class="menu-surat-title">SURAT KETERANGAN PERHIASAN</h5>
              <a href="surat_keterangan_perhiasan/" class="btn btn-info menu-surat-btn">BUAT SURAT</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-4 d-flex">
          <div class="card menu-surat-card w-100">
            <img src="../assets/img/menu_surat.png" class="card-img-top" alt="Surat Keterangan Usaha">
            <div class="card-body text-center">
              <h5 class="menu-surat-title">SURAT KETERANGAN USAHA</h5>
              <a href="surat_keterangan_usaha/" class="btn btn-info menu-surat-btn">BUAT SURAT</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-4 d-flex">
          <div class="card menu-surat-card w-100">
            <img src="../assets/img/menu_surat.png" class="card-img-top" alt="Surat Lapor Hajatan">
            <div class="card-body text-center">
              <h5 class="menu-surat-title">SURAT LAPOR HAJATAN</h5>
              <a href="surat_lapor_hajatan/" class="btn btn-info menu-surat-btn">BUAT SURAT</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-4 d-flex">
          <div class="card menu-surat-card w-100">
            <img src="../assets/img/menu_surat.png" class="card-img-top" alt="Surat Akta Kematian">
            <div class="card-body text-center">
              <h5 class="menu-surat-title">SURAT KETERANGAN AKTA KEMATIAN</h5>
              <a href="surat_keterangan_akta_kematian/" class="btn btn-info menu-surat-btn">BUAT SURAT</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-4 d-flex">
          <div class="card menu-surat-card w-100">
            <img src="../assets/img/menu_surat.png" class="card-img-top" alt="Surat Cetak Kembali Akta Capil">
            <div class="card-body text-center">
              <h5 class="menu-surat-title">SURAT CETAK KEMBALI AKTA CAPIL</h5>
              <a href="surat_cetak_kembali_akta_capil/" class="btn btn-info menu-surat-btn">BUAT SURAT</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-4 d-flex">
          <div class="card menu-surat-card w-100">
            <img src="../assets/img/menu_surat.png" class="card-img-top" alt="Surat Pembetulan Akta Capil">
            <div class="card-body text-center">
              <h5 class="menu-surat-title">SURAT PEMBETULAN AKTA CAPIL</h5>
              <a href="surat_pembetulan_akta_capil/" class="btn btn-info menu-surat-btn">BUAT SURAT</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-4 d-flex">
          <div class="card menu-surat-card w-100">
            <img src="../assets/img/menu_surat.png" class="card-img-top" alt="Surat Perubahan Nama Dengan Capil">
            <div class="card-body text-center">
              <h5 class="menu-surat-title">SURAT PERUBAHAN NAMA DENGAN CAPIL</h5>
              <a href="surat_perubahan_nama_dengan_capil/" class="btn btn-info menu-surat-btn">BUAT SURAT</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-4 d-flex">
          <div class="card menu-surat-card w-100">
            <img src="../assets/img/menu_surat.png" class="card-img-top" alt="Surat Pendaftaran & Pencetakan KK">
            <div class="card-body text-center">
              <h5 class="menu-surat-title">SURAT PENDAFTARAN DAN PENCETAKAN KK</h5>
              <a href="surat_pendaftaran_dan_pencetakan_kk/" class="btn btn-info menu-surat-btn">BUAT SURAT</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="footer bg-dark text-center py-3">
    <span class="text-light">
      <strong>Copyright &copy; 2026
        <a href="../" class="text-decoration-none text-white">e-SuratDesa</a>.
      </strong> All rights reserved.
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

  <!-- JS Bootstrap 4 (boleh tetap ada buat komponen lain) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="../assets/bootstrap-4.3.1/dist/js/bootstrap.min.js"></script>
</body>
</html>
