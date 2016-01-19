<?php 
require 'Templates/phpHeadingTemplate.php';


mysql_select_db($database, $dbConn);

/* Obtiene todas las imágenes para el carrusel */
$query_imagenesCarrusel = "SELECT * FROM imagenesCarrusel ORDER BY orden ASC";
$imagenesCarrusel = mysql_query($query_imagenesCarrusel, $dbConn) or die(mysql_error());
$totalRows_imagenesCarrusel = mysql_num_rows($imagenesCarrusel);


require 'Templates/mainTemplate.php'; ?>

<script src="JQuery/flexslider-2.2/jquery.flexslider.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="JQuery/flexslider-2.2/flexslider.css" />
<script type="text/javascript" charset="utf-8" src="JQuery/utilities.js"></script>


<script type="text/javascript">

	$(document).ready(function() {
				
		$('.flexslider').flexslider({
				animation: "slide",
				controlNav: true,
				directionNav: true,
				animationLoop: true,
				slideshow: true,
				prevText: "",
				nextText: "",
				itemWidth: "100%",
				itemMargin: 1,
				minItems: 1,
				maxItems: 1,
				move: 0
		});

		// Desplazamos la página hasta la barra rápida de filtrado.
		$('html, body').animate({scrollTop: $("div#header").height() - $("div#quick-filter-bar").height()}, 2000);
											
	}); //$(document).ready(); 

</script>

<?php require 'Templates/headTemplate.php'; ?>

<!-- START CONTENT -->
<div id="quienes-somos">
  
  <div id="title-container">
    <div id="title">
      <span class="diamond">&diams;</span>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      QUI&Eacute;NES SOMOS
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <span class="diamond">&diams;</span>
    </div>
  </div>
  
  <div id="left">
  	<div id="uno">Nuestro prop&oacute;sito es hacer de la contrataci&oacute;n de servicios un proceso simple y eficiente, libre de estr&eacute;s para la gente y con la seguridad de que est&aacute;n eligiendo la mejor opci&oacute;n posible.</div>
  	<div id="dos">Igual que t&uacute;, somos consumidores de servicios de telecomunicaciones y estamos obsesionados con la transparencia y la neutralidad. Queremos desaparecer la angustia, resolver la confusi&oacute;n y ordenar el caos que enfrentamos al contratar un servicio de TV, Internet, Telefon&iacute;a o Celular.</div>
  	<div id="tres">Al mismo tiempo ayudamos a los proveedores de servicios a conectar con usuarios exigentes e informados, reducimos los obst&aacute;culos entre ambos y contribuimos a generar una industria pr&oacute;spera y altamente competitiva.</div>
  </div>
   
  <div id="right">
  	<div id="img"><img src="images/quienes-somos.jpg" /></div>
  	<div id="ad-right-top" class="ad"><?php loadAd(QUIENES_SOMOS_RIGHT_TOP, $dbConn); ?></div>
    <!--div class="clearfix"></div-->
  	<div id="ad-right-bottom" class="ad"><?php loadAd(QUIENES_SOMOS_RIGHT_BOTTOM, $dbConn); ?></div>
  	<div id="ad-left" class="ad"><?php loadAd(QUIENES_SOMOS_LEFT, $dbConn); ?></div>
  </div> 
   
  <div class="clearfix"></div>    
    
  <?php if($totalRows_imagenesCarrusel > 0){ ?>  
  <div class="flexslider">
    <ul class="slides">
    	<?php
			
				while($row_imagenesCarrusel = mysql_fetch_assoc($imagenesCarrusel)){
				
					echo "<li>";
					
					if($row_imagenesCarrusel['url'] != NULL){
						echo "<a href='" . $row_imagenesCarrusel['url'] . "' target='_blank'>";
					}
					
					echo "<img src='uploads/carrusel/" . $row_imagenesCarrusel['filename'] . "'>";	
					
					if($row_imagenesCarrusel['url'] != NULL){
						echo "</a>";
					}
					
					echo "</li>";
					
				}//while
			
			?>
    </ul>
  </div>
  <?php }//if ?>

</div>

<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>