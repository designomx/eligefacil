<?php

  /**
  *@desc A page. See single.php is for a blog post layout.
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
         	 <div class="postDate"><?php the_date(); ?></div>

		</header>

      	<?php  /* echo get_avatar( $comment, 32 );  */ ?>
      
		<section class="post"><?php the_content(__('(more...)')); ?></section>
		<!--footer class="postMeta"><?php edit_post_link(__('Edit'), ''); ?></footer-->

    </article>

  <?php
//  comments_template();

  endwhile; else: ?>

    <p>Sorry, no pages matched your criteria.</p>

<?php
  endif;

  get_footer();
?>