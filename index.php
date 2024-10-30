<?php
/*
Plugin Name: Magic WP Coupons - Lite
Plugin URI: http://designsvalley.com
Description: A coupons site plugin, which generally has a shortcode to put coupon type posts where you want. It supports several coupon templates in admin panel.
Author: Shahzad Ahmad Mirza
Author URI: http://shahzadmirza.com
Version: 3.0
License: LGPLv2
*/


define("PLUGIN_NAME","DV Coupons");
define("PLUGIN_POST_TYPE","coupons");
define("PLUGIN_URL",__file__);
define("PLUGIN_BASE",dirname(__file__));
define("PLUGIN_DIR",plugin_dir_url(PLUGIN_URL));
define("PLUGIN_JS_DIR",PLUGIN_DIR."js/");
define("PLUGIN_CSS_DIR",PLUGIN_DIR."css/");
define("PLUGIN_MENU_TITLE",'DV Coupon Options');
$themename = PLUGIN_NAME;
$shortname = "dv";
$active_template_url	=	PLUGIN_DIR.'/templates/'.get_option('dv_coupon_template').'/';

include("admin/custom_post_types_with_taxonomies.php");
include("lib/wp_utitlities.php");
include("lib/utilities.class.php");
include("admin/meta_box.php");
include("lib/dv_ajax.php");
include("lib/html.php");
include("admin/cloak-manager.php");
include("editors_button/index.php");

include("admin/widgets/latest_coupons.php");
include("admin/widgets/popular_coupons.php");
include("admin/widgets/coupon_stores.php");
include("admin/includes/template_installer.php");

include("admin/admin-panel.php");

$wp_util	=	new	wp_util();
$util		=	new	utilities();
$html		=	new	Html();
include("lib/functions.php");
include("lib/shortcodes.php");

if(!class_exists('DV_Coupon_Report'))
		require_once('lib/report.class.php');		# DV Report Class | For reporting of coupons

require_once("lib/cron.php");					# For fetching coupons with cron
require_once("lib/templating.php");				# Templating single/archive coupon pages
require_once("lib/admin_reporting.php");		# Templating single/archive coupon pages
require_once("lib/dv_coupon_summary.php");		# DV Coupon Individual Summary page


//----------------------------------------------  Construct & Display  Side Bars ------------------------------------------------//
###################################################################################################################################
$wp_util->add_sidebar(array(
		'name'=>'Blog Sidebar',
		/*'id'=>	'right_sidebar',*/
		'description'=>'Sidebar to place widgets on blog page',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="blog_sidebar_widget_title">',
        'after_title' => '</h3>',
));
$wp_util->build_side_bars();
//-------------------------------------------------------------------------------------------------------------------------------//




//------------------------------------------------  Add JS & CSS to head tags ---------------------------------------------------//
###################################################################################################################################
function	add_to_head_tag(){
	global $util;
	echo '<script src="'.PLUGIN_JS_DIR.'script.js" type="text/javascript" charset="utf-8"></script>'."\n";
	echo '<link type="text/css" href="'.PLUGIN_CSS_DIR.'admin_style.css" rel="stylesheet" media="screen" />'."\n";
}
add_action('wp_head', 'add_to_head_tag');
//-------------------------------------------------------------------------------------------------------------------------------//





//---------------------------------------------------  Enqueueing Javascripts ---------------------------------------------------//
###################################################################################################################################
function modify_jquery() {
	if (!is_admin()) {
		// comment out the next two lines to load the local copy of jQuery
		wp_deregister_script('jquery');
		wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js', false, '1.8.1');
		wp_enqueue_script('jquery');

		wp_register_script('jqueryClipBoard', PLUGIN_DIR.'js/jquery.zclip.js', false, '1.1.1');
		wp_enqueue_script('jqueryClipBoard');

//		wp_register_script('magicWpCouponsCustom', PLUGIN_DIR.'templates/'.get_option("dv_coupon_template").'/custom.js', false);
		wp_enqueue_script( 'magicWpCouponsCustom', PLUGIN_DIR.'templates/'.get_option("dv_coupon_template").'/custom.js', array(), '1.0.0' );

		wp_enqueue_style( 'dv_default_template', PLUGIN_DIR.'templates/'.get_option("dv_coupon_template").'/style.css' );

//		wp_register_script('myajax', PLUGIN_DIR.'js/dv_coupons.js', false);
//		wp_enqueue_script('myajax');

	}
}
add_action('init', 'modify_jquery', 50);
//-------------------------------------------------------------------------------------------------------------------------------//




//------------------------------  Controlling Like/Dislike/etc Appearance Modified by Team Member of DV  -------------------------//
###################################################################################################################################

function check_and_control_options(){

	if(!get_option("dv_display_likes")){
		echo '<style type="text/css"> a.like{display:none !important;} </style>';
	}
	if(!get_option("dv_display_dislikes")){
		echo '<style type="text/css"> a.dislike{display:none !important;} </style>';
	}
	if(!get_option("dv_display_clicks")){
		echo '<style type="text/css"> .coupon_views{display:none !important;} </style>';
	}

}

add_action("wp_head", "check_and_control_options");

//-------------------------------------------------------------------------------------------------------------------------------//





//------------------------------  Allows active template to include functions or hook up into WP  -------------------------//
###################################################################################################################################

