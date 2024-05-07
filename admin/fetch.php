<?php

include "../conn.php";

$tanggal = date("Y-m-d");
//  $tampil2=mysqli_query($koneksi, "select * from tiket where tanggal='$tanggal' and status='open'");
$tampil2 = mysqli_query($koneksi, "SELECT id_tiket FROM `tiket_gsuite` WHERE status = 'new' and tanggal ='$tanggal'  UNION SELECT id_tiket FROM `tiket` WHERE status = 'new' and tanggal ='$tanggal'");
$total2 = mysqli_num_rows($tampil2);

// $status_query = "SELECT * FROM comments WHERE comment_status=0";
// $result_query = mysqli_query($con, $status_query);
$count = mysqli_num_rows($tampil2);
$data = array(
    'unseen_notification'  => $count
);

echo json_encode($data);
