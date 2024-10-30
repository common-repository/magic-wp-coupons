<?php
/**
 * To install new template via ajax
 */

add_action( 'admin_enqueue_scripts', 'mwpc_enqeue_script' );  
add_action( 'wp_ajax_ajax-installsinputtitleSubmit', 'mwpc_ajax_result' );
add_action( 'wp_ajax_nopriv_ajax-installsinputtitleSubmit', 'mwpc_ajax_result' );
 
function mwpc_enqeue_script() {
	
	wp_enqueue_script( 'install_template_js', PLUGIN_DIR.'admin/js/fetch_coupons.js', array( 'jquery' ) );
	wp_localize_script( 'install_template_js', 'Template_Ajax', array(
		'ajaxurl'       => admin_url( 'admin-ajax.php' ),
		'nextNonce'     => wp_create_nonce( 'mapajax-next-nonce' ))
	);
	
}

function mwpc_ajax_result() {
	// check nonce
	$nonce = $_POST['nextNonce']; 	
	if ( ! wp_verify_nonce( $nonce, 'mapajax-next-nonce' ) )
		die ( 'Sorry, Server is busy. Try again after few minutes!');
		
		$url		=	$_POST['url'];
		
		$zip_file	=	 str_replace(site_url(), ABSPATH, $url);
		
		$zip = new ZipArchive;
		if ($zip->open($zip_file) === TRUE) {
			$zip->extractTo(PLUGIN_BASE.DIRECTORY_SEPARATOR.'templates');
			$zip->close();
			echo 'ok';
		}else{
			echo 'failed';
		}
		
	
	die();
	
	
}
