<?php
	
add_action( 'init', 'create_post_type' );
function create_post_type() {
	
	$labels = array(
    'name' => _x('Coupons', 'post type general name', 'your_text_domain'),
    'singular_name' => _x('Coupon', 'post type singular name', 'your_text_domain'),
    'add_new' => _x('Add New Coupon', 'Coupon', 'your_text_domain'),
    'add_new_item' => __('Add New Coupon', 'your_text_domain'),
    'edit_item' => __('Edit Coupon', 'your_text_domain'),
    'new_item' => __('New Coupon', 'your_text_domain'),
    'all_items' => __('All Coupons', 'your_text_domain'),
    'view_item' => __('View Coupons', 'your_text_domain'),
    'search_items' => __('Search Coupons', 'your_text_domain'),
    'not_found' =>  __('No Coupons found', 'your_text_domain'),
    'not_found_in_trash' => __('No Coupons found in Trash', 'your_text_domain'), 
    'parent_item_colon' => '',
    'menu_name' => __('Coupons', 'your_text_domain')

  );
	
	
	register_post_type( 'coupons',
		array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'coupon'),
			'supports' => array( 'title', /*'editor',*/ 'thumbnail', 'excerpt', 'comments','custom-fields' )
		)
	);
}


add_filter( 'post_updated_messages', 'coupon_status_messages' );

function coupon_status_messages( $messages ) {
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );

	$messages['coupons'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Coupon updated.', 'magic_wp_coupons' ),
		2  => __( 'Custom field updated.', 'magic_wp_coupons' ),
		3  => __( 'Custom field deleted.', 'magic_wp_coupons' ),
		4  => __( 'Coupon updated.', 'magic_wp_coupons' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Coupon restored to revision from %s', 'magic_wp_coupons' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Coupon published.', 'magic_wp_coupons' ),
		7  => __( 'Coupon saved.', 'magic_wp_coupons' ),
		8  => __( 'Coupon submitted.', 'magic_wp_coupons' ),
		9  => sprintf(
			__( 'Coupon scheduled for: <strong>%1$s</strong>.', 'magic_wp_coupons' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i', 'magic_wp_coupons' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Coupon draft updated.', 'magic_wp_coupons' )
	);

	if ( $post_type_object->publicly_queryable and get_option("dv_disable_single_view")=='true' ) {
		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View Coupon', 'magic_wp_coupons' ) );
		$messages[ $post_type ][1] .= $view_link;
		$messages[ $post_type ][6] .= $view_link;
		$messages[ $post_type ][9] .= $view_link;

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview Coupon', 'magic_wp_coupons' ) );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}

	return $messages;
}



// hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'regi_coupon_tax', 0 );

// create two taxonomies, genres and writers for the post type "book"
function regi_coupon_tax() {
	
	
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Coupon Stores', 'taxonomy general name' ),
		'singular_name'     => _x( 'Coupon Store', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Coupon Stores' ),
		'all_items'         => __( 'All Coupon Stores' ),
		'parent_item'       => __( 'Parent Coupon Store' ),
		'parent_item_colon' => __( 'Parent Coupon Store:' ),
		'edit_item'         => __( 'Edit Coupon Store' ),
		'update_item'       => __( 'Update Coupon Store' ),
		'add_new_item'      => __( 'Add New Coupon Store' ),
		'new_item_name'     => __( 'New Coupon Store Name' ),
		'menu_name'         => __( 'Stores' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'coupons' ),
	);

	register_taxonomy( 'stores', array( 'coupons' ), $args );

	
}

/*--------------- For Taxonomy Images -----------------*/

//include('term_meta_class.php');

add_action('stores_add_form_fields', 'stores_metabox_add', 10, 1);
add_action('stores_edit_form_fields', 'stores_metabox_edit', 10, 1);    

function stores_metabox_add($tag) { ?>
	
    <script type="text/javascript">
	
		var image_field2;
		jQuery(function($){
		  $(document).on('click', 'input.store_img_bt', function(evt){
			image_field2 = $('.store_img');
			tb_show('', 'media-upload.php?TB_iframe=1');
			return false;
		  });
		  window.send_to_editor = function(html) {
			imgurl = $('img', html).attr('src');
			image_field2.val(imgurl);
			$( image_field2 ).trigger( "click" );
			tb_remove();
		  }
		});
	
	</script>
    <h3>Store Details</h3>
    <div class="form-field">
        <label for="store_url"><?php _e('Store URL') ?></label>
        <input name="store_url" id="store_url" type="text" value="" size="40" aria-required="true" />
    </div>
    <div class="form-field">
        <label for="store_img"><?php _e('Store Image') ?></label>
		<input type="button" class="store_img_bt button" value="Select Image" style="margin-right: 5px;" />
        <input name="store_img" id="store_img" class="store_img" type="text" value="" size="40" style="display: inline-block;width: 235px;" aria-required="true" />
	</div>
<?php }     

function stores_metabox_edit($tag) { ?>

    <h3>Store Details</h3>
<script type="text/javascript">

	var image_field2;
	jQuery(function($){
	  $(document).on('click', 'input.store_img_bt', function(evt){
		image_field2 = $('.store_img');
		tb_show('', 'media-upload.php?TB_iframe=1');
		return false;
	  });
	  window.send_to_editor = function(html) {
		imgurl = $('img', html).attr('src');
		image_field2.val(imgurl);
		$( image_field2 ).trigger( "click" );
		tb_remove();
	  }
	});

</script>
    <table class="form-table">
        <tr class="form-field">
        <th scope="row" valign="top">
            <label for="store_url"><?php _e('Store URL'); ?></label>
        </th>
        <td>
            <input name="store_url" id="store_url" type="text" value="<?php echo get_term_meta($tag->term_id, 'store_url', true); ?>" size="40" aria-required="true" />
        </td>
        </tr>
        <tr class="form-field">
        <th scope="row" valign="top">
            <label for="store_img"><?php _e('Store Image'); ?></label>
        </th>
        <td>

			
<?php 
$store_img	=	get_term_meta($tag->term_id, 'store_img', true);
	 	if( $store_img != ""){
			echo '<span style="display: inline-block; overflow: hidden; line-height: 0;">';
					echo '<img id="store_img_img" src="'. $store_img .'" width="180" />';
			echo '</span>';
			echo '<span style="clear:both; display:block; float: none;"></span>';
		}
?>
		<input type="text" class="store_img" name="store_img" id="store_img" value="<?php echo $store_img; ?>" size="40" aria-required="true" />
		<input type="button" class="store_img_bt button" value="Select Image" />
            
        </td>
        </tr>
    </table>
<?php }



add_action('created_stores', 'save_stores_metadata', 10, 1);    
add_action('edited_stores', 'save_stores_metadata', 10, 1);

function save_stores_metadata($term_id){
	{
	
		if (isset($_POST['store_url'])) 
			update_term_meta( $term_id, 'store_url', $_POST['store_url']);
	}
	{
		if (isset($_POST['store_img'])) 
			update_term_meta( $term_id, 'store_img', $_POST['store_img']);
	}
}


 ?>