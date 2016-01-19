<?php

require_once('../../connection/dbConn.php');
require_once('../phpTools/utilities.php');

$email = $_GET["email"];
	
mysql_select_db($database, $dbConn);
$query = sprintf("SELECT email FROM usuarios WHERE email = %s", GetSQLValueString($email, "text"));
$result = mysql_query($query, $dbConn) or die(mysql_error());
$row = mysql_fetch_assoc($result);

$emailExists = "false";

if($row['email'] != NULL){
		
	$emailExists = "true";

}

echo '{"emailExists":"'. $emailExists . '"}';

mysql_free_result($result);

?>