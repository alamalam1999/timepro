<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['username'])) {
    header('location:../index.php');
} else {
    include "../conn.php";
    date_default_timezone_set('Asia/Jakarta');
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
                $no_hp     = $_POST['no_hp'];
                $nama      = $_SESSION['username'];
                $email     = "adialamalam@gmail.com";
                $due_date  = $_POST['due_date'];
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
                $laporan .= "<td>No Hp</td><td>:</td><td>$no_hp</td>";
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
                $laporan .= "<tr>";
                $laporan .= "<td>Status/td><td>:</td><td>$due_date</td>";
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
                    $cek = mysqli_query($koneksi, "SELECT * FROM tiket WHERE id_tiket='$id_tiket'");
                    if (mysqli_num_rows($cek) == 0) {
                        $insert = mysqli_query($koneksi, "INSERT INTO tiket(id_tiket,waktu, tanggal, no_hp, nama, email, departemen, problem, penanganan, status, filename,waktu_close)
                                                            VALUES('$id_tiket','$waktu','$tanggal','$no_hp','$nama','$email','$departemen','$problem','$none','$open','$filename','$due_date')");
                        if ($insert) {
                            move_uploaded_file($tempname, $folder);
                            echo '<script>sweetAlert({
                                                           title: "Berhasil tersimpan!", 
                                                            text: "Cek Kalender!", 
                                                            type: "success"
                                                            });</script>';
                        } else {
                            echo '<script>sweetAlert({
                                                           title: "Gagal Tersimpan!", 
                                                            text: "Coba Lagi!", 
                                                            type: "error"
                                                            });</script>';
                        }
                    } else {
                        echo '<script>sweetAlert({
                                                           title: "Gagal Tersimpan!", 
                                                            text: "Sudah ada Sebelumnya!", 
                                                            type: "error"
                                                            });</script>';
                    }
                }
            }
            ?>
            <div class="container">

                <form class="cd-form floating-labels" method="POST" enctype="multipart/form-data" action="input_project.php">

                    <legend>
                        <strong>
                            <h3>Input Perkerjaan</h3>
                        </strong>
                    </legend>

                    <fieldset>

                        <input type="hidden" name="id_tiket" value="<?php echo date("dmYHis"); ?>" id="id_ticket" />
                        <input type="hidden" name="waktu" value="<?php echo date("Y-m-d H:i:s"); ?>" id="waktu" />
                        <input type="hidden" name="tanggal" value="<?php echo date("Y-m-d"); ?>" id="tanggal" />

                        <div class="icon">
                            <label class="cd-label" style="font-size:13px;" for="no_hp">Nama Pekerjaan</label>
                            <input class="company" type="text" name="no_hp" id="no_hp" autocomplete="off" required="required">
                        </div>

                        <div class="icon">
                            <label class="cd-label" style="font-size:13px;">Deskripsi Pekerjaan</label>
                            <textarea class="message" name="problem" id="problem" required style="widht: 964px; height: 73px; margin-bottom: 10px;"></textarea>
                        </div>

                        <div class="icon">
                            <label class="cd-label" style="font-size:13px;">Due Date Pekerjaan</label>
                            <input type="datetime-local" name="due_date" id="due_date" required </div>

                            <div style="margin-bottom: 70px;">
                                <label for="cd-textarea" style="font-size:13px;">Lampiran Pekerjaan</label>
                                <input name="choosefile" type="file" class="form-control" id="customFile" />
                            </div>

                            <legend>
                                <strong>
                                    <h3>Disposisi Perkerjaan</h3>
                                </strong>
                            </legend>

                            <div class="icon">
                                <label class="cd-label" style="font-size:13px; " for="cd-email">Departemen terkait pekerjaan</label>
                                <select class="email" name="departemen" id="departemen" required>
                                    <option value=""></option>
                                    <option value="Research and Development">Research and Development</option>
                                    <option value="Human Resources">Human Resources</option>
                                    <option value="General Affair">General Affair</option>
                                    <option value="Accounting & Tax">Accounting & Tax</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Building Maintenance">Building & Maintenance</option>
                                    <option value="Building Maintenance">Branding & Marketing</option>
                                    <option value="Transformasi Digital">Transformasi Digital (IT)</option>
                                    <option value="KB Avicenna Pamulang">KB Avicenna Pamulang</option>
                                    <option value="TK Avicenna Jagakarsa">TK Avicenna Jagakarsa</option>
                                    <option value="SD Avicenna Jagakarsa">SD Avicenna Jagakarsa</option>
                                    <option value="SMP Avicenna Jagakarsa">SMP Avicenna Jagakarsa</option>
                                    <option value="SMA Avicenna Jagakarsa">SMA Avicenna Jagakarsa</option>
                                    <option value="SD Avicenna Cinere">SD Avicenna Cinere</option>
                                    <option value="SMP Avicenna Cinere">SMP Avicenna Cinere</option>
                                    <option value="SMA Avicenna Cinere">SMA Avicenna Cinere</option>
                                </select>
                            </div>


                            <div class="row">
                                <div class="input-field col s12">
                                    <!-- <i class="mdi-action-lock-outline prefix"></i> -->
                                    <select name="pic" id="pic" required>
                                        <option value="kosong"> Pilih</option>
                                        <?php
                                        $user = mysqli_query($koneksi, "SELECT * from user");
                                        while ($row = mysqli_fetch_array($user)) {
                                        ?>
                                            <option value="<?php echo $row['username'] ?>"><?php echo $row['username'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="Pic" style="font-size:13px;">PIC yang menangani</label>
                                </div>
                            </div>



                            <div>
                                <input type="submit" onclick="notifikasi()" name="input" id="input" value="Submit">
                            </div>
                    </fieldset>

                </form>

            </div>
            <script src="js/main.js"></script> <!-- Resource jQuery -->

    </html>
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
                var notifikasi = new Notification('TimePRO Tiket', {
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
    </body>

    </html>