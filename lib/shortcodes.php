<?php	
	
	function remove_default_template_files(){
		
		echo '<script type="text/javascript">';
			echo	'jQuery(document).ready(function(){
				
						jQuery("#dv_default_template-css").remove();
						jQuery("#dv_default_template-css").remove();
				
					});';
		echo '</script>';
		
	}
	
	function show_coupons($atts){
		
		extract(shortcode_atts(array(
			'store'		=> '',
			'limit'		=> 10,
			'orderby'	=> 'post_date',
			'order'		=> 'DESC',
			'template'	=> ''
		), $atts, 'coupons'));
		
		if($template!=''){
			
			remove_default_template_files();
			wp_enqueue_script( $template.'-script', PLUGIN_DIR.'templates/'.$template.'/custom.js', array(), '1.0.0' );
			wp_enqueue_style( $template.'-style', PLUGIN_DIR."/templates/". $template ."/style.css" );
			
		}
		
		if($limit==''){
			$limit	=	10;
		}
		
		wp_reset_query();
		wp_reset_postdata();
		
		$paged_var		=	(is_front_page() ? 'page' : 'paged');
		
		$myvar	=	'';
		$args = array(
			'posts_per_page' => $limit,
			'post_type' => 'coupons',
			'stores' => $store,
			'post_status' => 'publish',
			'orderby'	=> $orderby,
			'order'		=> $order,
			'paged' => ( get_query_var( $paged_var ) ) ? get_query_var( $paged_var ) : 1
		);
		
		$coupons		=	new WP_Query( $args );
				
		if( $coupons->have_posts() ){

			$myvar	.=	'
			<form action="'.site_url().'?print_coupons=true" method="post" enctype="multipart/form-data" id="print_coupon_form">
				<a href="javascript:;" onClick="make_coupons_clickable()">Print</a>&nbsp;&nbsp;<div id="print_coupon_cpanel"><input type="submit" class="show_before_print" value="Print Selected" class="mwc_submit_bt" /></div>
			
			<div id="mwc_printable_wrapper">
			';
			
			while( $coupons->have_posts() ){ $coupons->the_post();
				$myvar	.=	display_coupons(get_the_ID(), $template);
			}
			
			$myvar	.=	'</div></form>';
			
		}else{
			$myvar		.=	'Sorry, no coupons found !';
		}
				
		$big = 999999999;
		$myvar	.=	'<span class="clear"></span>';
		$myvar	.=	'<div class="dv_pagination">';
		$myvar	.=	paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var($paged_var) ),
			'total' => $coupons->max_num_pages
		) );
		
		$myvar	.=	'</div>';
		wp_reset_postdata();
		wp_reset_query();
		return $myvar;
		
	}
	add_shortcode('coupons', 'show_coupons');
	
	
	function get_cloaked_url_by_actual($actual){
		
		global $wpdb;
		$result		=	$wpdb->get_row("SELECT * FROM `dv_cloaked_urls` WHERE `actaul_url` = '$actual' ");	
		
		if(!empty($result)){
			
			$cloaked_url		=	$result->cloaked_url;
			
			return $cloaked_url;
		}else{
			return '';
		}
		
	}
	
	
	function	display_coupons($post, $template	=	''){
		
		if(is_numeric($post)){
			$post	=	get_post($post);
		}
		
		$curr_views		=	(int)(get_post_meta($post->ID, 'views', true) ? get_post_meta($post->ID, 'views', true) : 0);
		$new_views		=	$curr_views	+ 1;
		
		update_post_meta($post->ID, 'views', $new_views);
				
		if($template==''){
			$file	=	file_get_contents(PLUGIN_BASE."/templates/". (get_option("dv_coupon_template")!='' ? get_option("dv_coupon_template") : 'designsvalley') ."/coupon_snippet.html");
		}else{
			$file	=	file_get_contents(PLUGIN_BASE."/templates/". $template ."/coupon_snippet.html");
		}
		$text	=	$post->post_content;
		
		$post_meta		=	get_post_meta($post->ID);
		$likes			=	$post_meta['likes'][0];
		$dislikes		=	$post_meta['dislikes'][0];
		$clicks			=	$post_meta['clicks'][0];
		$verify_class	=	(get_post_meta($post->ID, 'is_verified', true)=='on' ? ' verified ' : '');
		
		$store_data 	=	wp_get_post_terms( $post->ID, 'stores' );
		
		
		$store_tax				=	wp_get_post_terms($post->ID, 'stores');
		$store_tax				=	$store_tax[0];
		$store_img				=	get_term_meta( $store_tax->term_id, 'store_img', true);
		$store_default_url		=	get_term_meta( $store_tax->term_id, 'store_url', true);

		if($store_tax!='' and $store_img!='' and get_option("dv_use_store_imgs")=='true'){
			
			$feat_image	=	$store_img;
		
		}else{
		
			$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

			if(get_option("dv_use_timthumb")=='true'){
				$feat_image	=	PLUGIN_DIR."timthumb.php?h=120&w=120&src=".$feat_image;
			}
		
		}
		
		if($post_meta['coupon_store_url'][0]!=''){
			if(get_option("dv_use_cloaked_url")=='true'){
				$store_url	=	get_cloaked_url_by_actual($post_meta['coupon_store_url'][0]);
			}else{
				$store_url	=	$post_meta['coupon_store_url'][0];
			}
		}else{
			$store_url	=	$store_default_url;
		}
				
		/*
		 * Checking if date has passed or not !
		 */
		if(strcspn($post_meta['coupon_expiry_date'][0], '0123456789') != strlen($post_meta['coupon_expiry_date'][0])){

				list($y,$m,$d)=explode("-",$post_meta['coupon_expiry_date'][0]);
				$date=mktime(0,0,0,$m,$d,$y);
				
				$today=mktime(0,0,0,date("m"),date("d"),date("Y"));
				
				if($date<$today)
				{
					$expiry_msg		=	$post_meta['coupon_expiry_date'][0].' <span class="red">(Expired)</span>';
				}else{
					$expiry_msg		=	$post_meta['coupon_expiry_date'][0];
				}

		}else{
		
					$expiry_msg		=	$post_meta['coupon_expiry_date'][0];
			
		}

		
		$tags	=	array(	
							"coupon_permalink"		=>	get_permalink($post->ID),
							"coupon_code"			=>	$post_meta['coupon_code'][0],
							"coupon_discount"		=>	$post_meta['coupon_discount'][0],
							"coupon_title"			=>	$post->post_title,
							"expiry_date"			=>	$expiry_msg,
							"coupon_text_contents"	=>	$post->post_excerpt,
							"post_id"				=>	$post->ID,
							"featured_image" 		=>  $feat_image,
							"store_url"				=>	$store_url,
							"coupon_views"			=>	(get_option('dv_display_clicks')=='true' ? (empty($clicks))?'0 Clicks':$clicks.' Clicks': ''),
							"likes"					=>	(get_option('dv_display_likes')=='true' ? (empty($likes))?'0':$likes: ''),
							"dislikes"				=>	(get_option('dv_display_dislikes')=='true' ? (empty($dislikes))?'0':$dislikes: ''),
							"class_dislike"			=>	(get_option('dv_display_dislikes')=='true' ? '' : 'hide'),
							"class_like"			=>	(get_option('dv_display_likes')=='true' ? '' : 'hide'),
							"class_clicks"			=>	(get_option('dv_display_clicks')=='true' ? '' : 'hide'),
							"more_from_this_store"	=>	site_url().'?stores='.$store_data->slug,
							"store_name"			=>	$store_data->name,
							"verify_class"			=>	$verify_class,
							"fb_dis_class"			=>	(get_option('dv_dis_facebook')=='true' ? '' : 'hide'),
							"tw_dis_class"			=>	(get_option('dv_dis_twitter')=='true' ? '' : 'hide'),
							"gp_dis_class"			=>	(get_option('dv_dis_gplus')=='true' ? '' : 'hide')
						);
		
		return parse_template($file, $tags);
	}
	
	
	function	show_coupon_stores($atts){
				
			$output		=	'<div class="store_coupons dv_wrapper">';
				$output		.=	'<ul>';
					
					$args = array(
						'number'        => 999999
					); 
									
					$stores = get_terms("stores", $args);
					
					foreach ( $stores as $store ) :  
                    
						$output		.=	'<li>';
							$output		.=	'<a style="background-image: url('.get_term_meta( $store->term_id, 'store_img', true).');" href="'.get_term_link($store, "stores").'"><strong>'.$store->name.' ( '.$store->count.' )</strong><br>'.$store->description.'</a>';
						$output		.=	'</li>';
					
					endforeach; 					
					
				$output		.=	'</ul>';
		 		
			$output		.=	'</div>';
	
			return $output;			
				
	}
	
	add_shortcode('coupon_stores', 'show_coupon_stores');
	
	
	
	
	function coupons_carousel_func($atts){
		
		extract(shortcode_atts(array(
			'store'		=> '',
			'limit'		=> 10,
			'orderby'	=> 'post_date',
			'order'		=> 'DESC'
		), $atts, 'coupon_carousel'));
					
		wp_enqueue_script( 'mwc_carousel_script', PLUGIN_DIR.'js/carousel.js', array(), '1.0.0' );
		wp_enqueue_style( 'mwc_carousel_style', PLUGIN_DIR."/css/carousel.css" );
		
		if($limit==''){
			$limit	=	10;
		}
		
		wp_reset_query();
		wp_reset_postdata();
		
		$paged_var		=	(is_front_page() ? 'page' : 'paged');
		
		$myvar	=	'';
		$args = array(
			'posts_per_page' => $limit,
			'post_type' => 'coupons',
			'stores' => $store,
			'post_status' => 'publish',
			'orderby'	=> $orderby,
			'order'		=> $order,
			'paged' => ( get_query_var( $paged_var ) ) ? get_query_var( $paged_var ) : 1
		);
		
		$coupons		=	new WP_Query( $args );
		
		$myvar	.=	'
		<script type="text/javascript">
		
			jQuery(function() {	
			
				jQuery(".items-carousel").flexslider({
					animation: "slide",
					itemWidth: 200,
					itemMargin: 0,
					minItems: 1,
					maxItems: 4,
					slideshowSpeed: 4000
				});
			
			});
		
		</script>

		<div class="stripe-regular items-carousel-wrap">
			<div class="row collapse">
				<div class="column">';
			
		if( $coupons->have_posts() ){
					$myvar	.=	'
					<div class="items-carousel flexslider">
							<ul class="rr slides">';
			while( $coupons->have_posts() ){ $coupons->the_post();
								
				$exp_date	=	get_post_meta(get_the_ID(), 'coupon_expiry_date', true);
				if(strcspn($exp_date, '0123456789') != strlen($exp_date)){
					list($y,$m,$d)=explode("-",$exp_date);
						$date=mktime(0,0,0,$m,$d,$y);			
						$today=mktime(0,0,0,date("m"),date("d"),date("Y"));
						if($date<$today){
							$expiry_msg		=	$exp_date.' <span class="red">(Expired)</span>';
						}else{
							$expiry_msg		=	$exp_date;
						}
				}else{
							$expiry_msg		=	$exp_date;
				}
				
				$store_url		=	get_post_meta(get_the_ID(), 'coupon_store_url', true);
				$aff_url		=	(get_option("dv_use_cloaked_url")=='true' ? get_cloaked_url_by_actual($store_url) : $store_url);
							
				ob_start(); ?>
				
					
				<li>
					<div class="wrapper-3 item-thumb">
						<div class="top">
							<figure>
								<a href="<?php the_permalink(); ?>">
									<img src="<?php echo wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>" alt="<?php the_title(); ?>">
								</a>
							</figure>
							<h2 class="alt"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						</div>
						<div class="bottom">
							<p class="value secondary"><?php echo get_post_meta(get_the_ID(), 'coupon_discount', true); ?></p>
							<h6><?php echo $expiry_msg; ?></h6>
							<a href="<?php echo $aff_url; ?>" class="copy_to_clipboard hover input button red secondary" coupon_code="<?php echo get_post_meta(get_the_ID(), 'coupon_code', true); ?>" post_id="<?php the_ID(); ?>" target="_blank">Learn more</a>
						</div>
					</div>
				</li> 
					
			<?php
				$myvar	.=	ob_get_clean();
			
			}
							$myvar	.=	'
							</ul>
						</div>';
			
		}else{
			$myvar		.=	'Sorry, no coupons found !';
		}
		$myvar		.=	'
				</div>
			</div>
		</div>';
				
		$big = 999999999;
		$myvar	.=	'<span class="clear"></span>';

		wp_reset_postdata();
		wp_reset_query();
		return $myvar;
		
	}
	add_shortcode('coupon_carousel', 'coupons_carousel_func');
	
	
	
?>