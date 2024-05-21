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
        <div id="loader-wrapper">
            <div id="loader"></div>
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>
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
                            <h5 class="breadcrumbs-title">TimePRO</h5>
                            <ol class="breadcrumb">
                                <li><a href="index.php">Dashboard</a></li>
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
                            <div class="col s12 m6 l3">
                                <div class="card">
                                    <div class="card-content  red white-text">
                                        <p class="card-stats-title"><i class=""></i>Pekerjaan Baru</p>
                                        <h4 class="card-stats-number"><?php echo $total; ?></h4>
                                        <p class="card-stats-compare"><!-- <i class="mdi-hardware-keyboard-arrow-up"></i> --> <span class="green-text text-lighten-5">Belum Ditangani</span>
                                        </p>
                                    </div>
                                    <div class="card-action  red darken-2">
                                        <div id="clients-bar"></div>
                                    </div>
                                </div>
                            </div>
                            <?php $tampil1 = mysqli_query($koneksi, "select * from tiket where status='proses'");
                            $total1 = mysqli_num_rows($tampil1);
                            ?>
                            <div class="col s12 m6 l3">
                                <div class="card">
                                    <div class="card-content green white-text">
                                        <p class="card-stats-title"><i class=""></i>Pekerjaan Proses</p>
                                        <h4 class="card-stats-number"><?php echo $total1 ?></h4>
                                        <p class="card-stats-compare"><!-- <i class="mdi-hardware-keyboard-arrow-up"></i> --> <span class="purple-text text-lighten-5">Sedang Ditangani</span>
                                        </p>
                                    </div>
                                    <div class="card-action green darken-2">
                                        <div id="sales-compositebar"></div>

                                    </div>
                                </div>
                            </div>
                            <?php $tampil2 = mysqli_query($koneksi, "select * from tiket where status='close'");
                            $total2 = mysqli_num_rows($tampil2);
                            ?>
                            <div class="col s12 m6 l3">
                                <div class="card">
                                    <div class="card-content blue white-text">
                                        <p class="card-stats-title"><i class=""></i>Pekerjaan Selesai</p>
                                        <h4 class="card-stats-number"><?php echo $total2; ?></h4>
                                        <p class="card-stats-compare"><!-- <i class="mdi-hardware-keyboard-arrow-up"></i> --> <span class="blue-grey-text text-lighten-5">Sudah Ditangani</span>
                                        </p>
                                    </div>
                                    <div class="card-action blue darken-2">
                                        <div id="profit-tristate"></div>
                                    </div>
                                </div>
                            </div>
                            <?php $tampil3 = mysqli_query($koneksi, "select * from tiket order by id_tiket");
                            $total3 = mysqli_num_rows($tampil3);
                            ?>
                            <div class="col s12 m6 l3">
                                <div class="card">
                                    <div class="card-content deep-purple white-text">
                                        <p class="card-stats-title"><i class=""></i>Total Pekerjaan Tersedia</p>
                                        <h4 class="card-stats-number"><?php echo $total3; ?></h4>
                                        <p class="card-stats-compare"><!-- <i class="mdi-hardware-keyboard-arrow-down"></i> 3% --><span class="deep-purple-text text-lighten-5">Jumlah Permasalahan</span>
                                        </p>
                                    </div>
                                    <div class="card-action  deep-purple darken-2">
                                        <div id="invoice-line"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--card stats end-->


                    <div id="work-collections">
                        <div class="row">
                            <div class="col s12 m12 l4">
                                <ul id="projects-collection" class="collection">
                                    <li class="collection-item avatar">
                                        <i class="mdi-alert-error circle red darken-2"></i>
                                        <span class="collection-header">Pekerjaan Baru</span>
                                        <p>Status <b style="color: red;">New</b></p>
                                        <!-- <a href="#" class="secondary-content"><i class="mdi-action-grade"></i></a>-->
                                    </li>
                                    <?php
                                    $tanggal = date("Y-m-d");
                                    $query = "SELECT * FROM tiket WHERE status='new' limit 7";
                                    $tampil = mysqli_query($koneksi, $query) or die(mysqli_error());
                                    ?>
                                    <?php
                                    $no = 0;
                                    while ($data = mysqli_fetch_array($tampil)) {
                                        $no++; ?>
                                        <li class="collection-item">
                                            <div class="row">
                                                <div class="col s9">
                                                    <p class="collections-title"><?php echo $no; ?>. <?php echo $data['nama']; ?> | <?php echo $data['departemen']; ?></p>
                                                    <p class="collections-content">Problem : <?php echo $data['problem']; ?></p>
                                                </div>
                                                <div class="col s3">
                                                    <?php if ($data['status'] == "new") {
                                                        echo "<span class='task-cat pink'>Tiket $data[status]</span>";
                                                    } else if ($data['status'] == "close") {
                                                        echo "<span class='task-cat teal'>Tiket $data[status]</span>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="col s12 m12 l4">
                                <ul id="projects-collection" class="collection">
                                    <li class="collection-item avatar">
                                        <i class="mdi-av-play-circle-fill circle green darken-2"></i>
                                        <span class="collection-header">Pekerjaan Proses</span>
                                        <p>Status <b style="color: green;">Process</b></p>
                                        <!-- <a href="#" class="secondary-content"><i class="mdi-action-grade"></i></a>-->
                                    </li>
                                    <?php
                                    $tanggal = date("Y-m-d");
                                    $query = "SELECT * FROM tiket WHERE status='proses' limit 7";
                                    $tampil = mysqli_query($koneksi, $query) or die(mysqli_error());
                                    ?>
                                    <?php
                                    $no = 0;
                                    while ($data = mysqli_fetch_array($tampil)) {
                                        $no++; ?>
                                        <li class="collection-item">
                                            <div class="row">
                                                <div class="col s9">
                                                    <p class="collections-title"><?php echo $no; ?>. <?php echo $data['nama']; ?> | <?php echo $data['departemen']; ?></p>
                                                    <p class="collections-content">Problem : <?php echo $data['problem']; ?></p>
                                                </div>
                                                <div class="col s3">
                                                    <?php if ($data['status'] == "proses") {
                                                        echo "<span class='task-cat pink'>Tiket $data[status]</span>";
                                                    } else if ($data['status'] == "close") {
                                                        echo "<span class='task-cat teal'>Tiket $data[status]</span>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>


                            <div class="col s12 m12 l4">
                                <ul id="issues-collection" class="collection">
                                    <li class="collection-item avatar">
                                        <i class="mdi-toggle-check-box circle light-blue darken-2"></i>
                                        <span class="collection-header">Pekerjaan Selesai</span>
                                        <p>Status <b style="color: blue;">Close</b></p>
                                        <!-- <a href="#" class="secondary    -content"><i class="mdi-action-grade"></i></a> -->
                                    </li>
                                    <?php
                                    $tanggal = date("Y-m-d");
                                    $query1 = "SELECT * FROM tiket WHERE status='close' limit 7";
                                    $tampil1 = mysqli_query($koneksi, $query1) or die(mysqli_error());
                                    ?>
                                    <?php
                                    $no = 0;
                                    while ($data1 = mysqli_fetch_array($tampil1)) {
                                        $no++; ?>
                                        <li class="collection-item">
                                            <div class="row">
                                                <div class="col s9">
                                                    <p class="collections-title"><?php echo $no; ?>. <?php echo $data1['nama']; ?> | <?php echo $data1['departemen']; ?></p>
                                                    <p class="collections-content">Problem : <?php echo $data1['problem']; ?></p>
                                                    <p class="collections-content">Penanganan : <?php echo $data1['penanganan']; ?></p>
                                                </div>
                                                <div class="col s3">
                                                    <?php if ($data1['status'] == "open") {
                                                        echo "<span class='task-cat pink'>Tiket $data1[status]</span>";
                                                    } else if ($data1['status'] == "close") {
                                                        echo "<span class='task-cat teal'>Tiket $data1[status]</span>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
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


        <!-- ================================================
    Scripts
    ================================================ -->

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