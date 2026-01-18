<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="shortcut icon" href="../assets/img/mini-logo.png">
  <title>Tentang e-SuratDesa</title>

  <link rel="stylesheet" href="../assets/fontawesome-5.10.2/css/all.css">
  <link rel="stylesheet" href="../assets/bootstrap-4.3.1/dist/css/bootstrap.min.css">

  <style>
    body { background: #f8f9fa; }

    /* Card modern */
    .card-soft{
      border: 0;
      border-radius: 16px;
      box-shadow: 0 10px 26px rgba(0,0,0,.06);
    }

    .section-title{
      font-weight: 800;
      font-size: 1.35rem;
      margin-bottom: .75rem;
    }

    .muted-small{
      color:#6c757d;
      font-size:.95rem;
    }

    .icon-badge{
      width: 46px;
      height: 46px;
      border-radius: 14px;
      display:flex;
      align-items:center;
      justify-content:center;
      color:#17a2b8;
      background: rgba(23,162,184,.12);
      flex: 0 0 46px;
    }

    /* HERO LANDSCAPE */
    .hero-landscape {
      position: relative;
      height: 550px;
      background: url('../assets/img/background.png') center/cover no-repeat;
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 12px 30px rgba(0,0,0,.12);
    }
    .hero-landscape::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(rgba(0,0,0,.60), rgba(0,0,0,.45));
    }
    .hero-content {
  position: relative;
  z-index: 2;
  height: 100%;
  display: flex;
  align-items: flex-end;   /* bikin konten turun */
  padding: 42px;
  padding-bottom: 40px;   /* tambah jarak dari bawah */
  color: #fff;
}

    .hero-content h1{
      font-weight: 900;
      letter-spacing: .3px;
      margin-bottom: 10px;
    }
    .hero-content p{
      font-size: 1.08rem;
      opacity: .95;
      max-width: 680px;
      margin-bottom: 14px;
    }
    .hero-chips .badge{
      font-size: .9rem;
      padding: .55rem .85rem;
      margin-right: .5rem;
      margin-bottom: .4rem;
    }

    /* Benefit list */
    .list-check li{
      margin-bottom: .55rem;
    }
    .list-check i{
      color:#28a745;
      margin-right:.55rem;
    }

    /* LANDSCAPE GALLERY */
    .landscape-img {
      border-radius: 14px;
      height: 230px;
      width: 100%;
      object-fit: cover;
      box-shadow: 0 10px 24px rgba(0,0,0,.14);
      transition: transform .25s ease, box-shadow .25s ease;
    }
    .landscape-img:hover {
      transform: translateY(-4px) scale(1.02);
      box-shadow: 0 14px 30px rgba(0,0,0,.18);
    }

    /* Circle logo */
    .img-circle{
      width: 110px;
      height: 110px;
      border-radius: 100%;
      object-fit: cover;
      border: 4px solid #fff;
      box-shadow: 0 8px 20px rgba(0,0,0,.12);
    }

    .footer{
      margin-top: 50px;
      padding: 18px 0;
    }
  </style>
</head>

<body>

<!-- NAVBAR -->
<div>
  <nav class="navbar navbar-expand-lg navbar-dark bg-info">
    <a class="navbar-brand ml-4 mt-1" href="../">
      <img src="../assets/img/e-SuratDesa.png" alt="e-SuratDesa">
    </a>

    <button class="navbar-toggler mr-4 mt-3" type="button" data-toggle="collapse"
            data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02"
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
      <ul class="navbar-nav ml-auto mt-lg-3 mr-5 position-relative text-right">
        <li class="nav-item">
          <a class="nav-link" href="../">HOME</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../surat">BUAT SURAT</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="#"><i class="fas fa-info-circle"></i>&nbsp;TENTANG <b>e-SuratDesa</b></a>
        </li>
        <li class="nav-item active ml-5">
          <?php
            session_start();
            if (empty($_SESSION['username'])) {
              echo '<a class="btn btn-light text-info" href="../login/"><i class="fas fa-sign-in-alt"></i>&nbsp;LOGIN</a>';
            } else if (isset($_SESSION['lvl'])) {
              echo '<a class="btn btn-transparent text-light" href="../admin/"><i class="fa fa-user-cog"></i> '.$_SESSION['lvl'].'</a>';
              echo '<a class="btn btn-transparent text-light" href="../login/logout.php"><i class="fas fa-power-off"></i></a>';
            }
          ?>
        </li>
      </ul>
    </div>
  </nav>
