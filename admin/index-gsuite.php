<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['username'])){
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

                <!--start container-->
                <div class="container">
                    <!--card stats start-->
                    <div id="card-stats">
                        <div class="row">
                        <?php $tampil=mysqli_query($koneksi, "select * from tiket_gsuite where status='open'");
                        $total=mysqli_num_rows($tampil);
                        ?>
                            <div class="col s12 m6 l3">
                                <div class="card">
                                    <div class="card-content  green white-text">
                                        <p class="card-stats-title"><i class="mdi-social-group-add"></i> Tiket Baru</p>
                                        <h4 class="card-stats-number"><?php echo $total; ?></h4>
                                        <p class="card-stats-compare"><!-- <i class="mdi-hardware-keyboard-arrow-up"></i> --> <span class="green-text text-lighten-5">Belum Ditangani</span>
                                        </p>
                                    </div>
                                    <div class="card-action  green darken-2">
                                        <div id="clients-bar"></div>
                                    </div>
                                </div>
                            </div>
                            <?php $tampil1=mysqli_query($koneksi, "select * from tiket_gsuite where status='close'");
                        $total1=mysqli_num_rows($tampil1);
                        ?>
                            <div class="col s12 m6 l3">
                                <div class="card">
                                    <div class="card-content purple white-text">
                                        <p class="card-stats-title"><i class="mdi-social-group-add"></i> Tiket Selesai</p>
                                        <h4 class="card-stats-number"><?php echo $total1 ?></h4>
                                        <p class="card-stats-compare"><!-- <i class="mdi-hardware-keyboard-arrow-up"></i> --> <span class="purple-text text-lighten-5">Sudah Ditangani</span>
                                        </p>
                                    </div>
                                    <div class="card-action purple darken-2">
                                        <div id="sales-compositebar"></div>

                                    </div>
                                </div>
                            </div>
                            <?php $tampil2=mysqli_query($koneksi, "select * from tiket_gsuite order by id_tiket");
                        $total2=mysqli_num_rows($tampil2);
                        ?>
                            <div class="col s12 m6 l3">
                                <div class="card">
                                    <div class="card-content blue-grey white-text">
                                        <p class="card-stats-title"><i class="mdi-action-trending-up"></i> Tiket</p>
                                        <h4 class="card-stats-number"><?php echo $total2; ?></h4>
                                        <p class="card-stats-compare"><!-- <i class="mdi-hardware-keyboard-arrow-up"></i> --> <span class="blue-grey-text text-lighten-5">Total Tiket Masuk</span>
                                        </p>
                                    </div>
                                    <div class="card-action blue-grey darken-2">
                                        <div id="profit-tristate"></div>
                                    </div>
                                </div>
                            </div>
                            <?php $tampil3=mysqli_query($koneksi, "select * from user order by user_id");
                        $total3=mysqli_num_rows($tampil3);
                        ?>
                            <div class="col s12 m6 l3">
                                <div class="card">
                                    <div class="card-content deep-purple white-text">
                                        <p class="card-stats-title"><i class="mdi-editor-insert-drive-file"></i> Admin</p>
                                        <h4 class="card-stats-number"><?php echo $total3; ?></h4>
                                        <p class="card-stats-compare"><!-- <i class="mdi-hardware-keyboard-arrow-down"></i> 3% --><span class="deep-purple-text text-lighten-5">Users</span>
                                        </p>
                                    </div>
                                    <div class="card-action  deep-purple darken-2">
                                        <div id="invoice-line"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="work-collections">
                        <div class="row">
                            <div class="col s12 m12 l6">
                                <ul id="projects-collection" class="collection">
                                    <li class="collection-item avatar">
                                        <i class="mdi-action-bug-report circle red darken-2"></i>
                                        <span class="collection-header">Task Tiket</span>
                                        <?php
                                                echo $_SESSION['username'];
                                        ?>
                                        <p>Status <b style="color: red;">New</b></p>
                                        <!-- <a href="#" class="secondary-content"><i class="mdi-action-grade"></i></a>-->
                                    </li>
                                    <?php
                    $tanggal = date("Y-m-d");
                    $query= "SELECT * FROM tiket_gsuite WHERE status='new' limit 5";
                    $tampil=mysqli_query($koneksi, $query) or die(mysqli_error());
                    ?>
                    <?php
                    $no=0;
                    while($data=mysqli_fetch_array($tampil))
                    { $no++; ?>
                                    <li class="collection-item">
                                        <div class="row">
                                            <div class="col s9">
                                            <?php
                                                $kind = '';
                                                if ($data["name"]== 3) {
                                                    $kind = 'Pembuatan Akun G-Suite';
                                                } else if ($data["name"] == 2) {
                                                    $kind = 'Lupa Password';
                                                } else {
                                                    $kind = 'Akun Ditangguhkan';
                                                }
                                            ?>
                                                <p class="collections-title"><?php echo $no; ?>. <?php echo $kind; ?> | <?php echo $data["tanggal"]; ?></p>
                                                <p class="collections-content">Problem : <?php echo $data['email']; ?></p>
                                            </div>
                                            <div class="col s3">
                                            <?php if($data['status'] == "open" ){
                                                 echo "<span class='task-cat pink'>Tiket $data[status]</span>";
                                                 } else if ($data['status'] == "close"){
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
                            <div class="col s12 m12 l6">
                                <ul id="issues-collection" class="collection">
                                    <li class="collection-item avatar">
                                        <i class="mdi-file-folder circle light-blue darken-2"></i>
                                        <span class="collection-header">Task Tiket</span>
                                        <p>Status <b style="color: blue;">Close</b></p>
                                        <!-- <a href="#" class="secondary    -content"><i class="mdi-action-grade"></i></a> -->
                                    </li>
                    <?php
                    $tanggal = date("Y-m-d");
                    $query1= "SELECT * FROM tiket_gsuite WHERE status='close' limit 7";
                    $tampil1=mysqli_query($koneksi, $query1) or die(mysqli_error());
                    ?>
                    <?php
                    $no=0;
                    while($data1=mysqli_fetch_array($tampil1))
                    { $no++; ?>
                                    <li class="collection-item">
                                        <div class="row">
                                            <div class="col s9">
                                            <?php
                                                $kind = '';
                                                if ($data1["name"]== 3) {
                                                    $kind = 'Pembuatan Akun G-Suite';
                                                } else if ($data1["name"] == 2) {
                                                    $kind = 'Lupa Password';
                                                } else {
                                                    $kind = 'Akun Ditangguhkan';
                                                }
                                            ?>
                                                <p class="collections-title"><?php echo $no; ?>. <?php echo $kind; ?> | <?php echo $data1['tanggal']; ?></p>
                                                <p class="collections-content">Problem : <?php echo $data1['email']; ?></p>
                                            </div>
                                            <div class="col s3">
                                            <?php if($data1['status'] == "open" ){
                                                 echo "<span class='task-cat pink'>Tiket $data1[status]</span>";
                                                 } else if ($data1['status'] == "close"){
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

            <!-- //////////////////////////////////////////////////////////////////////////// -->
            <!-- START RIGHT SIDEBAR NAV-->
            <aside id="right-sidebar-nav">
                <ul id="chat-out" class="side-nav rightside-navigation">
                    <li class="li-hover">
                    <a href="#" data-activates="chat-out" class="chat-close-collapse right"><i class="mdi-navigation-close"></i></a>
                    <div id="right-search" class="row">
                        <form class="col s12">
                            <div class="input-field">
                                <i class="mdi-action-search prefix"></i>
                                <input id="icon_prefix" type="text" class="validate">
                                <label for="icon_prefix">Search</label>
                            </div>
                        </form>
                    </div>
                    </li>
                    <li class="li-hover">
                        <ul class="chat-collapsible" data-collapsible="expandable">
                        <li>
                            <div class="collapsible-header teal white-text active"><i class="mdi-social-whatshot"></i>Recent Activity</div>
                            <div class="collapsible-body recent-activity">
                                <div class="recent-activity-list chat-out-list row">
                                    <div class="col s3 recent-activity-list-icon"><i class="mdi-action-add-shopping-cart"></i>
                                    </div>
                                    <div class="col s9 recent-activity-list-text">
                                        <a href="#">just now</a>
                                        <p>Jim Doe Purchased new equipments for zonal office.</p>
                                    </div>
                                </div>
                                <div class="recent-activity-list chat-out-list row">
                                    <div class="col s3 recent-activity-list-icon"><i class="mdi-device-airplanemode-on"></i>
                                    </div>
                                    <div class="col s9 recent-activity-list-text">
                                        <a href="#">Yesterday</a>
                                        <p>Your Next flight for USA will be on 15th August 2015.</p>
                                    </div>
                                </div>
                                <div class="recent-activity-list chat-out-list row">
                                    <div class="col s3 recent-activity-list-icon"><i class="mdi-action-settings-voice"></i>
                                    </div>
                                    <div class="col s9 recent-activity-list-text">
                                        <a href="#">5 Days Ago</a>
                                        <p>Natalya Parker Send you a voice mail for next conference.</p>
                                    </div>
                                </div>
                                <div class="recent-activity-list chat-out-list row">
                                    <div class="col s3 recent-activity-list-icon"><i class="mdi-action-store"></i>
                                    </div>
                                    <div class="col s9 recent-activity-list-text">
                                        <a href="#">Last Week</a>
                                        <p>Jessy Jay open a new store at S.G Road.</p>
                                    </div>
                                </div>
                                <div class="recent-activity-list chat-out-list row">
                                    <div class="col s3 recent-activity-list-icon"><i class="mdi-action-settings-voice"></i>
                                    </div>
                                    <div class="col s9 recent-activity-list-text">
                                        <a href="#">5 Days Ago</a>
                                        <p>Natalya Parker Send you a voice mail for next conference.</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header light-blue white-text active"><i class="mdi-editor-attach-money"></i>Sales Repoart</div>
                            <div class="collapsible-body sales-repoart">
                                <div class="sales-repoart-list  chat-out-list row">
                                    <div class="col s8">Target Salse</div>
                                    <div class="col s4"><span id="sales-line-1"></span>
                                    </div>
                                </div>
                                <div class="sales-repoart-list chat-out-list row">
                                    <div class="col s8">Payment Due</div>
                                    <div class="col s4"><span id="sales-bar-1"></span>
                                    </div>
                                </div>
                                <div class="sales-repoart-list chat-out-list row">
                                    <div class="col s8">Total Delivery</div>
                                    <div class="col s4"><span id="sales-line-2"></span>
                                    </div>
                                </div>
                                <div class="sales-repoart-list chat-out-list row">
                                    <div class="col s8">Total Progress</div>
                                    <div class="col s4"><span id="sales-bar-2"></span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header red white-text"><i class="mdi-action-stars"></i>Favorite Associates</div>
                            <div class="collapsible-body favorite-associates">
                                <div class="favorite-associate-list chat-out-list row">
                                    <div class="col s4"><img src="images/avatar.jpg" alt="" class="circle responsive-img online-user valign profile-image">
                                    </div>
                                    <div class="col s8">
                                        <p>Eileen Sideways</p>
                                        <p class="place">Los Angeles, CA</p>
                                    </div>
                                </div>
                                <div class="favorite-associate-list chat-out-list row">
                                    <div class="col s4"><img src="images/avatar.jpg" alt="" class="circle responsive-img online-user valign profile-image">
                                    </div>
                                    <div class="col s8">
                                        <p>Zaham Sindil</p>
                                        <p class="place">San Francisco, CA</p>
                                    </div>
                                </div>
                                <div class="favorite-associate-list chat-out-list row">
                                    <div class="col s4"><img src="images/avatar.jpg" alt="" class="circle responsive-img offline-user valign profile-image">
                                    </div>
                                    <div class="col s8">
                                        <p>Renov Leongal</p>
                                        <p class="place">Cebu City, Philippines</p>
                                    </div>
                                </div>
                                <div class="favorite-associate-list chat-out-list row">
                                    <div class="col s4"><img src="images/avatar.jpg" alt="" class="circle responsive-img online-user valign profile-image">
                                    </div>
                                    <div class="col s8">
                                        <p>Weno Carasbong</p>
                                        <p>Tokyo, Japan</p>
                                    </div>
                                </div>
                                <div class="favorite-associate-list chat-out-list row">
                                    <div class="col s4"><img src="images/avatar.jpg" alt="" class="circle responsive-img offline-user valign profile-image">
                                    </div>
                                    <div class="col s8">
                                        <p>Nusja Nawancali</p>
                                        <p class="place">Bangkok, Thailand</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                        </ul>
                    </li>
                </ul>
            </aside>
            <!-- LEFT RIGHT SIDEBAR NAV-->

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