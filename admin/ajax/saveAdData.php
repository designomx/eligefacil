<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/uploadFile.php');

mysql_select_db($database, $dbConn);

$id_anuncio = $_POST['id_anuncio'];
$url = $_POST['url'];
$dir_anuncio = "../../uploads/anuncios/" . $id_anuncio;


if($_FILES['imagen']['tmp_name'] != NULL){
		
	//include('phpTools/SimpleImage.php');
	//include('phpTools/uploadFile.php');

	// Delete current file from server.
	unlink($dir_anuncio . "/" . $_POST['imagen_actual']);
	//unlink($dir_anuncio . "/thumbs/" . $_POST['imagen_actual']);
	
	$filename = uploadImage("imagen", $dir_anuncio . "/");
	
	echo $filename;
			
} else {
	
	$filename = $_POST['imagen_actual']; // o sea, no subieron un nuevo archivo.
}

$sql = sprintf("UPDATE anuncios SET url=%s, imagen=%s WHERE id_anuncio=%s",
								GetSQLValueString($url, "text"),
								GetSQLValueString($filename, "text"),
								GetSQLValueString($id_anuncio, "int"));

$result = mysql_query($sql, $dbConn) or die(mysql_error());

mysql_free_result($result);

?>