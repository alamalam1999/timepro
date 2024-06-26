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

            $month = 0;
            $year = 0;

            if (isset($_POST['update'])) {
                $month = $_POST['month'];
                $year  = $_POST['year'];
            }

            $currentYear = ($year != 0) ? $year : date("Y");
            $currentMonth = ($month != 0) ? $month : date("n");

            ?>

            <!--breadcrumbs start-->
            <div id="breadcrumbs-wrapper" class=" white lighten-3">
                <div class="container">

                    <div class="col s12 m12 l12">
                        <h5 class="breadcrumbs-title">TimePRO</h5>
                        <ol class="breadcrumb">
                            <li><a href="input_calendar_project.php">Kalender Kerja</a></li>
                        </ol>
                    </div>

                </div>
            </div>
            <!--breadcrumbs end-->
            <div class="container">
                <?php
                // Function to get the name of the month
                function getMonthName($month)
                {
                    $monthNames = [
                        "January", "February", "March", "April", "May", "June",
                        "July", "August", "September", "October", "November", "December"
                    ];
                    return $monthNames[$month - 1];
                }

                // Function to generate the calendar
                function generateCalendar($year, $month, $highlightedDays, $highlightedMonth, $highlightedYear)
                {
                    echo "<h2>" . getMonthName($month) . " " . $year . "</h2>";
                    echo "<table>";
                    echo "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";

                    $firstDay = mktime(0, 0, 0, $month, 1, $year);
                    $totalDays = date("t", $firstDay);
                    $numDayOfWeek = date("w", $firstDay);

                    echo "<tr>";
                    // Display blank cells for days before the first day of the month
                    for ($i = 0; $i < $numDayOfWeek; $i++) {
                        echo "<td>kosong</td>";
                    }

                    // Display the days of the month
                    for ($day = 1; $day <= $totalDays; $day++) {
                        $highlightClass = "";
                        if (in_array($day, $highlightedDays) && in_array($month, $highlightedMonth) && in_array($year, $highlightedYear)) {
                            $highlightClass = 'bgcolor="#2f86c5"';
                        }
                        echo "<td $highlightClass>$day</td>";

                        if (++$numDayOfWeek == 7) {
                            echo "</tr><tr>";
                            $numDayOfWeek = 0;
                        }
                    }

                    // Fill in remaining blank cells in the last row
                    while ($numDayOfWeek > 0 && $numDayOfWeek < 7) {
                        echo "<td>kosong</td>";
                        $numDayOfWeek++;
                    }

                    echo "</tr>";
                    echo "</table>";
                }

                // Database connection
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "employee_tiket";

                $koneksi = mysqli_connect($servername, $username, $password, $dbname);

                // Check connection
                if (!$koneksi) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Fetch highlighted days from the database
                $highlightedDays = [];
                $highlightedMonth = [];
                $highlightedYear = [];
                $result = mysqli_query($koneksi, "SELECT * FROM tiket");
                while ($row = mysqli_fetch_assoc($result)) {
                    $highlightedDays[] = date('d', strtotime($row['waktu_close']));
                    $highlightedMonth[] = date('m', strtotime($row['waktu_close']));
                    $highlightedYear[] = date('Y', strtotime($row['waktu_close']));
                }

                // Display the calendar for the current month
                generateCalendar($currentYear, $currentMonth, $highlightedDays, $highlightedMonth, $highlightedYear);

                // Close database connection
                mysqli_close($koneksi);
                ?>
            </div>
            <div class="container">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col"><input type="number" name="month" min="1" max="12" step="1" value="<?php echo date("m") ?>" require></div>
                        <div class="col"><input type="number" name="year" min="1900" max="2099" step="1" value="<?php echo date("Y") ?>" require></div>
                        <div> <button class="btn cyan" type="submit" name="update" id="update">Check</button></div>
                    </div>
                </form>
            </div>
            <div>
                <input type="hidden" id="extra_search" value="<?php echo $_SESSION['username']; ?>">
            </div>
            <div class="container" style="padding-bottom: 56px;">
                <h3>List Job Scheduler</h3>
                <div class="col-lg-12" style="margin-top: 40px;">
                    <table id="lookup" class="table table-bordered table-hover">
                        <thead bgcolor="eeeeee" align="center">
                            <tr>
                                <th>Nama Pekerjaan</th>
                                <th>Detail Pekerjaan</th>
                                <th>Due Date</th>
                                <th>Lampiran</th>
                                <th>Departemen</th>
                                <th>Pic</th>
                                <th>Status</th>
                                <th>Edit Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

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


        <!-- Javascript Libs -->
        <script type="text/javascript" src="../js/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="../datatables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="../datatables/dataTables.bootstrap.min.js"></script>
        <script type="text/javascript" src="../dist/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {

                var dataTable = $('#lookup').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: "ajax-grid-data1.php", // json datasource
                        type: "post", // method  , by default get
                        data: function(d) {
                            // Add extra search parameter to the AJAX request
                            d.extra_search = $('#extra_search').val();
                            console.log('extra_search:', d.extra_search);
                        },
                        error: function(xhr, textStatus, errorThrown) { // error handling
                            $(".lookup-error").html("");
                            $("#lookup").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                            $("#lookup_processing").css("display", "none");
                            console.error('An error occurred: test', xhr);
                        }
                    }
                });
            });
        </script>
    </body>

    </html>