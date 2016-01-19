<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/utilities.php');

mysql_select_db($database, $dbConn);

$transaccion = $_POST['transaccion'];

switch($transaccion){

	case 'INSERT':

				$sql = sprintf("INSERT INTO empresas(nombre, codigo_color) VALUES(%s, %s)",																																																																																
								 GetSQLValueString(utf8_decode($_POST['nombre']), "text"),
								 GetSQLValueString(utf8_decode($_POST['codigo_color']), "text"));
				
				break;
				
	case 'UPDATE':

				$sql = sprintf("UPDATE empresas SET nombre=%s, codigo_color=%s WHERE id_empresa=%s",																																																																																	
								 GetSQLValueString(utf8_decode($_POST['nombre']), "text"),
								 GetSQLValueString(utf8_decode($_POST['codigo_color']), "text"),
								 GetSQLValueString($_POST['id_empresa'], "int"));
				
				break;
}// switch

$result = mysql_query($sql, $dbConn) or die(mysql_error());

mysql_free_result($result);

?>