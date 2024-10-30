<?php
/*
function hook_actions_in_init_ajax(){
	wp_enqueue_script( 'my-ajax-handle', PLUGIN_DIR . 'js/dv_coupons.js'  );
	wp_localize_script( 'my-ajax-handle', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'nextNonce'     => wp_create_nonce( 'theajax-next-nonce' ) ) );
	// THE AJAX ADD ACTIONS
	add_action( 'wp_ajax_the_ajax_hook', 'the_action_function' );
	add_action( 'wp_ajax_nopriv_the_ajax_hook', 'the_action_function' ); // need this to serve non logged in users
}
add_action('init','hook_actions_in_init_ajax');
// THE FUNCTION
*/

function dv_hook_ajax_mess_in_init(){
	add_action( 'wp_enqueue_scripts', 'dv_the_ajax_func' );  
	add_action( 'wp_ajax_mapsinputtitleSubmit2', 'dv_ajax_result' );
	add_action( 'wp_ajax_nopriv_mapsinputtitleSubmit2', 'dv_ajax_result' );
}
add_action('init','dv_hook_ajax_mess_in_init');

function dv_the_ajax_func() {

    wp_enqueue_script( 'dv_ajax_js', PLUGIN_DIR . 'js/dv_coupons.js', array( 'jquery' ));	
    wp_localize_script( 'dv_ajax_js', 'DVC_Ajax', array(
        'ajaxurl'       => admin_url( 'admin-ajax.php' ),
        'nextNonce'     => wp_create_nonce( 'dvcajax-next-nonce' ))
    );
	
}

function getuserIP_a(){

		$rem = @$_SERVER["REMOTE_ADDR"];
		$ff = @$_SERVER["HTTP_X_FORWARDED_FOR"];
		$ci = @$_SERVER["HTTP_CLIENT_IP"];
		if(preg_match('/^(?:192\.168|172\.16|10\.|127\.)/', $rem)){ 
			if($ff){ return $ff; }
			if($ci){ return $ci; }
			return $rem;
		} else {
			if($rem){ return $rem; }
			if($ff){ return $ff; }
			if($ci){ return $ci; }
			return "UNKNOWN";
		}
}

 function dv_ajax_result(){
	$nonce = $_POST['nextNonce']; 	
	if ( ! wp_verify_nonce( $nonce, 'dvcajax-next-nonce' ) )
		die ( 'Sorry, Server is busy. Try again after few minutes!');

	 
	$postid	=	$_POST['postid'];
	
	$user_ip			=	getuserIP_a();
	$liked_dis_people	=	get_post_meta($postid, "liked_dis_people", true);
	$clicked_people		=	get_post_meta($postid, "clicked_people", true);
	$is_avail			=	strpos($liked_dis_people, $user_ip);
	$is_avail_clicked	=	strpos($clicked_people, $user_ip);
	 
	switch($_POST['type']){
				
			case "like":
				
				if( !($is_avail !== false) ){
					update_post_meta($postid, "liked_dis_people", $liked_dis_people.'|'.$user_ip);
					$current_likes	=	get_post_meta($postid, "likes", true)+1;
					update_post_meta($postid, "likes", $current_likes);
				}else{
					$current_likes	=	get_post_meta($postid, "likes", true)+0;
				}
				
			break;
			
			case	"dislike":
				
				if( !($is_avail !== false) ){
					update_post_meta($postid, "liked_dis_people", $liked_dis_people.'|'.$user_ip);
					$current_likes	=	get_post_meta($postid, "dislikes", true)+1;
					update_post_meta($postid, "dislikes", $current_likes)+1;
				}else{
					$current_likes	=	get_post_meta($postid, "dislikes", true);
				}
				
			break;
					
			case	"clicks":
				
				if( !($is_avail_clicked !== false) ){
					update_post_meta($postid, "clicked_people", $clicked_people.'|'.$user_ip);
					$current_likes	=	get_post_meta($postid, "clicks", true)+1;
					update_post_meta($postid, "clicks", $current_likes);
					$current_likes	=	$current_likes .' Clicks';
				}else{
					$current_likes	=	get_post_meta($postid, "clicks", true).' Clicks';
				}
			
			break;
		
	}
	 
	 echo $current_likes;
	 die();
 }
 
 ?>