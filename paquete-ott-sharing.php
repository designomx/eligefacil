<?php 
require 'Templates/phpHeadingTemplate.php';

mysql_select_db($database, $dbConn);

/* Obtiene el nombre del paquete */
$query_paquete = sprintf("SELECT nombre FROM paquetes_ott WHERE id_paquete=%s", GetSQLValueString($_GET['id_paquete'], "int"));
$paquete = mysql_query($query_paquete, $dbConn) or die(mysql_error());
$row_paquete = mysql_fetch_assoc($paquete);

require 'Templates/mainTemplate.php'; 
?>

<link href="css/blog.css" rel="stylesheet" type="text/css">
<meta property="og:site_name" content="Elige Fácil"/>
<meta property="og:title" content="Paquete: <?php echo $row_paquete['nombre']; ?>"/>
<!--meta property="og:description" content="Compelling description of URL that is about 300 characters in length."/-->
<meta property="og:image" content="images/eligefacil.jpg">
<meta property="og:type" content="article"/>

<script type="text/javascript">

	$(document).ready(function() {
							
							
	}); //$(document).ready(); 

	function resizeIframe(obj) {
		obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
	}

</script>

<?php
require 'Templates/headTemplate.php';
?>

<!-- START CONTENT -->

<div class="spacer-top"></div>
<iframe src="paquete-ott.php?id_paquete=<?php echo $_GET['id_paquete']; ?>&header=true" width="100%" frameborder="0" scrolling="no" onload='resizeIframe(this);'></iframe>

<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>