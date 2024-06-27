<?php
session_start();
if (empty($_SESSION['username'])) {
  header('location:../index.php');
} else {
  include "../conn.php";
?>
  <!DOCTYPE html>
  <html lang="en">

  <!--================================================================================
	Item Name: Materialize - Material Design Admin Template
	Version: 1.0
	Author: GeeksLabs
	Author URL: http://www.themeforest.net/user/geekslabs
================================================================================ -->

  <?php include "head.php"; ?>

  <body>
    <!-- Start Page Loading -->
    <!-- <div id="loader-wrapper">
      <div id="loader"></div>
      <div class="loader-section section-left"></div>
      <div class="loader-section section-right"></div>
    </div> -->
    <!-- End Page Loading -->

    <!-- //////////////////////////////////////////////////////////////////////////// -->

    <!-- START HEADER -->
    <?php include "header.php"; ?>
    <!-- END HEADER -->

    <!-- //////////////////////////////////////////////////////////////////////////// -->

    <!-- START MAIN -->
    <div id="main">
      <!-- START WRAPPER -->
      <div class="wrapper">

        <!-- START LEFT SIDEBAR NAV-->
        <?php include "menu.php"; ?>
        <?php
        $timeout = 10; // Set timeout minutes
        $logout_redirect_url = "../index.php"; // Set logout URL

        $timeout = $timeout * 60; // Converts minutes to seconds
        if (isset($_SESSION['start_time'])) {
          $elapsed_time = time() - $_SESSION['start_time'];
          if ($elapsed_time >= $timeout) {
            session_destroy();
            echo "<script>alert('Session Anda Telah Habis!'); window.location = '$logout_redirect_url'</script>";
          }
        }
        $_SESSION['start_time'] = time();
        ?>
      <?php } ?>
      <!-- END LEFT SIDEBAR NAV-->

      <!-- //////////////////////////////////////////////////////////////////////////// -->

      <!-- START CONTENT -->
      <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" whute lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Data Tiket TimePRO</h5>
                <ol class="breadcrumb">
                  <li><a href="index.php">Dashboard</a></li>
                  <li><a href="tiket.php">Tiket</a></li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->


        <!--start container-->
        <div class="container">
          <?php
          $kd = $_GET['id'];
          $sql = mysqli_query($koneksi, "SELECT * FROM tiket WHERE id_tiket='$kd'");
          if (mysqli_num_rows($sql) == 0) {
            header("Location: tiket.php");
          } else {
            $row = mysqli_fetch_assoc($sql);
          }
          if (isset($_POST['update'])) {
            $waktu = $row['waktu'];
            $id_tiket  = $_POST['id_tiket'];
            $tanggal   = $_POST['tanggal'];

            $nama      = $_POST['nama'];
            $email     = $_POST['email'];
            $departemen = $_POST['departemen'];
            $problem   = $_POST['problem'];
            $penanganan = "kosong";
            $status    = $_POST['status'];
            $filename = $_POST['filename'];
            $pic        = "kosong";
            $fotopengerjaan        = $_FILES['choosefile']["name"];
            $tempname              = $_FILES["choosefile"]["tmp_name"];


            $folder = "images/" . $fotopengerjaan;

            $laporan = "<h4><b>Tiket Status : $waktu</b></h4>";
            $laporan .= "<br/>";
            $laporan .= "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">";
            $laporan .= "<tr>";
            $laporan .= "<td>Tanggal</td><td>:</td><td>$tanggal</td>";
            $laporan .= "</tr>";

            $laporan .= "<tr>";
            $laporan .= "<td>Nama</td><td>:</td><td>$nama</td>";
            $laporan .= "</tr>";
            $laporan .= "<tr>";
            $laporan .= "<td>Departemen</td><td>:</td><td>$departemen</td>";
            $laporan .= "</tr>";
            $laporan .= "<tr>";
            $laporan .= "<td>Problem</td><td>:</td><td>$problem</td>";
            $laporan .= "</tr>";
            $laporan .= "<tr>";
            $laporan .= "<td>Penanganan</td><td>:</td><td>$penanganan</td>";
            $laporan .= "</tr>";
            $laporan .= "<tr>";
            $laporan .= "<td>Status</td><td>:</td><td>$status</td>";
            $laporan .= "</tr>";
            $laporan .= "<tr>";
            $laporan .= "<td>Filename</td><td>:</td><td>$filename</td>";
            $laporan .= "</tr>";
            $laporan .= "<tr>";
            $laporan .= "<td>Pic</td><td>:</td><td>$pic</td>";
            $laporan .= "</tr>";


            require_once("../phpmailer/class.phpmailer.php");
            require_once("../phpmailer/class.smtp.php");

            $sendmail = new PHPMailer(true);
            $sendmail->isSMTP();                                            // Send using SMTP
            $sendmail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $sendmail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $sendmail->Username   = 'ypap@sekolah-avicenna.sch.id';                     // SMTP username
            $sendmail->Password   = 'ypap@123';                               // SMTP password
            $sendmail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            $sendmail->setFrom('ypap@sekolah-avicenna.sch.id', 'YPAP');
            $sendmail->addAddress("$email", "$nama"); //email tujuan
            $sendmail->addReplyTo('ypap@sekolah-avicenna.sch.id', 'YPAP');
            $sendmail->isHTML(true);                                  // Set email format to HTML
            $sendmail->Subject = "Tiket TimePRO $waktu"; //subjek email
            $sendmail->Body = $laporan; //isi pesan dalam format laporan

            if (!$sendmail->Send()) {
              echo "Email gagal dikirim : " . $sendmail->ErrorInfo;
            } else {

              $update = mysqli_query($koneksi, "UPDATE tiket SET tanggal='$tanggal', nama='$nama', email='$email', departemen='$departemen', problem='$problem', penanganan='$penanganan', status='$status', pic='$pic', fotopengerjaan='$fotopengerjaan' WHERE id_tiket='$kd'") or die(mysqli_error());
              if ($update) {
                move_uploaded_file($tempname, $folder);
                echo '<script>sweetAlert({
	                                                   title: "Berhasil!", 
                                                        text: "Tiket Berhasil di update!", 
                                                        type: "success"
                                                        });</script>';
              } else {
                echo '<script>sweetAlert({
	                                                   title: "Gagal!", 
                                                        text: "Tiket Gagal di update, silahakan coba lagi!", 
                                                        type: "error"
                                                        });</script>';
              }
            }
          }

          ?>
          <div class="col s8 m8 l6">
            <div class="card-panel">
              <h4 class="header2">Edit Status Tiket Helpdeks</h4>
              <div class="row">
                <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1" class="col s12">
                  <div class="row">
                    <div class="input-field col s12">
                      <!-- <i class="mdi-action-assignment-ind prefix"></i> -->
                      <input id="id_tiket" name="id_tiket" value="<?php echo $row['id_tiket']; ?>" type="text" readonly="readonly">
                      <label for="Id Tiket">Id Tiket</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s12">
                      <!-- <i class="mdi-action-alarm-on prefix"></i> -->
                      <input id="tanggal" name="tanggal" value="<?php echo $row['tanggal']; ?>" type="text" readonly="readonly">
                      <label for="Tanggal">Tanggal</label>
                    </div>
                  </div>

                  <div class="row">
                    <div class="input-field col s12">
                      <!-- <i class="mdi-action-account-circle prefix"></i> -->
                      <input id="nama" name="nama" value="<?php echo $row['nama']; ?>" type="text" readonly="readonly">
                      <label for="Nama">Nama</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s12">
                      <!-- <i class="mdi-communication-email prefix"></i> -->
                      <input id="email" name="email" value="<?php echo $row['email']; ?>" type="email" readonly="readonly">
                      <label for="Email">Email</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s8">
                      <!-- <i class="mdi-action-lock-outline prefix"></i> -->
                      <input id="departemen" name="departemen" value="<?php echo $row['departemen']; ?>" type="text" readonly="readonly">
                      <label for="Departemen">Departemen</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s12">
                      <!-- <i class="mdi-action-subject prefix"></i> -->
                      <textarea id="problem" name="problem" class="materialize-textarea validate" length="120" readonly="readonly"><?php echo $row['problem']; ?></textarea>
                      <label for="Problem">Problem</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s12">
                      <!-- <i class="mdi-action-subject prefix"></i> -->
                      <a href="/timepro/admin/images/<?php echo $row['filename']; ?>" style="color:#eee; text-align: center;" data-toggle="tooltip" title="Edit" class="btn-floating waves-effect waves-light light-blue darken-3">view</a><label for="Problem">View</label>
                      <label for="Departemen">View</label>
                    </div>
                  </div>
                  <div class="row">
                    <?php
                    if ($row['fotopengerjaan'] == null && $row['fotopengerjaan'] == '') {
                    ?>
                      <label for="fotoperbaikan">Foto Perbaikan</label>
                      <div class="input-field col s12" style="padding-bottom: 20px;">
                        <input type="file" name="choosefile" value="">
                      </div>
                    <?php } else { ?>
                      <label for="fotoperbaikan">Foto Perbaikan</label>
                      <div class="input-field col s12" style="padding-bottom: 20px;">
                        <a href="/tiket/admin/images/<?php echo $row['fotopengerjaan'] ?>" style="color:#eee; text-align: center;" data-toggle="tooltip" title="Edit" class="btn-floating waves-effect waves-light light-blue darken-3">view</a>
                        <input type="file" name="choosefile" value="<?php echo $row['fotopengerjaan']; ?>" />
                      </div>
                    <?php } ?>
                  </div>
                  <div class="row">
                    <div class="input-field col s12">
                      <select name="status" id="status" required>
                        <option value="<?php echo $row['status']; ?>"> <?php echo $row['status']; ?></option>
                        <option value="New">New</option>
                        <option value="Diterima">Diterima</option>
                        <option value="Proses">Proses</option>
                        <option value="Close">Close</option>
                      </select>
                      <label for="Status">Status</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s12">
                      <button class="btn cyan waves-effect waves-light right" type="submit" name="update" id="update">Submit
                        <i class="mdi-content-send right"></i>
                      </button>
                    </div>
                  </div>
              </div>
              </form>
            </div>
          </div>
        </div>





      </div>

    </div>
    <!--end container-->

    </section>
    <!-- END CONTENT -->
    </div>
    <!-- END WRAPPER -->

    </div>
    <!-- END MAIN -->



    <!-- //////////////////////////////////////////////////////////////////////////// -->

    <!-- START FOOTER -->
    <?php include "footer.php"; ?>
    <!-- END FOOTER -->



    <!-- ================================================
    Scripts
    ================================================ -->
    !-- jQuery Library -->
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
    <!--materialize js-->
    <script type="text/javascript" src="js/materialize.js"></script>
    <!--prism-->
    <script type="text/javascript" src="js/prism.js"></script>
    <!--scrollbar-->
    <script type="text/javascript" src="js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <!-- data-tables -->
    <script type="text/javascript" src="js/plugins/data-tables/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/data-tables/data-tables-script.js"></script>
    <!-- chartist -->
    <script type="text/javascript" src="js/plugins/chartist-js/chartist.min.js"></script>

    <!--plugins.js - Some Specific JS codes for Plugin Settings-->
    <script type="text/javascript" src="js/plugins.js"></script>

    <script>
      $(document).ready(function() {
        var dataTable = $('#lookup').DataTable({
          "processing": true,
          "serverSide": true,
          "ajax": {
            url: "ajax-grid-data1.php", // json datasource
            type: "post", // method  , by default get
            error: function() { // error handling
              $(".lookup-error").html("");
              $("#lookup").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#lookup_processing").css("display", "none");

            }
          }
        });
      });
    </script>

  </body>

  </html>