<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (empty($_SESSION['username'])) {
  header('location:../index.php');
} else {
  include "../conn.php";
?>
  <!DOCTYPE html>
  <html lang="en">

  <?php include "head.php"; ?>

  <body>
    <!-- Start Page Loading -->
    <div id="loader-wrapper">
      <div id="loader"></div>
      <div class="loader-section section-left"></div>
      <div class="loader-section section-right"></div>
    </div>
    <!-- End Page Loading -->

    <!-- //////////////////////////////////////////////////////////////////////////// -->

    <!-- START HEADER -->

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
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Data Admin TimePRO</h5>
                <ol class="breadcrumb">
                  <li><a href="index.php">Dashboard</a></li>
                  <li><a href="admin.php">Admin</a></li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->


        <!--start container-->
        <div class="container">
          <?php
          if (isset($_GET['aksi']) == 'delete') {
            $id = $_GET['id'];
            $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE user_id='$id'");
            if (mysqli_num_rows($cek) == 0) {
              echo '<script>sweetAlert({
	                                                   title: "Ups!", 
                                                        text: "Data tiket tidak ditemukan!", 
                                                        type: "error"
                                                        });</script>';
            } else {
              $delete = mysqli_query($koneksi, "DELETE FROM user WHERE user_id='$id'");
              if ($delete) {
                echo '<script>sweetAlert({
	                                                   title: "Berhasil!", 
                                                        text: "Data Berhasil di hapus!", 
                                                        type: "success"
                                                        });</script>';
              } else {
                echo '<script>sweetAlert({
	                                                   title: "Gagal!", 
                                                        text: "Data gagal di hapus!", 
                                                        type: "error"
                                                        });</script>';
              }
            }
          }
          ?>
          <div id="table-datatables" style="padding-bottom: 56px;">
            <h4 class="header"></h4>
            <a href="input-admin.php" class="btn-floating btn-small waves-effect waves-light green darken-2" title="Tambah Admin"><i class="mdi-content-add"></i></a>
            <br /><br />
            <div class="row">
              <div class="col s12 m12">
                <table id="lookup" class="responsive-table display" cellspacing="0">
                  <thead>
                    <tr>

                      <th>User ID</th>
                      <th>Username</th>
                      <th>Password</th>
                      <th>Fullname</th>
                      <th>Level</th>
                      <th>Tools</th>


                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
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
            url: "ajax-grid-data2.php", // json datasource
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