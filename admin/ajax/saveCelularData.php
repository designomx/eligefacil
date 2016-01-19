<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/utilities.php');
require_once('../phpTools/SimpleImage.php');
require_once('../phpTools/uploadFile.php');

mysql_select_db($database, $dbConn);

$transaccion = $_POST['transaccion'];

switch($transaccion){

	case 'INSERT':
	
				// Obtiene de la base de datos el último id de los celulares
				$query_newId = "SELECT (MAX(id_celular) + 1) as newId FROM celularesMasPopulares";
				$newId = mysql_query($query_newId, $dbConn) or die(mysql_error());
				$row_newId = mysql_fetch_assoc($newId);
				
				$id_celular = $row_newId["newId"];

				$dir_celular = "../../uploads/celulares_mas_populares/" . $id_celular . "/";
	
				$filename = NULL;

				if($_FILES['foto']['tmp_name'] != NULL){

					$filename = uploadImage("foto", $dir_celular, 120, 248);
					
				}

				$sql = sprintf("INSERT INTO celularesMasPopulares(id_celular, nombre, foto) VALUES(%s, %s, %s)",																																																																																
								 GetSQLValueString($id_celular, "int"),
								 GetSQLValueString(utf8_decode($_POST['nombre']), "text"),
								 GetSQLValueString($filename, "text"));
				
				echo "$sql";
				
				break;
				
	case 'UPDATE':

				$dir_celular = "../../uploads/celulares_mas_populares/" . $_POST['id_celular'] . "/";
	
				$filename = NULL;

				if($_FILES['foto']['tmp_name'] != NULL){

					$filename = uploadImage("foto", $dir_celular, 120, 248);
					
				} else {
				
					$filename = $_POST['foto_actual'];
				
				}

				$sql = sprintf("UPDATE celularesMasPopulares SET nombre=%s, foto=%s WHERE id_celular=%s",																																																																																	
								 GetSQLValueString(utf8_decode($_POST['nombre']), "text"),
								 GetSQLValueString($filename, "text"),
								 GetSQLValueString($_POST['id_celular'], "int"));
				
				break;
}// switch

$result = mysql_query($sql, $dbConn) or die(mysql_error());

mysql_free_result($result);

?>