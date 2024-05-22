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
                <div id="breadcrumbs-wrapper" class=" white lighten-3">
                    <div class="container">

                        <div class="col s12 m12 l12">
                            <h5 class="breadcrumbs-title">Timeline Project</h5>
                            <ol class="breadcrumb">
                                <li><a href="index.php">Dashboard Jobs</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
                <!--breadcrumbs end-->
                <!-- Container -->
                <div class="container">
                    <!--card stats start-->
                    <div id="card-stats">
                        <div class="row">
                            <?php $tampil = mysqli_query($koneksi, "select * from tiket where status='new'");
                            $total = mysqli_num_rows($tampil);
                            ?>

                            <?php $tampil1 = mysqli_query($koneksi, "select * from tiket where status='proses'");
                            $total1 = mysqli_num_rows($tampil1);
                            ?>

                            <?php $tampil2 = mysqli_query($koneksi, "select * from tiket where status='close'");
                            $total2 = mysqli_num_rows($tampil2);
                            ?>

                            <?php $tampil3 = mysqli_query($koneksi, "select * from tiket order by id_tiket");
                            $total3 = mysqli_num_rows($tampil3);
                            ?>

                        </div>
                    </div>
                    <!--card stats end-->
                    <button><a href="dashboard_depan.php">Full View</a></button>
                    <div id="work-collections">
                        <div class="row">
                            <?php
                            $tanggal = date("Y-m-d");
                            $query = "SELECT * FROM user ";
                            $query .= "limit 3";
                            $tampil = mysqli_query($koneksi, $query);
                            ?>
                            <?php
                            $no = 0;
                            while ($data = mysqli_fetch_array($tampil)) {
                                $no++; ?>
                                <div class="col s12 m12 l4">
                                    <a href="dashboard_depan.php">
                                        <ul id="projects-collection" class="collection">
                                            <li class="collection-item avatar">
                                                <i class="mdi-social-person circle red darken-2"></i>
                                                <span class="collection-header">Nama : <?php echo $data['username'] ?></span>
                                                <p>Fullname : <?php echo $data['fullname'] ?></p>
                                                <p>Level : <?php echo $data['level'] ?></p>
                                            </li>
                                            <li class="collection-item">
                                                <div class="row">
                                                    <?php
                                                    $query_baru = "SELECT * FROM tiket where nama = '" . $data['username'] . "' and status = 'new'";
                                                    $tampil_baru = mysqli_query($koneksi, $query_baru);
                                                    $total_baru = mysqli_num_rows($tampil_baru);
                                                    ?>
                                                    <p>Pekerjaan Baru : <?php echo $total_baru; ?></p>
                                                    <?php
                                                    $query_process = "SELECT * FROM tiket where nama = '" . $data['username'] . "' and status = 'Proses'";
                                                    $tampil_process = mysqli_query($koneksi, $query_process);
                                                    $total_process = mysqli_num_rows($tampil_process);
                                                    ?>
                                                    <p>Pekerjaan Process : <?php echo $total_process ?></p>
                                                    <?php
                                                    $query_selesai = "SELECT * FROM tiket where nama = '" . $data['username'] . "' and status = 'Close'";
                                                    $tampil_selesai = mysqli_query($koneksi, $query_selesai);
                                                    $total_selesai = mysqli_num_rows($tampil_selesai);
                                                    ?>
                                                    <p>Pekerjaan Selesai : <?php echo $total_selesai; ?></p>
                                                    <?php
                                                    $query_total = "SELECT * FROM tiket where nama = '" . $data['username'] . "'";
                                                    $tampil_total = mysqli_query($koneksi, $query_total);
                                                    $total_total = mysqli_num_rows($tampil_total);
                                                    ?>
                                                    <p>Total Pekerjaan : <?php echo $total_total; ?></p>
                                                </div>
                                            </li>
                                        </ul>
                                    </a>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <!--work collections end-->

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

        <!-- ================================================Scripts================================================ -->

        <!-- jQuery Library -->
        <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
        <!--materialize js-->
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <!--scrollbar-->
        <script type="text/javascript" src="js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>


        <!-- chartist -->
        <script type="text/javascript" src="js/plugins/chartist-js/chartist.min.js"></script>

        <!-- chartjs -->
        <script type="text/javascript" src="js/plugins/chartjs/chart.min.js"></script>
        <script type="text/javascript" src="js/plugins/chartjs/chart-script.js"></script>

        <!-- sparkline -->
        <script type="text/javascript" src="js/plugins/sparkline/jquery.sparkline.min.js"></script>
        <script type="text/javascript" src="js/plugins/sparkline/sparkline-script.js"></script>

        <!--jvectormap-->
        <script type="text/javascript" src="js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script type="text/javascript" src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <script type="text/javascript" src="js/plugins/jvectormap/vectormap-script.js"></script>


        <!--plugins.js - Some Specific JS codes for Plugin Settings-->
        <script type="text/javascript" src="js/plugins.js"></script>
        <!-- Toast Notification -->
        <script type="text/javascript">
            // Toast Notification
            $(window).load(function() {
                setTimeout(function() {
                    Materialize.toast('<span>Hiya! I am a toast.</span>', 1500);
                }, 3000);
                setTimeout(function() {
                    Materialize.toast('<span>You can swipe me too!</span>', 3000);
                }, 5500);
                setTimeout(function() {
                    Materialize.toast('<span>You have new order.</span><a class="btn-flat yellow-text" href="#">Read<a>', 3000);
                }, 18000);
            });
        </script>
    </body>

    </html>