<?php
################################################
#	This is new admin panel with tabs.
#	@author: Designsvalley Team
################################################



/*
 *		INCLUDING REQUIRED FILES
 */
include("includes/dv_admin_options.php");


/*
 *		ENQUEUE SCRITPS
 */
function scripts_for_admin_options() {

	wp_enqueue_script( 'jquery-ui', PLUGIN_DIR.'admin/js/jquery-ui.js', array( 'jquery' ) );
	wp_enqueue_style( 'custom_styles_for_admin_options', PLUGIN_DIR.'admin/css/style.css');

	wp_enqueue_script( 'colorpicker-script-dv-options', PLUGIN_DIR.'admin/js/colorpicker.js', array( 'jquery' ) );
	wp_enqueue_script( 'eye-script-dv-options', PLUGIN_DIR.'admin/js/eye.js', array( 'jquery' ) );
	wp_enqueue_script( 'layout-script-dv-options', PLUGIN_DIR.'admin/js/layout.js', array( 'jquery' ) );
	wp_enqueue_script( 'utils-script-dv-options', PLUGIN_DIR.'admin/js/utils.js', array( 'jquery' ) );
	wp_enqueue_script( 'chosen-script-dv-options', PLUGIN_DIR.'admin/js/chosen.jquery.js', array( 'jquery' ) );
	wp_enqueue_script( 'custom_scripts_for_admin_options', PLUGIN_DIR.'admin/js/custom.js', array( 'jquery' ) );

	wp_enqueue_style( 'colorpicker-style-dv-options', PLUGIN_DIR.'admin/css/colorpicker.css');
	wp_enqueue_style( 'layout-style-dv-options', PLUGIN_DIR.'admin/css/layout.css');
	wp_enqueue_style( 'chosen-style-dv-options', PLUGIN_DIR.'admin/css/chosen.css');



}
add_action( 'admin_enqueue_scripts', 'scripts_for_admin_options' );



/*
 * ADD IT TO ADMIN MENU!
 */
function dv_coupons_menu() {


	add_menu_page( PLUGIN_NAME." Dashboard",PLUGIN_MENU_TITLE, "manage_options", "dv_admin_panel", "dv_admin_options" );

}

add_action('admin_menu', 'dv_coupons_menu');



/*
 * FOR AJAX BASED SAVING
 */

add_action( 'admin_enqueue_scripts', 'show_map' );
add_action( 'wp_ajax_ajax-mapsinputtitleSubmit', 'map_ajax_result' );
add_action( 'wp_ajax_nopriv_ajax-mapsinputtitleSubmit', 'map_ajax_result' );

function show_map() {

	wp_enqueue_script( 'dv_save_option_ajax', PLUGIN_DIR.'admin/js/ajax-call.js', array( 'jquery' ) );
    wp_localize_script( 'dv_save_option_ajax', 'MAP_Ajax', array(
        'ajaxurl'       => admin_url( 'admin-ajax.php' ),
        'nextNonce'     => wp_create_nonce( 'mapajax-next-nonce' ))
    );

}

function map_ajax_result() {
	// check nonce
	$nonce = $_POST['nextNonce'];
	if ( ! wp_verify_nonce( $nonce, 'mapajax-next-nonce' ) )
		die ( 'Sorry, Server is busy. Try again after few minutes!');

		$obj		=	substr($_POST['vals'], 1, -1);
		$org_vals	=	json_decode(stripslashes($obj), true);

		foreach($org_vals as $key=>$val){

			update_option( $key, $val );

		}

		die();


}

if(! function_exists('put_cloaked_url_in_db_ajaxed') ){

	function put_cloaked_url_in_db_ajaxed($actual_url) {

			if($actual_url!=''){
				$cloaked_url	=	site_url().'?go='.md5($actual_url);

				global $wpdb;
				$arr	=	array(
								'cloaked_url'		=> $cloaked_url,
								'actaul_url'		=> $actual_url);
				$wpdb->insert( 'dv_cloaked_urls', $arr );
				return true;
			}else{
				return false;
			}

	}

}


function wpse75629_admin_notice222() {

		$output	=	'<div class="dv_fetch_success"> <a href="javascript:;" onClick="jQuery(\'.dv_fetch_success\').fadeOut(1500)" class="close">X</a>';
		$output	.=	'Data Saved !';
		$output	.=	'</div>';

		echo $output;

}
