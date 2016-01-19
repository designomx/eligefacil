<?php 
require 'Templates/phpHeadingTemplate.php';

mysql_select_db($database, $dbConn);

/* Obtiene los nombres de todos los planes comparados. */
$query_planes = sprintf("SELECT nombre FROM planes WHERE id_plan IN(%s)", implode(", ", $_GET['planes']));
$planes = mysql_query($query_planes, $dbConn) or die(mysql_error());

$planes_list = "";
$i = 1;

while($row_planes = mysql_fetch_assoc($planes)){

	if($i > 1){
		
		 $planes_lists .= ", ";
  }

	$planes_lists .= $row_planes['nombre'];
		
	$i++;	
}

require 'Templates/mainTemplate.php'; 

?>

<link href="css/blog.css" rel="stylesheet" type="text/css">

<meta property="og:site_name" content="Elige Fácil"/>
<meta property="og:title" content="Comparaci&oacute;n de planes: <?php echo $planes_lists; ?>"/>
<!--meta property="og:description" content="Compelling description of URL that is about 300 characters in length."/-->
<meta property="og:image" content="images/eligefacil.jpg">
<meta property="og:type" content="article"/>

<script type="text/javascript">

	$(document).ready(function() {
			
		$('#content').css({'max-width': '100%'});					
							
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

<iframe src="comparacion.php?<?php echo htmlentities($_SERVER['QUERY_STRING']); ?>" width="100%" frameborder="0" scrolling="no" onload='resizeIframe(this);'></iframe>

<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>