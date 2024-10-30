<?php
add_action('widgets_init', 'dv_latest_coupons');
function dv_latest_coupons()
{
	register_widget('dv_latest_coupons_widget');
}
class dv_latest_coupons_widget extends WP_Widget {
	
	function dv_latest_coupons_widget()
	{
		$widget_ops = array('classname' => 'latest_coupons', 'description' => 'Display latest coupons of all stores.');
		$control_ops = array('id_base' => 'home_top_work_widget');
		$this->WP_Widget('home_top_work_widget', 'DV Coupons: Latest Coupons', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		
		$title_widget			= $instance['title_widget'];
		$limit					= $instance['limit'];


		echo $before_widget; 
		
                echo '<h3 class="widget-title">'.$title_widget.'</h3>';
				
				echo '<ul>';
					
					$args = array( 
									'posts_per_page'	=> $limit, 
									'post_type'			=> 'coupons',
									'orderby'			=> 'post_date',
									'order'				=> 'DESC' );
									
					$coupons = get_posts( $args );
					
					foreach ( $coupons as $coupon ) : setup_postdata( $coupon ); ?>
                    
						<li>
							<a href="<?php echo get_permalink($coupon->ID); ?>"><?php echo get_the_title($coupon->ID); ?> ( <?php echo get_post_meta($coupon->ID, 'coupon_discount', true); ?> )</a>
						</li>
					
					<?php endforeach; 
					wp_reset_postdata();
					
					
				echo '</ul>';
			
		echo $after_widget;
	}
	
	
	
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title_widget']	= $new_instance['title_widget'];
		$instance['limit']			= $new_instance['limit'];
		
		
		return $instance;
	}
	
	function form($instance)
	{
		$defaults = array('title_widget' => 'Latest Coupons', 'limit' => '10');
		
		$instance = wp_parse_args((array) $instance, $defaults); ?>		
		
			<p>
			<label for="<?php echo $this->get_field_id('title_widget'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title_widget'); ?>" name="<?php echo $this->get_field_name('title_widget'); ?>" value="<?php echo $instance['title_widget']; ?>" />
			</p>
									
			<p>
			<label for="<?php echo $this->get_field_id('limit'); ?>">Number of Coupons:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" value="<?php echo $instance['limit']; ?>" />
			</p>
			
		
	<?php
	}
}
?>