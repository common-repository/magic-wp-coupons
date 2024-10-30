<?php
$plugin_metaboxes = array(
    'id' => 'coupons-meta',
    'title' => PLUGIN_NAME.' Custom Settings',
    'page' => 'coupons',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
       array(
            'name' => 'Is Verified?',
            'id' => 'is_verified',
            'type' => 'checkbox'
        ),
		array (
			"id"		=> "coupon_discount",
			"default" 	=> "",
			"name" 		=> "Coupon Discount",
			"type" 		=> "text",
			"desc"      => "How much discount this coupon will allow to get."
		),
		
		array (
			"id"		=> "coupon_code",
			"default" 	=> "",
			"name" 	=> "Coupon Code",
			"type" 		=> "text",
			"desc"      => "A coupon code which will be used to redeem the discount."
		),
		
		array (
			"id"		=> "coupon_expiry_date",
			"default" 	=> "",
			"name" 	=> "Coupon Expiry Date",
			"type" 		=> "text",
			"desc"      => "Put the date when this couopon will expire. you also set <strong>No Expires</strong>"
		),
		array (
			"id"		=> "coupon_store_url",
			"default" 	=> "",
			"name" 	=> "Coupon Store URL",
			"type" 		=> "text",
			"desc"      => "The URL where coupon can be redeemed."
		)
		
    )
);

add_action('admin_menu', 'dv_coupons_add_box');
// Add meta box
function dv_coupons_add_box() {
    global $plugin_metaboxes;
    add_meta_box($plugin_metaboxes['id'], $plugin_metaboxes['title'], 'dv_coupons_show_box', $plugin_metaboxes['page'], $plugin_metaboxes['context'], $plugin_metaboxes['priority']);
}


function dv_coupons_show_box() {
    global $plugin_metaboxes, $post;
    // Use nonce for verification
    echo '<input type="hidden" name="dv_coupons_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    echo '<table class="form-table-tttt">';
		
		
    foreach ($plugin_metaboxes['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
        echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
                break;
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
                break;
            case 'select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $key=>$option) {
                    echo '<option ', $meta == $key ? ' selected="selected"' : '', ' value="', $key ,'">', $option, '</option>';
                }
                echo '</select>';
                break;
            case 'radio':
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                }
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                break;
        }
        echo     '</td><td>',
            '</td></tr>';
    }
    echo '</table>';
}


add_action('save_post', 'dv_coupons_save_data');
// Save data from meta box
function dv_coupons_save_data($post_id) {
    global $plugin_metaboxes;
    // verify nonce
    if (!wp_verify_nonce($_POST['dv_coupons_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    foreach ($plugin_metaboxes['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}

?>