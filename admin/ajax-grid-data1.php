<?php
/* Database connection start */
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_tiket";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;

$columns = array(
	// datatable column index  => database column name


	0 => 'nama_pekerjaan',
	1 => 'problem',

	2 => 'waktu_close',
	3 => 'filename',

	4 => 'departemen',
	5 => 'pic',
	6 => 'status',
	7 => 'edit'
);

// getting total number records without any search
$sql = "SELECT id_tiket, tanggal,waktu_close, nama_pekerjaan, nama, email, departemen, problem, status, filename, pic";
$sql .= " FROM tiket where nama = '" . $requestData['extra_search'] . "'";
$query = mysqli_query($conn, $sql) or die("ajax-grid-data.php: get Tiket");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if (!empty($requestData['search']['value'])) {
	// if there is a search parameter
	$sql = "SELECT id_tiket, tanggal,waktu_close, nama_pekerjaan, nama, email, departemen, problem, status, filename, pic";
	$sql .= " FROM tiket";
	$sql .= " WHERE nama = 'admin'";    // $requestData['search']['value'] contains search parameter
	$sql .= " AND ( id_tiket LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR tanggal LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR nama_pekerjaan LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR email LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR departemen LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR problem LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR status LIKE '%" . $requestData['search']['value'] . "%') ";
	$query = mysqli_query($conn, $sql) or die("ajax-grid-data.php: get Tiket");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 

	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	$query = mysqli_query($conn, $sql) or die("ajax-grid-data.php: get Tiket"); // again run query with limit

} else {

	$sql = "SELECT id_tiket, tanggal,waktu_close, nama_pekerjaan, nama, email, departemen, problem, status, filename, pic";
	$sql .= " FROM tiket where nama = '" . $requestData['extra_search'] . "'";
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
	$query = mysqli_query($conn, $sql) or die("ajax-grid-data.php: get Tiket");
}

$data = array();
while ($row = mysqli_fetch_array($query)) {  // preparing an array
	$nestedData = array();


	$nestedData[] = $row["nama_pekerjaan"];
	$nestedData[] = $row["problem"];

	$nestedData[] = $row["waktu_close"];
	$nestedData[] = $row["filename"];

	$nestedData[] = $row["departemen"];
	$nestedData[] = $row["pic"];
	$nestedData[] = $row["status"];

	$nestedData[] = '<td><center>
                     <a href="edit-tiket.php?id=' . $row['id_tiket'] . '" style="color:#eee;"  data-toggle="tooltip" title="Edit" class="btn-floating waves-effect waves-light light-blue darken-3"><i class="mdi-editor-mode-edit"></i> </a>
				     <a href="tiket.php?aksi=delete&id=' . $row['id_tiket'] . '"  data-toggle="tooltip" title="Delete" onclick="return confirm(\'Anda yakin akan menghapus data ' . $row['id_tiket'] . '?\')" class="btn-floating waves-effect waves-light red"><i class="mdi-action-delete"></i> </a>
	                 </center></td>';
	$data[] = $nestedData;
}

$json_data = array(
	"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
	"recordsTotal"    => intval($totalData),  // total number of records
	"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
	"data"            => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