function include_template_functions(){

	$templates_functions = PLUGIN_BASE.'/templates/'.get_option('dv_coupon_template').'/functions.php';

	if (file_exists($templates_functions)) {
		include($templates_functions);
	}

}
add_action('init', 'include_template_functions');
//-------------------------------------------------------------------------------------------------------------------------------//


//-----------------------  Create custom DB Table for likes, dislieks, clicks and views counting timewise  ----------------------//
###################################################################################################################################

function install_report_table_for_dvc() {
   	global $wpdb;
  	$rablename = 'dv_report';

	if($wpdb->get_var("show tables like '$rablename'") != $rablename)
	{

		$sql = "CREATE TABLE " . $rablename . " (
		`id` int(255) NOT NULL AUTO_INCREMENT,
		`coupon_id` int(255) NULL,
		`ip` varchar (20) NULL,
		`type` varchar (20) NULL,
		`date_time` datetime NULL,
		UNIQUE KEY id (id)
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

}

register_activation_hook(__FILE__ , 'install_report_table_for_dvc');


//-----------------------  Add data to DV Report Table in database when update_post_meta is triggered !!!! ----------------------//
###################################################################################################################################

function add_reporting_row($coupon_id, $type){

	$ipaddress = '';
	if ($_SERVER['HTTP_CLIENT_IP'])
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if($_SERVER['HTTP_X_FORWARDED_FOR'])
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if($_SERVER['HTTP_X_FORWARDED'])
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if($_SERVER['HTTP_FORWARDED_FOR'])
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if($_SERVER['HTTP_FORWARDED'])
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if($_SERVER['REMOTE_ADDR'])
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'UNKNOWN';


	global $wpdb;
	$arr	=	array(
					'coupon_id'	=> $coupon_id,
					'ip'		=> $ipaddress,
					'type'		=> $type,
					'date_time'	=> current_time('mysql', 1)
					);
	$wpdb->insert( 'dv_report', $arr );


}

add_action( 'added_post_meta', 'add_row_in_report_table', 10, 4 );
add_action( 'updated_post_meta', 'add_row_in_report_table', 10, 4 );
function add_row_in_report_table( $meta_id, $post_id, $meta_key, $meta_value )
{

	if( $meta_key=='likes' || $meta_key=='dislikes' || $meta_key=='clicks' || $meta_key=='views' ){
			add_reporting_row($post_id, $meta_key);
	}

}


//------------------------------  Creating New Table in DB when plugin is installed - For Url Cloaking  -------------------------//
###################################################################################################################################


function install_table_for_dvc() {
   	global $wpdb;
  	$rablename = 'dv_cloaked_urls';

	// create the ECPT metabox database table
	if($wpdb->get_var("show tables like '$rablename'") != $rablename)
	{

		$sql = "CREATE TABLE " . $rablename . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`cloaked_url` varchar (255) NULL,
		`actaul_url` longtext NULL,
		`hits` varchar (7) NULL,
		UNIQUE KEY id (id)
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

}

register_activation_hook(__FILE__ , 'install_table_for_dvc');

//-------------------------------- Taxonomy Terms Meta ---------------------------------------------------------------//

class Taxonomy_Metadata {
	function __construct() {
		add_action( 'init', array($this, 'wpdbfix') );
		add_action( 'switch_blog', array($this, 'wpdbfix') );
		add_action('wpmu_new_blog', array($this, 'new_blog'), 10, 6);
	}

	/*
	 * Quick touchup to wpdb
	 */
	function wpdbfix() {
		global $wpdb;
		$wpdb->taxonomymeta = "{$wpdb->prefix}taxonomymeta";
	}

	/*
	 * TABLE MANAGEMENT
	 */

	function activate( $network_wide = false ) {
		global $wpdb;

		// if activated on a particular blog, just set it up there.
		if ( !$network_wide ) {
			$this->setup_blog();
			return;
		}

		$blogs = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs} WHERE site_id = '{$wpdb->siteid}'" );
		foreach ( $blogs as $blog_id ) {
			$this->setup_blog( $blog_id );
		}
		// I feel dirty... this line smells like perl.
		do {} while ( restore_current_blog() );
	}

	function setup_blog( $id = false ) {
		global $wpdb;

		if ( $id !== false)
			switch_to_blog( $id );

		$charset_collate = '';
		if ( ! empty($wpdb->charset) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) )
			$charset_collate .= " COLLATE $wpdb->collate";

		$tables = $wpdb->get_results("show tables like '{$wpdb->prefix}taxonomymeta'");
		if (!count($tables))
			$wpdb->query("CREATE TABLE {$wpdb->prefix}taxonomymeta (
				meta_id bigint(20) unsigned NOT NULL auto_increment,
				taxonomy_id bigint(20) unsigned NOT NULL default '0',
				meta_key varchar(255) default NULL,
				meta_value longtext,
				PRIMARY KEY	(meta_id),
				KEY taxonomy_id (taxonomy_id),
				KEY meta_key (meta_key)
			) $charset_collate;");
	}

	function new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
		if ( is_plugin_active_for_network(plugin_basename(__FILE__)) )
			$this->setup_blog($blog_id);
	}
}
$taxonomy_metadata = new Taxonomy_Metadata;
register_activation_hook( __FILE__, array($taxonomy_metadata, 'activate') );
