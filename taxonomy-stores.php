<?php

include(get_template_directory().'/header.php'); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<article>

    			<div class="entry-content">
	                <h3>Coupon Store: <?php single_cat_title(); ?></h3>
					<?php while ( have_posts() ) : the_post(); ?>
	                    <?php echo display_coupons(get_the_ID());  ?>
					<?php endwhile; ?>
    			</div>

			</article>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php include(get_template_directory().'/footer.php'); ?>