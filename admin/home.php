<?php
session_start();

if(!isset($_SESSION["email"])){
	
	header("Location: /index.php");
	exit;
		
} else {

	//echo "user connected -> ";
	//echo ' email: |' .  $_SESSION["email"] . "|";
	//echo ' nombre: |' .  $_SESSION["nombre"] . "|";
	
}

require 'Templates/mainTemplate.php';
require 'Templates/headTemplate.php';

?>

<!-- START CONTENT -->

<div id="bienvenida_instrucciones">
  <p>Bienvenid@, por favor eliga la secci&oacute;n que desea administrar desde el men&uacute; superior.</p>
  <p>
    Recuerde siempre cerrar su sesi&oacute;n al finalizar la administraci&oacute;n,
    <br />
    para as&iacute; reforzar la seguridad e integridad de sus contenidos.
  </p>
</div>
  
<!-- CONTENT END -->

<?php require ('Templates/footerTemplate.php'); ?>