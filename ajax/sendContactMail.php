<?php
require_once('../connection/dbConn.php');

// getting variables from form
$nombre = trim($_REQUEST['nombre']);
$email = trim($_REQUEST['email']);
$estado = trim($_REQUEST['estado']);
$comentario = trim($_REQUEST['comentario']);

mysql_select_db($database, $dbConn);
$query = sprintf("SELECT * FROM estados WHERE id_estado = %s", $estado);
$result = mysql_query($query, $dbConn) or die(mysql_error());
$row_estado = mysql_fetch_assoc($result);

$nombre_estado = $row_estado['nombre'];

// prepare email body text
$emailTo = "jluisalmazo@yahoo.com";
$subject = "Contacto";
$body .= "<b>Nombre:</b>&nbsp;" . $nombre;
$body .= "<br /><br />";
$body .= "<b>Email:</b>&nbsp;" . $email;
$body .= "<br /><br />";
$body .= "<b>Estado:</b>&nbsp;" . $nombre_estado;
$body .= "<br /><br />";
$body .= "<b>Comentario:</b>&nbsp;" . $comentario;

$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
$headers .= "From: Elige Fácil Site <clase@poettier.com>" . "\r\n";
$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

// send prepared message
$sent = mail($emailTo, $subject, $body, $headers);

/*
if($sent){
	echo "Succeeded message...";
} else {
	echo "Failed message";
}
*/

?>