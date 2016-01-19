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
					
							
	}); //$(document).ready(); 

</script>

<?php

require 'Templates/headTemplate.php';

// INCLUYE LA FUNCIONALIDAD DE WORDPRESS
define('WP_USE_THEMES', false);
require('./blog/wp-blog-header.php');

?>

<!-- START CONTENT -->
<div id="home">
  
  <div id="ad-left" class="ad"><?php loadAd(HOME_LEFT, $dbConn); ?></div>
  <div id="ad-right" class="ad"><?php loadAd(HOME_RIGHT, $dbConn); ?></div>
  <div id="middle">
		<?php
      $args1 = array(
        'category_name' => "home",
        'showposts' => 1
      );
      
      $my_query1 = new WP_Query($args1);
    
      if( $my_query1->have_posts() ) :
            
          $my_query1->the_post();
          
          echo "<a href='blog-post.php?url=" . get_the_permalink()  . "'>";
          echo "	<div id='blogPost'>";
          echo "		<div class='storytitle'>" . get_the_title() . "</div>";
                    if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
                      the_post_thumbnail();
                    }; 
          echo "	</div>";
          echo "</a>";
      
      else :
      
        echo "<div id='blogPost'></div>"; //No hay post que mostrar.
        
      endif;
    ?>
        
    <a href="servicios-ott.php"><div id="servicios-ott"><img src="images/home/boton_serviciosOTT.jpg" /></div></a>
    <div id="ad-center" class="ad"><?php loadAd(HOME_CENTER, $dbConn); ?></div>
    <div class="clearfix"></div>
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