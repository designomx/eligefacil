<?php 
require 'Templates/phpHeadingTemplate.php';
require 'Templates/mainTemplate.php';
?>

<script type="text/javascript">

	$(document).ready(function() {
							
							
	}); //$(document).ready(); 

</script>

<?php
require 'Templates/headTemplate.php';

define('WP_USE_THEMES', false);
require('./blog/wp-blog-header.php');
?>

<!-- START CONTENT -->

<div id="blog">

	<h1>BLOG</h1>
    
  <?php
  
  $args1 = array(
    'category_name' => "noticias",
    'showposts' => 10
  );
  
  $my_query1 = new WP_Query($args1);

  if( $my_query1->have_posts() ) : ?>
              
      <?php
      while( $my_query1->have_posts() ) : $my_query1->the_post();
      ?>
    
    <?php //if (have_posts()) : while (have_posts()) : the_post(); ?>
    
    <?php /*?><?php the_date('','<h2>','</h2>'); ?><?php */?>
      
    <div class="post" id="post-<?php the_ID(); ?>">
       <h3 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
      <div class="meta"><?php _e("Filed under:"); ?> <?php the_category(',') ?> &#8212; <?php the_author() ?> @ <?php the_time() ?> <?php edit_post_link(__('Edit This')); ?></div>
      
      <div class="storycontent">
        <?php the_content(__('(more...)')); ?>
      </div>
      
      <div class="feedback">
                <?php wp_link_pages(); ?>
                <?php comments_popup_link(__('Comments (0)'), __('Comments (1)'), __('Comments (%)')); ?>
      </div>
    
    </div>
    
    <?php comments_template(); // Get wp-comments.php template ?>
                
    <?php endwhile; else: ?>
    <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>            

</div>

<!-- CONTENT END -->

<?php 
    require ('Templates/footerTemplate.php'); 
?>