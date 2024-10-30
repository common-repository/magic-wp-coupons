<?php
	
//include(PLUGIN_BASE."\admin\pages.php");



//---------------------------------------  Function to generate admin menu page forms -------------------------------------------//
###################################################################################################################################

	function dv_generate_settings_page($options){
		global $themename;
		?>
			<div class="wrap">
    			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
						<h2><?php echo $themename.' General Settings'; ?></h2>
                        
						<?php if ( $_REQUEST['saved'] ) { ?><div style="clear:both;height:20px;"></div><div class="warning"><?php echo $themename; ?>'s Options has been updated!</div><?php } ?>
						<?php if ( $_REQUEST['reset'] ) { ?><div style="clear:both;height:20px;"></div><div class="warning"><?php echo $themename; ?>'s Options has been reset!</div><?php } ?>	
						<!--START: GENERAL SETTINGS-->
     						<table class="maintable">
							<?php foreach ($options as $value) { ?>
									<?php if ( $value['type'] <> "heading" ) { ?>
										<tr class="mainrow">
										<td class="titledesc"><?php echo $value['name']; ?></td>
										<td class="forminp">
									<?php } ?>	
									<?php
										switch ( $value['type'] ) {
										case 'text':
									?>
		        							<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings($value['id']); } else { echo $value['std']; } ?>" />
									<?php
										break;
										case 'select':
									?>
	            						<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
	                					<?php foreach ($value['options'] as $option) { ?>
	                						<option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
	                					<?php } ?>
	            						</select>
									<?php
										break;
										case 'textarea':
										$ta_options = $value['options'];
									?>
										<textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="<?php echo $ta_options['cols']; ?>" rows="8"><?php  if( get_settings($value['id']) != "") { echo stripslashes(get_settings($value['id'])); } else { echo $value['std']; } ?></textarea>
									<?php
										break;
										case "radio":
 										foreach ($value['options'] as $key=>$option) { 
													$radio_setting = get_settings($value['id']);
													if($radio_setting != '') {
		    											if ($key == get_settings($value['id']) ) { $checked = "checked=\"checked\""; } else { $checked = ""; }
													} else {
														if($key == $value['std']) { $checked = "checked=\"checked\""; } else { $checked = ""; }
									} ?>
	            					<input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> /><?php echo $option; ?><br />
									<?php }
										break;
										case "checkbox":
										if(get_settings($value['id'])) { $checked = "checked=\"checked\""; } else { $checked = ""; }
									?>
		            				<input type="checkbox" class="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
									<?php
										break;
										case "multicheck":
 										foreach ($value['options'] as $key=>$option) {
	 											$sb_key = $value['id'] . '_' . $key;
												$checkbox_setting = get_settings($sb_key);
 												if($checkbox_setting != '') {
		    											if (get_settings($sb_key) ) { $checked = "checked=\"checked\""; } else { $checked = ""; }
												} else { if($key == $value['std']) { $checked = "checked=\"checked\""; } else { $checked = ""; }
									} ?>
	            					<input type="checkbox" class="checkbox" name="<?php echo $sb_key; ?>" id="<?php echo $sb_key; ?>" value="true" <?php echo $checked; ?> /><label for="<?php echo $sb_key; ?>"><?php echo $option; ?></label><br />
									<?php }
										break;
										case "heading":
									?>
										</table> 
		    									<h3 class="title"><?php echo $value['name']; ?></h3>
										<table class="maintable">
									<?php
										break;
										default:
										break;
									} ?>
									<?php if ( $value['type'] <> "heading" ) { ?>
										<?php if ( $value['type'] <> "checkbox" ) { ?><br/><?php } ?><span><?php echo $value['desc']; ?></span>
										</td></tr>
									<?php } ?>	
							<?php } ?>
							</table>
							<p class="submit">
								<input name="save" type="submit" value="Save changes" />    
								<input type="hidden" name="action" value="save" />
							</p>
							<div style="clear:both;"></div>
						<!--END: GENERAL SETTINGS-->
            </form>
</div><!--wrap-->
<div style="clear:both;height:20px;"></div>
 <?php } 
 //-------------------------------------------------------------------------------------------------------------------------------//
 
 
 
 
 /**
 * Disable admin bar on the frontend of your website
 * for subscribers.
 */
function dv_disable_admin_bar() { 
	if( ! current_user_can('edit_posts') )
		add_filter('show_admin_bar', '__return_false');	
}
add_action( 'after_setup_theme', 'dv_disable_admin_bar' );
 
/**
 * Redirect back to homepage and not allow access to 
 * WP admin for Subscribers.
 */
function dv_redirect_admin(){
	if ( ! current_user_can( 'edit_posts' ) and !defined('DOING_AJAX') and !DOING_AJAX ){ # This was Conflicting with ajax for non logged in users....
		wp_redirect( site_url() );
		exit;		
	}
}
add_action( 'admin_init', 'dv_redirect_admin' );			



//---------------------------------------------------------
add_filter('user_contactmethods', 'my_user_contactmethods');
function my_user_contactmethods($user_contactmethods){
  $user_contactmethods['twitter'] = 'Twitter Username';
  $user_contactmethods['facebook'] = 'Facebook Username';
  unset($user_contactmethods['aim'],$user_contactmethods['jabber']);

  return $user_contactmethods;
}
 
 
 
 
 
 
 
add_action('category_edit_form_fields','category_edit_form_fields');
add_action('category_edit_form', 'category_edit_form');
add_action('category_add_form_fields','category_edit_form_fields');
add_action('category_add_form','category_edit_form');


function category_edit_form() {
?>
<script type="text/javascript">
jQuery(document).ready(function(){
jQuery('#edittag').attr( "enctype", "multipart/form-data" ).attr( "encoding", "multipart/form-data" );
        });
</script>
<?php 
}

function category_edit_form_fields () {
?>
    <tr class="form-field">
            <th valign="top" scope="row">
                <label for="catpic"><?php _e('Picture of the category', ''); ?></label>
            </th>
            <td>
                <input type="file" id="catpic" name="catpic"/>
            </td>
        </tr>
<?php 
    }
	
	function parse_template($tpl, $tags){
		
		foreach($tags	as $key=>$val){
			
			$tpl	=	str_replace("{".$key."}",$val,$tpl);

		}
		//$tpl		=	preg_replace("|{(.*)}|U","",$tpl);
		return	$tpl;
	
	}
	
	function add_js_var_for_plugin(){
		
		echo '<script type="text/javascript"> 
		
				var dv_coupon_plugin_url		=	"'. PLUGIN_DIR .'";
				var dv_coupon_active_tmpl_url	=	"'. PLUGIN_DIR.'templates/'.get_option('dv_coupon_template') .'";
				
		</script>';

	}
	add_action('wp_head', 'add_js_var_for_plugin');
	add_action('admin_head', 'add_js_var_for_plugin');
	

/**
 * Function to check if coupon already exists
 */

function is_already_exists_coupon($couponid){
	
	$args = array(
				'posts_per_page'   => 1,
				'meta_key'         => 'couponid',
				'meta_value'       => $couponid,
				'post_type'        => 'coupons'
	);
	
	$posts	=	get_posts($args);
	
	if(count($posts)>0){
		return true;
	}else{
		return false;
	}
	
	
}	

function add_query_var_for_printing() {
    global $wp;
    $wp->add_query_var('print_coupons');
}

add_filter('init', 'add_query_var_for_printing');



function print_coupons() {
	
	if(get_query_var( 'print_coupons' )=="true"){
		
		$html	=	'';
		foreach($_POST as $key=>$coupon){
			
			$coupon_id		=	str_replace('chk_bx_', '', $key);
			
			$html	.=	'<div class="print_snippet">';
				$html	.=	str_replace('class="snippet coupon-container"', 'class="snippet coupon-container" style="float: left; vertical-align: top;"', display_coupons($coupon_id));
				$html	.=	'<img class="coupon_qr" src="https://chart.googleapis.com/chart?chs=160x160&cht=qr&chl='.urlencode(get_post_meta($coupon_id, 'coupon_code', true)).'&choe=UTF-8" style="float: left; vertical-align: top; margin-left: 25px;  border-left: 2px dashed #000;" /><span class="clear"></span>';
			$html	.=	'</div>';
		
		}
		wp_head();
		echo $html;
		wp_footer();
		echo '	<script type="text/javascript"> 
					
					$(document).ready(function () {
						window.print();
						setTimeout("closePrintView()", 900);
					});
					function closePrintView() {
						document.location.href = "'.$_SERVER['HTTP_REFERER'].'";
					}
					
				</script>';
		exit();
		
	}

}
add_action('parse_query', 'print_coupons');
	
?>