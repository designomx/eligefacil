<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/utilities.php');
require_once('../phpTools/SimpleImage.php');
require_once('../phpTools/uploadFile.php');


mysql_select_db($database, $dbConn);

$transaccion = $_POST['transaccion'];

switch($transaccion){

	case 'INSERT':

				// Obtiene de la base de datos el último id de las empresas ott
				$query_newId = "SELECT (MAX(id_empresa) + 1) as newId FROM empresas_ott";
				$newId = mysql_query($query_newId, $dbConn) or die(mysql_error());
				$row_newId = mysql_fetch_assoc($newId);
				
				$id_empresa = $row_newId["newId"];

				//$dir_empresa = "../../uploads/empresas_ott/" . $id_empresa . "/";
				$dir_empresa = "../../uploads/empresas_ott/" . $id_empresa;
	
				$filename = NULL;

				if($_FILES['logo']['tmp_name'] != NULL){

					//$filename = uploadImage("logo", $dir_empresa, 278, 52);
					$filename = uploadImage2("logo", $dir_empresa);
					
				}

				$sql = sprintf("INSERT INTO empresas_ott(nombre, logo) VALUES(%s, %s)",																																																																																
								 GetSQLValueString(utf8_decode($_POST['nombre']), "text"),
								 GetSQLValueString($filename, "text"));
				
				break;
				
	case 'UPDATE':
	
				//$dir_empresa = "../../uploads/empresas_ott/" . $_POST['id_empresa'] . "/";
				$dir_empresa = "../../uploads/empresas_ott/" . $_POST['id_empresa'];
	
				$filename = NULL;

				if($_FILES['logo']['tmp_name'] != NULL){

					//$filename = uploadImage("logo", $dir_empresa, 278, 52);
					$filename = uploadImage2("logo", $dir_empresa);
					
				} else {
				
					$filename = $_POST['logo_actual'];
				
				}

				$sql = sprintf("UPDATE empresas_ott SET nombre=%s, logo=%s WHERE id_empresa=%s",																																																																																	
								 GetSQLValueString(utf8_decode($_POST['nombre']), "text"),
								 GetSQLValueString($filename, "text"),
								 GetSQLValueString($_POST['id_empresa'], "int"));
				
				break;
}// switch

$result = mysql_query($sql, $dbConn) or die(mysql_error());

mysql_free_result($result);

?>