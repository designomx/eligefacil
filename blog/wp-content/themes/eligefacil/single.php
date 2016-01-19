<?php

  /**
  *@desc A single blog post See page.php is for a page layout.
  */

	get_header();

  if (have_posts()) : while (have_posts()) : the_post();
?>

	<article class="postWrapper" id="post-<?php the_ID(); ?>">

      <header>
          <div class="postTitle">
            <span class="diamond">&diams;</span>
            <span class="diamond">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <?php the_title(); ?>
            <span class="diamond">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <span class="diamond">&diams;</span>
          </div>
          <!--div class="postDate"><?php the_date(); ?></div-->
      </header>

      <section class="post"><?php the_content(__('(more...)')); ?></section>
      <!--footer class="postMeta">Category: < ?php the_category(', ') . " " . the_tags(__('Tags: '), ', ', ' | ') . edit_post_link(__('Edit'), ''); ? ></footer-->

			<div class="clearfix"></div>
			<div id="sharing"></div>
      
	</article>
	
  <?php /*
   
  <div id="relatedPosts">
  
    <h2>ART&Iacute;CULOS RELACIONADOS</h2>
		<?php
    
    $postId = get_the_ID();

    / *$categsNotToInclude = array();
    if ( get_category_by_slug( 'sube-tu-columna' ) ) {
      array_push( $categsNotToInclude, get_category_by_slug( "sube-tu-columna" )->term_id );
    }* /
    
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
        / *'category__not_in' => $categsNotToInclude,* /
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
        / *'category__not_in' => $categsNotToInclude,* /
        'showposts'        => 8
      );

      $my_query2 = new WP_Query( $args2 );

      / * Hacemos el merge de los 2 resultsets * /

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
        / *'category__not_in' => $categsNotToInclude,* /
        'showposts'        => 8
      );

      $wp_query = new WP_Query( $args2 );

    }

    $postNum = 1;

    while ( $wp_query->have_posts() && $postNum < 8 ) : $wp_query->the_post(); ?>
    
      <a href="<?php the_permalink(); ?>">
        <div class="relatedPost">
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
    
  </div><!-- #relatedPosts --> */ ?>

	<?php

  endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php
	endif;
	
  get_footer();
	
?>