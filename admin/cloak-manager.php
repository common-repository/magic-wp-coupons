<?php

function put_cloaked_url_in_db($post_id) {

    if( get_post_type($post_id) == 'coupons' ) {
        
		$actual_url		=	get_post_meta($post_id, 'coupon_store_url', true);
		
		if($actual_url!=''){
			$cloaked_url	=	site_url().'?go='.md5($actual_url);
			
			global $wpdb;
			$arr	=	array(	
							'cloaked_url'		=> $cloaked_url, 
							'actaul_url'		=> $actual_url);
			$wpdb->insert( 'dv_cloaked_urls', $arr );	
		}
		
    }

}

add_action( 'wp_insert_post', 'put_cloaked_url_in_db' );

function check_if_go_in_par(){
	
		
		if(isset($_GET['go']) && $_GET['go']!='' ){
			
			global $wpdb;
			$cont_cloaked_url	=	site_url().'?go='.$_GET['go'];
			$result				=	$wpdb->get_row("SELECT * FROM `dv_cloaked_urls` WHERE `cloaked_url` = '$cont_cloaked_url' ");		
			
			if(!empty($result)){
				$id					=	$result->id;
				$newurl				=	$result->actaul_url;
				$cloaked_url		=	$result->cloaked_url;
				$hits				=	$result->hits;
				
				$updated_hits		=	$hits + 1;
				
				$wpdb->query("UPDATE `dv_cloaked_urls` SET `hits`='$updated_hits' WHERE `id`='$id' ");
				
				ob_start();
				header("HTTP/1.1 301 Moved Permanently"); 
				header("location:$newurl");
				exit();
			}
			
		}
		

}
add_action('init', 'check_if_go_in_par');

