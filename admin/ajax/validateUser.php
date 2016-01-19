<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/utilities.php');

$email = $_POST["email"];
$password = $_POST["password"];
	
mysql_select_db($database, $dbConn);
$query = sprintf("SELECT id_usuario, email, password, nombre, id_permiso FROM usuarios WHERE email = %s AND password = %s", GetSQLValueString($email, "text"), GetSQLValueString($password, "text"));
$result = mysql_query($query, $dbConn) or die(mysql_error());
$row = mysql_fetch_assoc($result);

$validUser = "false";
$page = "";

if($row['email'] != NULL){
	
	session_start();
	$_SESSION['id_usuario'] = $row['id_usuario'];
	$_SESSION['email'] = $row['email'];
	$_SESSION['nombre'] = $row['nombre'];
	$_SESSION['id_permiso'] = $row['id_permiso'];
	
	$validUser = "true";
	$page = $row['id_permiso'] == 1? "home.php" : "home.php";

} else {
 
	$validUser = "false";
}

echo '{"validUser":"'. $validUser . '", "page":"' . $page . '" }';

mysql_free_result($result);

?>