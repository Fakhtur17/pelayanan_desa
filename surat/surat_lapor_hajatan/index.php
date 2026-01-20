<?php include('../part/header.php'); ?>

<div class="container" style="padding-top:60px; padding-bottom:60px; min-height:100%;" align="center">
  <div class="card col-md-4">
    <div class="card-body">
      <form action="info-surat.php" method="post">
        <?php
          if(isset($_GET['pesan']) && $_GET['pesan']=="gagal"){
            echo "<div class='alert alert-danger'><center>NIK Anda tidak terdaftar. Silahkan hubungi Kantor Desa!</center></div>";
          }
          if(isset($_GET['pesan']) && $_GET['pesan']=="berhasil"){
            echo "<div class='alert alert-success'><center>Pengajuan surat berhasil dikirim!</center></div>";
          }
        ?>

        <img src="../../assets/img/logo_banjarnegara.png" alt="Logo" style="max-width:120px;">
        <hr>

        <label style="font-weight:700;">
          <i class="fas fa-id-card"></i> NIK <i>(Nomor Induk Kependudukan)</i>
        </label>

        <input type="text" class="form-control form-control-md"
               maxlength="16" onkeypress="return hanyaAngka(event)"
               name="fnik" placeholder="Masukkan NIK Anda..." required>

        <script>
          function hanyaAngka(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
            return true;
          }
        </script>

        <br>
        <button type="submit" class="btn btn-info btn-md">
          <i class="fas fa-search"></i> CEK NIK
        </button>
      </form>
    </div>
  </div>
</div>

<?php include('../part/footer.php'); ?>
