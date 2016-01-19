

<?php 
require 'Templates/phpHeadingTemplate.php';
require 'Templates/mainTemplate.php';
?>

<link href="css/blog.css" rel="stylesheet" type="text/css">

<script type="text/javascript">

	$(document).ready(function() {
		
		<?php if(isset($_GET['categoria'])){
			echo "$('#categoria').val('" . $_GET['categoria'] . "')";
		} ?>
	
		$('#categoria').change(function(){
					
			window.location = "blog.php?categoria=" + $(this).val();
			
		});
		
		// Desplazamos la página hasta la barra rápida de filtrado.
		$('html, body').animate({scrollTop: $("div#header").height() - $("div#quick-filter-bar").height()}, 2000);
							
	}); //$(document).ready(); 

</script>

<?php
require 'Templates/headTemplate.php';

define('WP_USE_THEMES', false);
require('./blog/wp-blog-header.php');
?>

<!-- START CONTENT -->
  
  <div id="blog-header">
    <div class="serviciosott"> <a href="servicios-ott.php"><img src="images/botonott.jpg" alt="" width="312" height="50"/></a></div>
    <div class="categorias">
      <select name="categoria" id="categoria">
          <option value="noticias">Ver todas</option>
          
          <?php
          
            $idObj = get_category_by_slug('noticias'); 
            $id = $idObj->term_id;
            
            $args = array("type" => "post", "child_of" => $id);
            $categories = get_categories($args);
            
            foreach ($categories as $category) {
              echo "<option value='" . $category->category_nicename . "'>" . utf8_decode($category->cat_name) . "</option>";
            }
          ?> 
      </select>
    </div>
    <div class="clearfix"></div>
  </div>
  
  <div id="blog">
      
    <!-- Grid de Posts -->
   
    <?php
 
 //echo "|" . $_GET['categoria'] . "|";
    
    $args1 = array(
      'category_name' => $_GET['categoria'],
/* 		'showposts' => 10*/
    );
    
    $my_query1 = new WP_Query($args1);
  
    if( $my_query1->have_posts() ) : ?>
                
        <?php
        while( $my_query1->have_posts() ) : $my_query1->the_post();
        ?>
      <a href="blog-post.php?url=<?php the_permalink() ?>" rel="bookmark">
      <div class="post" id="post-<?php the_ID(); ?>">
         <div class="storytitle"><?php echo utf8_decode(get_the_title()); ?></div>
         <?php 
          if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
          the_post_thumbnail();
          } 
          ?>
      </div>
      </a>
      <?php comments_template(); // Get wp-comments.php template ?>
                  
      <?php endwhile; else: ?>
      <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
  <?php endif; ?>
  
      <div class="clearfix"></div>
  
  </div>

	<div class="clearfix"></div>

<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>