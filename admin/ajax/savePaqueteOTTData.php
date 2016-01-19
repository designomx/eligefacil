<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/utilities.php');

mysql_select_db($database, $dbConn);

$transaccion = $_POST['transaccion'];

switch($transaccion){

	case 'INSERT':
	
				$sql = sprintf("INSERT INTO paquetes_ott(nombre, id_empresa, precio, dato_principal_1, dato_principal_2, dato_principal_3, dato_principal_4, mas_datos) VALUES(%s, %s, %s, %s, %s, %s, %s, %s)",																																																																																
								 GetSQLValueString(utf8_decode($_POST['nombre']), "text"),
								 GetSQLValueString($_POST['id_empresa'], "int"),
								 GetSQLValueString($_POST['precio'], "double"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_1']), "text"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_2']), "text"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_3']), "text"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_4']), "text"),
								 GetSQLValueString(utf8_decode($_POST['mas_datos']), "text"));
				
				break;
				
	case 'UPDATE':

				$sql = sprintf("UPDATE paquetes_ott SET nombre=%s, id_empresa=%s, precio=%s, dato_principal_1=%s, dato_principal_2=%s, dato_principal_3=%s, dato_principal_4=%s, mas_datos=%s WHERE id_paquete=%s",
								 GetSQLValueString(utf8_decode($_POST['nombre']), "text"),																																																																																	
								 GetSQLValueString($_POST['id_empresa'], "int"),
								 GetSQLValueString($_POST['precio'], "double"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_1']), "text"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_2']), "text"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_3']), "text"),
								 GetSQLValueString(utf8_decode($_POST['dato_principal_4']), "text"),
								 GetSQLValueString(utf8_decode($_POST['mas_datos']), "text"),
								 GetSQLValueString($_POST['id_paquete'], "int"));
				
				break;
}// switch

$result = mysql_query($sql, $dbConn) or die(mysql_error());

mysql_free_result($result);

?>