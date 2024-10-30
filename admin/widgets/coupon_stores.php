<?php
add_action('widgets_init', 'dv_coupon_stores');
function dv_coupon_stores()
{
	register_widget('dv_coupon_stores_widget');
}
class dv_coupon_stores_widget extends WP_Widget {
	
	function dv_coupon_stores_widget()
	{
		$widget_ops = array('classname' => 'stores_coupons', 'description' => 'Displays all stores.');
		$control_ops = array('id_base' => 'dv_coupons_stores_widget_id');
		$this->WP_Widget('dv_coupons_stores_widget_id', 'DV Coupons: Coupon Stores', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		
		$title_widget			= $instance['title_widget'];


		echo $before_widget; 
		
                echo '<h3 class="widget-title">'.$title_widget.'</h3>';
				
				echo '<ul>';
					
					$args = array(
						'number'        => 9999999
					); 
									
					$stores = get_terms("stores", $args);
					
					foreach ( $stores as $store ) :  ?>
                    
						<li>
							<a href="<?php echo get_term_link($store, 'stores'); ?>"><?php echo $store->name; ?> ( <?php echo $store->count; ?> )</a>
						</li>
					
					<?php endforeach; 					
					
				echo '</ul>';
			
		echo $after_widget;
	}
	
	
	
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title_widget']	= $new_instance['title_widget'];
		
		return $instance;
	}
	
	function form($instance)
	{
		$defaults = array('title_widget' => 'Coupon Stores');
		
		$instance = wp_parse_args((array) $instance, $defaults); ?>		
		
			<p>
			<label for="<?php echo $this->get_field_id('title_widget'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title_widget'); ?>" name="<?php echo $this->get_field_name('title_widget'); ?>" value="<?php echo $instance['title_widget']; ?>" />
			</p>
									
		
	<?php
	}
}
?>