</div>

<!-- HERO LANDSCAPE -->
<div class="container mt-4">
  <div class="hero-landscape mb-4">
    <div class="hero-content">
      <div>
        <h1><i class="fas fa-envelope-open-text"></i> e-SuratDesa</h1>
        <p>
          Aplikasi pelayanan surat administrasi desa berbasis web untuk memudahkan masyarakat melakukan pengajuan surat
          secara online dengan proses yang lebih cepat, tertib, dan transparan.
        </p>
        <div class="hero-chips">
          <span class="badge badge-info"><i class="fas fa-shield-alt"></i> Aman & Tertib Arsip</span>
          <span class="badge badge-light text-info"><i class="fas fa-bolt"></i> Cepat & Praktis</span>
          <span class="badge badge-dark"><i class="fas fa-university"></i> KKN UNSOED 2026</span>
          <span class="badge badge-secondary">Periode Januari – Februari</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MAIN CONTENT -->
<div class="container pb-2">

  <div class="row">

    <!-- LEFT -->
    <div class="col-lg-8 mb-4">
      <div class="card card-soft">
        <div class="card-body p-4 p-md-5">

          <!-- APA ITU -->
          <div class="d-flex mb-4">
            <div class="icon-badge mr-3">
              <i class="fas fa-info-circle fa-lg"></i>
            </div>
            <div>
              <div class="section-title mb-1">Apa itu e-SuratDesa?</div>
              <div class="text-muted">
                e-SuratDesa adalah web aplikasi pelayanan administrasi desa yang membantu warga dalam mengajukan berbagai
                surat secara online. Sistem ini dirancang untuk mempercepat proses layanan, mengurangi antrean,
                serta membantu perangkat desa dalam pengelolaan data dan arsip surat secara lebih rapi.
              </div>
            </div>
          </div>

          <hr>

          <!-- TUJUAN & MANFAAT -->
          <div class="d-flex mb-4">
            <div class="icon-badge mr-3">
              <i class="fas fa-bullseye fa-lg"></i>
            </div>
            <div class="w-100">
              <div class="section-title mb-2">Tujuan & Manfaat</div>
              <ul class="list-unstyled list-check text-muted mb-0">
                <li><i class="fas fa-check-circle"></i> Mempercepat proses pengajuan dan pembuatan surat administrasi desa.</li>
                <li><i class="fas fa-check-circle"></i> Mengurangi kesalahan input dengan format data yang terstandar.</li>
                <li><i class="fas fa-check-circle"></i> Memudahkan pelacakan status surat (diproses/selesai/ditolak).</li>
                <li><i class="fas fa-check-circle"></i> Meningkatkan kerapihan arsip dan memudahkan pencarian data.</li>
                <li><i class="fas fa-check-circle"></i> Mendukung transformasi layanan desa menjadi lebih digital dan efisien.</li>
              </ul>
            </div>
          </div>

          <hr>

          <!-- FITUR UTAMA -->
          <div class="d-flex">
            <div class="icon-badge mr-3">
              <i class="fas fa-layer-group fa-lg"></i>
            </div>
            <div class="w-100">
              <div class="section-title mb-2">Fitur Utama</div>

              <div class="row">
                <div class="col-md-6 mb-2 text-muted">
                  <i class="fas fa-file-signature text-info"></i> Pengajuan surat online
                </div>
                <div class="col-md-6 mb-2 text-muted">
                  <i class="fas fa-search text-info"></i> Cek status surat
                </div>
                <div class="col-md-6 mb-2 text-muted">
                  <i class="fas fa-user-check text-info"></i> Validasi data penduduk
                </div>
                <div class="col-md-6 mb-2 text-muted">
                  <i class="fas fa-folder-open text-info"></i> Arsip & riwayat surat
                </div>
                <div class="col-md-6 mb-2 text-muted">
                  <i class="fas fa-print text-info"></i> Cetak surat sesuai format
                </div>
                <div class="col-md-6 mb-2 text-muted">
                  <i class="fas fa-user-cog text-info"></i> Manajemen pengguna (Admin/Kades)
                </div>
              </div>

              
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="col-lg-4 mb-4">

      <!-- IDENTITAS KKN -->
      <div class="card card-soft mb-4">
        <div class="card-body p-4 text-center">
          <img src="../assets/img/mini-logo.png" class="img-circle mb-3" alt="KKN UNSOED">
          <h5 class="font-weight-bold mb-1">KKN UNSOED 2026</h5>
          <div class="text-muted mb-2">Periode Januari – Februari</div>
          <p class="muted-small mb-0">
            Dikembangkan sebagai bagian dari program pengabdian masyarakat untuk mendukung digitalisasi layanan administrasi desa.
          </p>
        </div>
      </div>

      <!-- HAK CIPTA -->
      <div class="card card-soft">
        <div class="card-body p-4">
          <div class="d-flex align-items-start">
            <div class="icon-badge mr-3">
              <i class="fas fa-shield-alt fa-lg"></i>
            </div>
            <div>
              <div class="section-title mb-1" style="font-size:1.2rem;">Hak Cipta & Ketentuan</div>
              <div class="text-muted mb-2">
                <b>© 2026 e-SuratDesa.</b> Seluruh hak cipta dilindungi.
              </div>
              <div class="muted-small">
                Aplikasi ini dikembangkan oleh <b>Tim KKN Universitas Jenderal Soedirman (UNSOED)</b>
                Periode <b>Januari – Februari 2026</b>.
                Dilarang menyalin, memperbanyak, atau mendistribusikan sebagian/seluruh sistem tanpa izin tertulis
                dari pengembang dan/atau pihak desa terkait.
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- DOKUMENTASI (LANDSCAPE GALLERY) -->
  <div class="card card-soft mb-4">
    <div class="card-body p-4 p-md-5">
      <div class="d-flex align-items-start mb-2">
        <div class="icon-badge mr-3">
          <i class="fas fa-camera-retro fa-lg"></i>
        </div>
        <div>
          <div class="section-title mb-1">Dokumentasi Kegiatan KKN</div>
          <div class="text-muted">
            Dokumentasi kegiatan pengabdian masyarakat KKN UNSOED 2026 dalam mendukung digitalisasi layanan administrasi desa.
          </div>
        </div>
      </div>

      <hr>

      <div class="row">
        <div class="col-md-4 mb-4">
          <img src="../assets/img/KKN-1.jpeg" class="landscape-img" alt="Dokumentasi KKN 1">
        </div>
        <div class="col-md-4 mb-4">
          <img src="../assets/img/KKN-2.jpeg" class="landscape-img" alt="Dokumentasi KKN 2">
        </div>
        <div class="col-md-4 mb-4">
          <img src="../assets/img/KKN-3.jpeg" class="landscape-img" alt="Dokumentasi KKN 3">
        </div>
      </div>
    </div>
  </div>

</div>

<!-- FOOTER -->
<div class="footer bg-dark text-center">
  <span class="text-light">
    <strong>
      Copyright &copy; 2026
      <a href="../" class="text-decoration-none text-white">e-SuratDesa</a>.
    </strong>
    All rights reserved. — <span style="opacity:.9;">KKN UNSOED 2026 (Januari–Februari)</span>
  </span>
</div>

<!-- JS agar navbar toggler jalan -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="../assets/bootstrap-4.3.1/dist/js/bootstrap.min.js"></script>

</body>
</html>
