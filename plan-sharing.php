<?php 
require 'Templates/phpHeadingTemplate.php';

mysql_select_db($database, $dbConn);

/* Obtiene todos los datos del plan */
$query_plan = sprintf("SELECT nombre FROM planes WHERE id_plan=%s", GetSQLValueString($_GET['id_plan'], "int"));
$plan = mysql_query($query_plan, $dbConn) or die(mysql_error());
$row_plan = mysql_fetch_assoc($plan);

require 'Templates/mainTemplate.php'; 
?>

<link href="css/blog.css" rel="stylesheet" type="text/css">

<meta property="og:site_name" content="Elige Fácil"/>
<meta property="og:title" content="Plan: <?php echo $row_plan['nombre']; ?>"/>
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
<iframe src="plan.php?id_plan=<?php echo $_GET['id_plan']; ?>&header=true" width="100%" frameborder="0" scrolling="no" onload='resizeIframe(this);'></iframe>

<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>