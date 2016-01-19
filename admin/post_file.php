<?php

require_once('../connection/dbConn.php');
require_once('phpTools/utilities.php'); // Added by Juan Luis Almazo - May 9, 2014.

// If you want to ignore the uploaded files, 
// set $demo_mode to true;

$demo_mode = false;
//$upload_dir = 'uploads/';
$upload_dir = '../uploads/carrusel/'; // Modified by Juan Luis Almazo - May 7, 2014.
$allowed_ext = array('jpg','jpeg','png','gif');

if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
	exit_status('Error! Wrong HTTP method!');
}


if(array_key_exists('pic',$_FILES) && $_FILES['pic']['error'] == 0 ){
	
	$pic = $_FILES['pic'];

	if(!in_array(get_extension($pic['name']),$allowed_ext)){
		exit_status('Only '.implode(',',$allowed_ext).' files are allowed!');
	}	

	if($demo_mode){
		
		// File uploads are ignored. We only log them.
		
		$line = implode('		', array( date('r'), $_SERVER['REMOTE_ADDR'], $pic['size'], $pic['name']));
		file_put_contents('log.txt', $line.PHP_EOL, FILE_APPEND);
		
		exit_status('Uploads are ignored in demo mode.');
	}
	
	
	// Move the uploaded file from the temporary 
	// directory to the uploads folder:
	
	/****************************************************************/
	// if directory doesn't exist, it is created. Added by Juan Luis Almazo - May 7, 2014.
	if(!file_exists($upload_dir)){
		mkdir($upload_dir, 0777, true);
	}
	
	$cleanFileName = cleanForShortURL($pic['name']);
	/****************************************************************/
	
	//if(move_uploaded_file($pic['tmp_name'], $upload_dir.$pic['name'])){
	if(move_uploaded_file($pic['tmp_name'], $upload_dir.$cleanFileName)){ // Modified by Juan Luis Almazo - May 8, 2014.
		
		/****************************************************************/
		// Added by Juan Luis Almazo - May 8, 2014.
		// Write images data into database.
		
		mysql_select_db($database, $dbConn);
		
		$insertSQL = sprintf("INSERT INTO imagenesCarrusel (filename, orden) SELECT %s, IFNULL((MAX(orden) + 1), 1) FROM imagenesCarrusel; /*SELECT LAST_INSERT_ID();*/",
		             GetSQLValueString($cleanFileName, "text"));
	
		$result = mysql_query($insertSQL, $dbConn) or die(mysql_error());
		
		$query = "SELECT MAX(id_imagen) FROM imagenesCarrusel";	
		$result = mysql_query($query, $dbConn) or die(mysql_error());
		
		$row_id = mysql_fetch_assoc($result);
		$id = $row_id['MAX(id_imagen)']; 		
		/****************************************************************/
		
		exit_status('File was uploaded successfuly!', $id);
	}
	
}

exit_status('Something went wrong with your upload!');


// Helper functions

function exit_status($str, $id){
	echo json_encode(array('status'=>$str, 'id'=>$id));
	exit;
}

function get_extension($file_name){
	$ext = explode('.', $file_name);
	$ext = array_pop($ext);
	return strtolower($ext);
}
?>