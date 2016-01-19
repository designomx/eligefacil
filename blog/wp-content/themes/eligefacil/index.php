<?php

  get_header();

  if (have_posts()): ?>

<section id="posts">
	<?php

	    while (have_posts()) : the_post(); ?>

		<article class="postWrapper" id="post-<?php the_ID(); ?>">

		<header>
	      <h2 class="postTitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	      <small><?php the_date(); ?> by <?php the_author(); ?></small>
		</header>

		  <section class="post"><?php the_content(__('(more...)')); ?></section>
		
	      <footer class="postMeta">Category: <?php the_category(', ') . " " . the_tags(__('Tags: '), ', ', ' | ') . comments_popup_link(__('Comments (0)'), __('Comments (1)'), __('Comments (%)')) . edit_post_link(__('Edit'), ' | '); ?></footer>

	    </article>

	    <?php comments_template(); // Get wp-comments.php template ?>

	    <?php endwhile; ?>
		
</section>	

<?php else: ?>

  <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>

<?php

  endif;
  ?>

  <?php if (will_paginate()): ?>

<nav>
    <ul id="pagination">
      <li class="previous"><?php posts_nav_link('','','&laquo; Previous Entries') ?></li>
      <li class="future"><?php posts_nav_link('','Next Entries &raquo;','') ?></li>
    </ul>
</nav>  

    
  <?php endif; ?>


  <?php
  get_footer();
?>