<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/utilities.php');

mysql_select_db($database, $dbConn);

$transaccion = $_GET['transaccion'];
//$importe_pago = ($_GET['importe_pago'] == NULL) ? 0 : $_GET['importe_pago'];

switch($transaccion){

	case 'INSERT':

				//$sql = sprintf("INSERT INTO usuarios(email, nombre, password, id_permiso) VALUES(%s, %s, %s, %s)",
				$sql = sprintf("INSERT INTO usuarios(email, nombre, password) VALUES(%s, %s, %s)",																																																																																
								 GetSQLValueString(utf8_decode($_GET['email']), "text"),
								 GetSQLValueString(utf8_decode($_GET['nombre']), "text"),
								 GetSQLValueString(utf8_decode($_GET['password']), "text")/*,
								 GetSQLValueString($_GET['id_permiso'], "int")*/);
				
				break;
				
	case 'UPDATE':

				//$sql = sprintf("UPDATE usuarios SET email=%s, nombre=%s, password=%s, id_permiso=%s WHERE id_usuario=%s",
				$sql = sprintf("UPDATE usuarios SET email=%s, nombre=%s, password=%s WHERE id_usuario=%s",																																																																																	
								 GetSQLValueString(utf8_decode($_GET['email']), "text"),
								 GetSQLValueString(utf8_decode($_GET['nombre']), "text"),
								 GetSQLValueString(utf8_decode($_GET['password']), "text"),
								 /*GetSQLValueString($_GET['id_permiso'], "int"),*/
								 GetSQLValueString($_GET['id_usuario'], "int"));
				
				break;
}// switch

$result = mysql_query($sql, $dbConn) or die(mysql_error());

mysql_free_result($result);

?>