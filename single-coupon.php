<?php

include(get_template_directory().'/header.php'); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
		<?php while ( have_posts() ) : the_post(); ?>

			<article>

    			<div class="entry-content">
                    <?php echo display_coupons(get_the_ID());  ?>
    			</div>

			</article>
		<?php endwhile; ?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php include(get_template_directory().'/footer.php'); ?>