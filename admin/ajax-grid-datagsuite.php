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

// <th>Id Tiket</th>
// <th>Tanggal</th>
// <th>Name</th>
// <th>Email</th>
// <th>Status</th>

$columns = array(
	// datatable column index  => database column name
	0 => 'id_tiket',
	1 => 'tanggal',
	2 => 'name',
	3 => 'email',
	4 => 'status',
	5 => 'lokasi'
);

// getting total number records without any search
$sql = "SELECT id_tiket, tanggal, name,email, status, lokasi";
$sql .= " FROM tiket_gsuite";
$query = mysqli_query($conn, $sql) or die("ajax-grid-data.php: get Tiket");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if (!empty($requestData['search']['value'])) {
	// if there is a search parameter
	$sql = "SELECT id_tiket, tanggal, name,email, status, lokasi";
	$sql .= " FROM tiket_gsuite";
	$sql .= " WHERE id_tiket LIKE '" . $requestData['search']['value'] . "%' ";    // $requestData['search']['value'] contains search parameter
	$sql .= " OR tanggal LIKE '" . $requestData['search']['value'] . "%' ";
	$sql .= " OR name LIKE '" . $requestData['search']['value'] . "%' ";
	$sql .= " OR email LIKE '" . $requestData['search']['value'] . "%' ";
	$sql .= " OR status LIKE '" . $requestData['search']['value'] . "%' ";
	$sql .= " OR lokasi LIKE '" . $requestData['search']['value'] . "%' ";
	$query = mysqli_query($conn, $sql) or die("ajax-grid-data.php: get Tiket");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	$query = mysqli_query($conn, $sql) or die("ajax-grid-data.php: get Tiket"); // again run query with limit

} else {
	$sql = "SELECT id_tiket, tanggal, name,email, status, lokasi";
	$sql .= " FROM tiket_gsuite";
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
	$query = mysqli_query($conn, $sql) or die("ajax-grid-data.php: get Tiket");
}

$data = array();
while ($row = mysqli_fetch_array($query)) {  // preparing an array

	$idtikettest = $row["id_tiket"];
	$sqlcheckimage = "select filename from tiket_gsuite where id_tiket = '$idtikettest'";
	$querytest = mysqli_query($conn, $sqlcheckimage);
	$nestedData = array();

	$nestedData[] = $row["id_tiket"];
	$nestedData[] = $row["tanggal"];

	$kind = '';
	if ($row["name"] == 1) {
		$kind = "Akun Ditangguhkan";
	} else if ($row["name"] == 2) {
		$kind = "Lupa Password";
	} else {
		$kind = "Pembuatan Akun G-Suite";
	}

	$nestedData[] = $kind;
	$nestedData[] = $row["email"];
	$nestedData[] = $row["status"];
	$nestedData[] = $row["lokasi"];



	$nestedData[] = '<td><center>
                     <a href="edit-tiket-gsuite.php?id=' . $row['id_tiket'] . '" style="color:#eee;"  data-toggle="tooltip" title="Edit" class="btn-floating waves-effect waves-light light-blue darken-3"><i class="mdi-editor-mode-edit"></i> </a>
				     <a href="tiketgsuite.php?aksi=delete&id=' . $row['id_tiket'] . '"  data-toggle="tooltip" title="Delete" onclick="return confirm(\'Anda yakin akan menghapus data ' . $row['id_tiket'] . '?\')" class="btn-floating waves-effect waves-light red"><i class="mdi-action-delete"></i> </a>
	                 </center></td>';


	//$nestedData[] = number_format($total,0,",",".");		

	$data[] = $nestedData;
}



$json_data = array(
	"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
	"recordsTotal"    => intval($totalData),  // total number of records
	"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
	"data"            => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
