<?php 
require 'Templates/phpHeadingTemplate.php';
require 'Templates/mainTemplate.php';
?>

<link href="css/blog.css" rel="stylesheet" type="text/css">

<script type="text/javascript">

	$(document).ready(function() {
							
							
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

<h2>Página No Encontrada</h2>
<br>
¡Lo sentimos!
&nbsp;La página que buscas no existe.<br>
&nbsp; &nbsp;
<br><br>
<ul>
<li>Para ir a la página de inicio da click aqui:
<a href="http://eligefacil.com/">Eligefacil.com</a>
</li>
</ul>
&nbsp; &nbsp;

    <div class="clearfix"></div>
    
  </div><!-- #relatedPosts -->


<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>
