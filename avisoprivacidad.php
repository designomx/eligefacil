<?php 
require 'Templates/phpHeadingTemplate.php';
require 'Templates/mainTemplate.php';
?>

<link href="css/blog.css" rel="stylesheet" type="text/css">

<script type="text/javascript">

	$(document).ready(function() {
							
		// Desplazamos la página hasta la barra rápida de filtrado.
		$('html, body').animate({scrollTop: $("div#header").height() - $("div#quick-filter-bar").height()}, 2000);
							
	}); //$(document).ready(); 

	function resizeIframe(obj) {
		obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
	}

</script>

<?php
require 'Templates/headTemplate.php';

define('WP_USE_THEMES', false);
require('./blog/wp-blog-header.php');
?>

<!-- START CONTENT -->

<!--<div id="backLink"><a href="blog.php">Regresar</a></div>-->

<iframe src="http://eligefacil.com/blog/avisodeprivacidad/" width="100%" frameborder="0" scrolling="no" onload='resizeIframe(this);'>

</iframe>

    <div class="clearfix"></div>
    
  </div><!-- #relatedPosts -->


<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>