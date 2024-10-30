<?php
//------------------------------  Overriding Single/Store WP Defailt template Pages  -------------------------//
###################################################################################################################################

add_filter( 'template_include', 'override_coupon_page_template', 99 );

function override_coupon_page_template( $template ) {

		if( is_singular( 'coupons' ) ){
			
			if(get_option("dv_disable_single_view")!='true'){
				$new_template = PLUGIN_BASE . '/single-coupon.php' ;
			}
			
			return $new_template;
		
		}elseif( is_tax( 'stores') ){
		
			$new_template = PLUGIN_BASE . '/taxonomy-stores.php' ;
			return $new_template;
		}else{
			return $template;
		}
		
}	

if(get_option("dv_disable_single_view")=='true'){

	add_action( 'template_redirect', 'disable_single_view_func' );

}

function disable_single_view_func() {

	$queried_post_type = get_query_var('post_type');
	
	if ( is_singular( 'coupons' ) ) {
	
		wp_redirect( home_url(), 301 );
		exit;
	
	}

}


//-------------------------------------------------------------------------------------------------------------------------------//


?>