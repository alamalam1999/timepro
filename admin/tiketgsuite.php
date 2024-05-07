<?php 
session_start();
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

            <!--breadcrumbs start-->
            <div id="breadcrumbs-wrapper" class=" grey lighten-3">
                <div class="container">
                    <div class="row">
                        <div class="col s12 m12 l12">
                            <h5 class="breadcrumbs-title">Helpdesk G-Suite</h5>
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
                <!-- <div class="section">

                  <p class="caption">Tables are a nice way to organize a lot of data. We provide a few utility classes to help you style your table as easily as possible. In addition, to improve mobile experience, all tables on mobile-screen widths are centered automatically.</p>
                  <div class="divider"></div> -->

                <!--DataTables example-->
                <?php
                if(isset($_GET['aksi']) == 'delete'){
                    $id = $_GET['id'];
                    $cek = mysqli_query($koneksi, "SELECT * FROM tiket_gsuite WHERE id_tiket='$id'");
                    if(mysqli_num_rows($cek) == 0){
                        echo '<script>sweetAlert({
	                                                   title: "Ups!", 
                                                        text: "Data tiket tidak ditemukan!", 
                                                        type: "error"
                                                        });</script>';
                    }else{
                        $delete = mysqli_query($koneksi, "DELETE FROM tiket_gsuite WHERE id_tiket='$id'");
                        if($delete){
                            echo '<script>sweetAlert({
	                                                   title: "Berhasil!", 
                                                        text: "Data Berhasil di hapus!", 
                                                        type: "success"
                                                        });</script>';
                        }else{
                            echo '<script>sweetAlert({
	                                                   title: "Gagal!", 
                                                        text: "Data gagal di hapus!", 
                                                        type: "error"
                                                        });</script>';
                        }
                    }
                }
                ?>
                <div id="table-datatables">
                    <h4 class="header"></h4>
                    <!-- <a href="input-tiket.php" class="btn-floating btn-small waves-effect waves-light green darken-2" title="Tambah Tiket"><i class="mdi-content-add"></i></a>-->
                    <a href="tiket-export-xls.php" class="btn-floating btn-small waves-effect waves-light orange darken-2" title="Export Excel"><i class="mdi-content-content-copy"></i></a>
                    <br /><br />
                    <div class="row">
                        <div class="col s12 m12">
                            <table id="lookup" class="display" style="width:50%">
                                <thead>
                                <tr>

                                    <th>Id Tiket</th>
                                    <th>Tanggal</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Lokasi</th>
                                    <th>Action</th>
                                    


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
        var dataTable = $('#lookup').DataTable( {
            "autoWidth": false,
            "columnDefs": [
                { "width": "20%", "targets": 0 }
            ],
            "processing": true,
            "serverSide": true,
            "ajax":{
                url :"ajax-grid-datagsuite.php", // json datasource
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".lookup-error").html("");
                    $("#lookup").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#lookup_processing").css("display","none");

                }
            }
        } );
    } );
</script>

</body>

</html>