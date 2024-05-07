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





            <?php


            if (isset($_POST['input'])) {

                $id_tiket  = $_POST['id_tiket'];
                $waktu     = $_POST['waktu'];
                $tanggal   = $_POST['tanggal'];
                $pc_no     = $_POST['pc_no'];
                $nama      = $_POST['nama'];
                $email     = $_POST['email'];
                $departemen = $_POST['departemen'];
                $problem   = $_POST['problem'];
                $filename  = $_FILES["choosefile"]["name"];
                $tempname  = $_FILES["choosefile"]["tmp_name"];
                $none      = "";
                $open      = "new";

                $folder = "image/" . $filename;

                $laporan = "<h4><b>Tiket Baru : $waktu</b></h4>";
                $laporan .= "<br/>";
                $laporan .= "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">";
                $laporan .= "<tr>";
                $laporan .= "<td>Tanggal</td><td>:</td><td>$tanggal</td>";
                $laporan .= "</tr>";
                $laporan .= "<tr>";
                $laporan .= "<td>PC NO</td><td>:</td><td>$pc_no</td>";
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
                $laporan .= "<td>Status/td><td>:</td><td>$open</td>";
                $laporan .= "</tr>";


                require_once("phpmailer/class.phpmailer.php");
                require_once("phpmailer/class.smtp.php");

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
                $sendmail->Subject = "Tiket IT Helpdesk $waktu"; //subjek email
                $sendmail->Body = $laporan; //isi pesan dalam format laporan
                if (!$sendmail->Send()) {
                    echo "Email gagal dikirim : " . $sendmail->ErrorInfo;
                } else {
                    //echo "Email berhasil terkirim!";  


                    $cek = mysqli_query($koneksi, "SELECT * FROM tiket WHERE id_tiket='$id_tiket'");
                    if (mysqli_num_rows($cek) == 0) {
                        $insert = mysqli_query($koneksi, "INSERT INTO tiket(id_tiket,waktu, tanggal, pc_no, nama, email, departemen, problem, penanganan, status, filename)
															VALUES('$id_tiket','$waktu','$tanggal','$pc_no','$nama','$email','$departemen','$problem','$none','$open','$filename')") or die(mysqli_error());
                        if ($insert) {
                            move_uploaded_file($tempname, $folder);
                            echo '<script>sweetAlert({
	                                                   title: "Keluhan berhasil dikirim!", 
                                                        text: "Cek email anda untuk mengetahui nomor tiket!", 
                                                        type: "success"
                                                        });</script>';
                        } else {
                            echo '<script>sweetAlert({
	                                                   title: "Gagal!", 
                                                        text: "Keluhan Gagal di kirim, silahakan coba lagi!", 
                                                        type: "error"
                                                        });</script>';
                        }
                    } else {
                        echo '<script>sweetAlert({
	                                                   title: "Gagal!", 
                                                        text: "Tiket Sudah ada Sebelumnya!", 
                                                        type: "error"
                                                        });</script>';
                    }
                }
            }
            ?>





            <script src="js/main.js"></script> <!-- Resource jQuery -->

            <!-- <script>
  sweetAlert("Hello world!");
  </script> -->


            <script>
                $(document).ready(function() {
                    if (Notification.permission !== "granted")
                        Notification.requestPermission();
                });

                function notifikasi() {
                    if (!Notification) {
                        alert('Browsermu tidak mendukung Web Notification.');
                        return;
                    }
                    if (Notification.permission !== "granted")
                        Notification.requestPermission();
                    else {
                        var notifikasi = new Notification('IT Helpdesk Tiket', {
                            icon: 'img/logo.jpg',
                            body: "Tiket Baru dari <?php echo $nama; ?>",
                        });
                        notifikasi.onclick = function() {
                            window.open("http://tsuchiya-mfg.com");
                        };
                        setTimeout(function() {
                            notifikasi.close();
                        }, 1000);
                    }
                };
            </script>


            <!--Start of Tawk.to Script-->
            <script type="text/javascript">
                var Tawk_API = Tawk_API || {},
                    Tawk_LoadStart = new Date();
                (function() {
                    var s1 = document.createElement("script"),
                        s0 = document.getElementsByTagName("script")[0];
                    s1.async = true;
                    s1.src = 'https://embed.tawk.to/63b244a647425128790b2078/1glo5ob15';
                    s1.charset = 'UTF-8';
                    s1.setAttribute('crossorigin', '*');
                    s0.parentNode.insertBefore(s1, s0);
                })();
            </script>
            <!--End of Tawk.to Script-->

    </html>

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