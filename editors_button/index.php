<?php

add_action('admin_head','add_all_stores_as_var');
function add_all_stores_as_var() {

    global $wpdb;
    $stores = $wpdb->get_col( "SELECT * from $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy IN('stores') GROUP BY t.term_id");
?>
        <script type="text/javascript">
        			
			var all_stores = {"stores": [
			
					{text: "All", value: "all"},
<?php foreach($stores as $key=>$store_id){ 

		global $wpdb;
		$term_name = $wpdb->get_col( "SELECT t.name from $wpdb->terms AS t WHERE t.term_id = $store_id");
		$term_slug = $wpdb->get_col( "SELECT t.slug from $wpdb->terms AS t WHERE t.term_id = $store_id");
		
?>
					{text: "<?php echo $term_name[0]; ?>", value: "<?php echo $term_slug[0]; ?>"},
<?php } ?>					
				]
			};
        
        
        </script>
        <?php
    
}

add_action( 'init', 'add_coupon_shortcode' );
function add_coupon_shortcode() {
    add_filter( "mce_external_plugins", "dv_add_buttons" );
    add_filter( 'mce_buttons', 'dv_register_buttons' );
}
function dv_add_buttons( $plugin_array ) {
    $plugin_array['dv_coupon_shortcode'] =  PLUGIN_DIR . 'editors_button/add_shortcode.js';
    return $plugin_array;
}
function dv_register_buttons( $buttons ) {
    array_push( $buttons, 'add_coupon_shortcode' ); 
    return $buttons;
}


?>