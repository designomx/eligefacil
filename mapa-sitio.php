<?php 
require 'Templates/phpHeadingTemplate.php';
require 'Templates/mainTemplate.php'; ?>

<script type="text/javascript">

	$(document).ready(function() {
							
		// Desplazamos la página hasta la barra rápida de filtrado.
		$('html, body').animate({scrollTop: $("div#header").height() - $("div#quick-filter-bar").height()}, 2000);
											
	}); //$(document).ready(); 

</script>

<?php 
require 'Templates/headTemplate.php';
?>

<!-- START CONTENT -->

<div id="mapa-sitio">

<div id="title-container">
  <div id="title">
    <span class="diamond">&diams;</span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    MAPA DEL SITIO
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span class="diamond">&diams;</span>
  </div>
</div>

<ul>
	<a href="index.php"><li>Inicio</li></a>
	<a href="comparador.php"><li>&iexcl;Descubre!</li></a>
	<a href="servicios-ott.php"><li>Servicios V&iacute;a Streaming</li></a>
	<a href="blog.php"><li>&iexcl;Ent&eacute;rate!</li></a>
	<a href="contacto.php"><li>Contacto</li></a>
	<a href="quienes-somos.php"><li>Qui&eacute;nes Somos</li></a>
	<a href="avisoprivacidad.php"><li>Aviso de Privacidad</li></a>
	<a href="anunciate.php"><li>An&uacute;nciate con nosotros</li></a>
	<a href="ayuda.php"><li>Ayuda</li></a>
</ul>

</div>

<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>