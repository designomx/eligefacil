

<?php 
require 'Templates/phpHeadingTemplate.php';
require 'Templates/mainTemplate.php';

define('WP_USE_THEMES', false);
require('./blog/wp-blog-header.php');

$postId = url_to_postid( $_GET['url'] );

$post_title = utf8_decode(get_the_title($postId));
$post_thumb_url = wp_get_attachment_url( get_post_thumbnail_id($postId) );

?>

<link href="css/blog.css" rel="stylesheet" type="text/css">
<meta property="og:site_name" content="Elige Fácil"/>
<meta property="og:title" content="<?php echo $post_title; ?>"/>
<!--meta property="og:description" content="Compelling description of URL that is about 300 characters in length."/-->
<meta property="og:image" content="<?php echo $post_thumb_url; ?>">
<meta property="og:type" content="article"/>

<link href="css/blog.css" rel="stylesheet" type="text/css">

<script type="text/javascript">

	$(document).ready(function() {
		
		// Desplazamos la página hasta la barra rápida de filtrado.
		$('html, body').animate({scrollTop: $("div#header").height() - $("div#quick-filter-bar").height()}, 2000);
							
	}); //$(document).ready(); 
	

	function resizeIframe(obj) {
		
		//obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
		
		var iframe_contentElement = obj.contentDocument || obj.contentWindow.document;
		
		obj.style.height = iframe_contentElement.getElementById("canvas").scrollHeight + 'px';
	}
	
	$(window).resize(function(){
	
		resizeIframe(document.getElementById("ifrm"));
	
	});
	
</script>

<?php require 'Templates/headTemplate.php'; ?>

<!-- START CONTENT -->


  <div id="backLink"><a href="blog.php">Regresar</a></div>
  
  <iframe id="ifrm" src="<?php echo $_GET['url']; ?>" width="100%" frameborder="0" scrolling="no" onload='resizeIframe(this);'></iframe>
  
  <!-- Go to www.addthis.com/dashboard to customize your tools -->
  <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-55f1d450194756b3" async="async"></script>  
  <!-- Go to www.addthis.com/dashboard to customize your tools -->
  <div class="addthis_sharing_toolbox" data-url="http://eligefacil.com/blog-post.php?url=<?php echo $_GET['url']; ?>" data-title="<?php echo $post_title; ?>"></div>


  <div id="relatedPosts">
  
    <h2>
    	<span class="diamond">&diams;</span>
      <span class="diamond">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      ART&Iacute;CULOS RELACIONADOS
      <span class="diamond">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <span class="diamond">&diams;</span>
    </h2>
		
		<?php
    
    //$postId = get_the_ID();

		//$postId = url_to_postid( $_GET['url'] );

    /*$categsNotToInclude = array();
    if ( get_category_by_slug( 'sube-tu-columna' ) ) {
      array_push( $categsNotToInclude, get_category_by_slug( "sube-tu-columna" )->term_id );
    }*/
    
    $postTagsIds = wp_get_post_tags( $postId, array( 'fields' => 'ids' ) );

    // Si hay tags, entonces creamos 2 queries:
    // 1 con máximo 4 posts relacionados por los tags
    // y otro con máximo 4 posts relacionados por las categorias
    // Y al final hacemos un merge de los 2 resultsets y sólo mostramos los 4 primeros,
    // así, si no hay 4 posts relacionados por tags, se completan con los relacionados por las categorias
    if ( sizeof( $postTagsIds ) > 0 ) {

      $args = array(
        'tag__in'          => $postTagsIds,
        'post__not_in'     => array( $postId ),
        /*'category__not_in' => $categsNotToInclude,*/
        'showposts'        => 8
      );

      $my_query = new WP_Query( $args );

      $postCatsIds = array();
      foreach ( get_the_category( $postId ) as $category ) {
        array_push( $postCatsIds, $category->term_id );
      }

      $args2 = array(
        'category__in'     => $postCatsIds,
        'post__not_in'     => array( $postId ),
        /*'category__not_in' => $categsNotToInclude,*/
        'showposts'        => 8
      );

      $my_query2 = new WP_Query( $args2 );

      /* Hacemos el merge de los 2 resultsets */

      //create new empty query and populate it with the other two
      $wp_query        = new WP_Query();
      $wp_query->posts = array_merge( $my_query->posts, $my_query2->posts );

      //populate post_count count for the loop to work correctly
      $wp_query->post_count = $my_query->post_count + $my_query2->post_count;

    } else { // Si no hay tags para encontrar los posts relacionados, buscamos por categoria únicamente.

      $postCatsIds = array();
      foreach ( get_the_category( $postId ) as $category ) {
        array_push( $postCatsIds, $category->term_id );
      }

      $args2 = array(
        'category__in'     => $postCatsIds,
        'post__not_in'     => array( $postId ),
        /*'category__not_in' => $categsNotToInclude,*/
        'showposts'        => 8
      );

      $wp_query = new WP_Query( $args2 );

    }

    $postNum = 1;

    while ( $wp_query->have_posts() && $postNum < 8 ) : $wp_query->the_post(); ?>
    
      <a href="blog-post.php?url=<?php the_permalink(); ?>">
        
        <div class="post related" id="post-<?php the_ID(); ?>">
           <div class="storytitle"><?php echo utf8_decode(get_the_title()); ?></div>
           <?php 
            if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
            the_post_thumbnail();
            } 
            ?>
        </div>
        
      </a>
      
    <?php
      $postNum++;
    
    endwhile;

    wp_reset_query();

    ?>
    <div class="clearfix"></div>
    
  </div><!-- #relatedPosts -->


<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>