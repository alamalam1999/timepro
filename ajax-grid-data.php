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
	0 => 'waktu',
	1 => 'tanggal',
	2 => 'pc_no',
	3 => 'nama',
	4 => 'departemen',
	5 => 'problem',
	6 => 'status',
	7 => 'pic',
	8 => 'penanganan'
);

// getting total number records without any search
$sql = "SELECT id_tiket,waktu, tanggal, pc_no, nama, departemen, problem, status, pic, penanganan";
$sql .= " FROM tiket";
$query = mysqli_query($conn, $sql) or die("ajax-grid-data.php: get Tiket");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if (!empty($requestData['search']['value'])) {
	// if there is a search parameter
	$sql = "SELECT id_tiket, waktu, tanggal, pc_no, nama, departemen, problem, status, pic, penanganan";
	$sql .= " FROM tiket";
	$sql .= " WHERE id_tiket LIKE '" . $requestData['search']['value'] . "%' ";    // $requestData['search']['value'] contains search parameter
	$sql .= " OR waktu LIKE '" . $requestData['search']['value'] . "%' ";
	$sql .= " OR tanggal LIKE '" . $requestData['search']['value'] . "%' ";
	$sql .= " OR pc_no LIKE '" . $requestData['search']['value'] . "%' ";
	$sql .= " OR nama LIKE '" . $requestData['search']['value'] . "%' ";
	$sql .= " OR departemen LIKE '" . $requestData['search']['value'] . "%' ";
	$sql .= " OR problem LIKE '" . $requestData['search']['value'] . "%' ";
	$sql .= " OR status LIKE '" . $requestData['search']['value'] . "%' ";
	$sql .= " OR pic LIKE '" . $requestData['search']['value'] . "%'  ";
	$sql .= " OR pic Like '" . $requestData['search']['value'] . "%'  ";
	$query = mysqli_query($conn, $sql) or die("ajax-grid-data.php: get Tiket");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 

	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	$query = mysqli_query($conn, $sql) or die("ajax-grid-data.php: get Tiket"); // again run query with limit

} else {

	$sql = "SELECT  id_tiket,waktu, tanggal, pc_no, nama, departemen, problem, status, pic, penanganan";
	$sql .= " FROM tiket";
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
	$query = mysqli_query($conn, $sql) or die("ajax-grid-data.php: get Tiket");
}

$data = array();
while ($row = mysqli_fetch_array($query)) {  // preparing an array
	$nestedData = array();

	$nestedData[] = $row["waktu"];
	$nestedData[] = $row["tanggal"];
	$nestedData[] = $row["pc_no"];
	$nestedData[] = $row["nama"];
	$nestedData[] = $row["departemen"];
	$nestedData[] = $row["problem"];
	$nestedData[] = $row["status"];
	$nestedData[] = $row["pic"];
	$nestedData[] = $row["penanganan"];


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
