<?php



	$dv_general_options = array();

	$dv_general_options[] = array(	"name" => "General Settings",
									"icon" => "home",
									"type" => "page");

	$dv_general_options[] = array(	"name" => "General Settings",
									"type" => "heading");

	$dv_general_options[] = array(	"name" => "Display Likes",
									"desc" => "Check this if you want to display likes count for coupons.",
									"id" => $shortname."_display_likes",
									"std" => "",
									"type" => "checkbox");

	$dv_general_options[] = array(	"name" => "Display Dislikes",
									"desc" => "Check this if you want to display dislikes count for coupons.",
									"id" => $shortname."_display_dislikes",
									"std" => "",
									"type" => "checkbox");

	$dv_general_options[] = array(	"name" => "Display Clicks",
									"desc" => "Check this if you want to display total clicks on coupon codes.",
									"id" => $shortname."_display_clicks",
									"std" => "",
									"type" => "checkbox");

	$dv_general_options[] = array(	"name" => "Use Timthumb Library",
									"desc" => "This will resize your coupon images using timthumb library.",
									"id" => $shortname."_use_timthumb",
									"std" => "",
									"type" => "checkbox");

	$dv_general_options[] = array(	"name" => "Use Theme's CSS",
									"desc" => "The coupon snippet will use style sheets added to current theme.",
									"id" => $shortname."_use_theme_css",
									"std" => "",
									"type" => "checkbox");


	$dv_general_options[] = array(	"name" => "Use Cloaked Url",
									"desc" => "Turn it on to use cloaked (SEO friendly) links",
									"id" => $shortname."_use_cloaked_url",
									"std" => "",
									"type" => "checkbox");

	$dv_general_options[] = array(	"name" => "Disable Single Post For Coupons",
									"desc" => "Turn it on to disable single post views of coupons. (Good for SEO)",
									"id" => $shortname."_disable_single_view",
									"std" => "",
									"type" => "checkbox");

	$dv_general_options[] = array(	"name" => "Use Store Images for coupons?",
									"desc" => "Turn this on to use store image for coupons (Not: If store image is not set then it will use coupon's image).",
									"id" => $shortname."_use_store_imgs",
									"std" => "",
									"type" => "checkbox");


	$dv_general_options[] = array(	"type" => "heading_end");

	$dv_general_options[] = array(	"type" => "page_end");




	$dv_general_options[] = array(	"name" => "Social Sharing",
									"icon" => "internet",
									"type" => "page");

	$dv_general_options[] = array(	"name" => "Social Sharing",
									"type" => "heading");


	$dv_general_options[] = array(	"name" => "Twitter",
									"desc" => "Allow people to share coupon on Twitter?",
									"id" => $shortname."_dis_twitter",
									"std" => "",
									"type" => "checkbox");

	$dv_general_options[] = array(	"name" => "Facebook",
									"desc" => "Allow people to share coupon on Facebook?",
									"id" => $shortname."_dis_facebook",
									"std" => "",
									"type" => "checkbox");

	$dv_general_options[] = array(	"name" => "Google Plus",
									"desc" => "Allow people to share coupon on Google Plus?",
									"id" => $shortname."_dis_gplus",
									"std" => "",
									"type" => "checkbox");


	$dv_general_options[] = array(	"type" => "heading_end");

	$dv_general_options[] = array(	"type" => "page_end");




	$dv_general_options[] = array(	"name" => "Templates Settings",
									"type" => "page",
									"icon" => "templates");

	$dv_general_options[] = array(	"name" => "Templates Settings",
									"type" => "heading");

if ($handle = opendir(PLUGIN_BASE.'/templates')) {
	while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != "..") {
			if(strpos($entry,'.') == false) {
				$rr[]	= "$entry";
			}
		}
	}
	closedir($handle);
}


	$dv_general_options[] = array(	"name" => "Select Template",
									"desc" => "Select a template from bellow",
									"id" => $shortname."_coupon_template",
									"std" => '',
									"options" => $rr,
									"type" => "select");



	$dv_general_options[] = array(	"type" => "heading_end");





	$dv_general_options[] = array(	"name" => "Install New Template",
									"type" => "heading");

	$dv_general_options[] = array(	"name" => "Upload Template (.zip file)",
									"desc" => "Upload zip file of your template here.",
									"id" => $shortname."_upload_template",
									"std" => '',
									"no_up" => TRUE,
									"type" => "zip");


	$dv_general_options[] = array(	"type" => "heading_end");






	$dv_general_options[] = array(	"type" => "page_end");



	$dv_general_options[] = array(	"name" => "Need Help?",
									"icon" => "api",
									"type" => "page");

	$dv_general_options[] = array(	"name" => "Need help setting up things?",
									"type" => "heading");

	$dv_general_options[] = array(	"name" => "Support",
									"desc" => "<img src='http://designsvalley.com/wp-content/uploads/slide3.jpg' width='100%' height=''/>If you are having trouble in setting up this plugin on your site, or you want to custimze the look and feel. We are always here to help you. You can surely contact us via our contact us page and let us modify create your coupon website. <a href='http://designsvalley.com/'>Designs Valley</a> <br /><br /> Or you can simply email us at <a href='mailto:support@designsvalley.com'>support@designsvalley.com</a>",
									"id" => $shortname."_having_trouble",
									"type" => "html");


	$dv_general_options[] = array(	"type" => "heading_end");

	$dv_general_options[] = array(	"type" => "page_end");



	$dv_general_options[] = array(	"name" => "Compatible Themes",
									"icon" => "api",
									"type" => "page");

	$dv_general_options[] = array(	"name" => "Here are some themes compatible with this plugin",
									"type" => "heading");

	$dv_general_options[] = array(	"name" => "Themes",
									"desc" => '<div class="compatible_themes" style="border:solid 2px #7D7B7B; margin-bottom:20px; padding:0 30px;">
	<a href="http://themeforest.net/item/couponxl-coupons-deals-discounts-wp-theme/10721950?ref=shahzad11" target="_blank"><img src="https://image-tf.s3.envato.com/files/146633336/CouponXL_WP_Preview/01_couponxl_wp_preview.__large_preview.jpg" width="590" height="300" alt="Click and see that theme"/></a>
    <h2>CouponXL Theme</h2>
    <p>CouponXL is the most complete deals, discounts and coupons Wordpress theme. It is specialized for selling deals, discounts and coupons online. Also it is optimized and perfect for affiliates websites. Affiliate or discount, coupon or deal websites no difference, they will work perfect with CouponXL wordpress theme for coupons, discounts and deals. </p>
<p>
It is fully responsive, SEO optimised, followed latest web technologies based on Bootstrap framework, clean code and light speed fast. CouponXL using premium PSD design for coupons, discount and deals valued 13$, it is included with this package.</p>

<a href="http://themeforest.net/item/couponxl-coupons-deals-discounts-wp-theme/10721950?ref=shahzad11" target="_blank">
<img src="http://designsvalley.com/wp-content/uploads/moreinfo.jpg" width="200" height="63" alt="Click and see that theme" />
</a>
</div>

<div class="compatible_themes" style="border:solid 2px #7D7B7B; margin-bottom:20px; padding:0 30px;">
	<a href="http://themeforest.net/item/couponize-responsive-coupons-and-promo-theme/5306580?ref=shahzad11" target="_blank"><img src="https://image-tf.s3.envato.com/files/121555626/prev%20cosmo.__large_preview%202.__large_preview.__large_preview.__large_preview.__large_preview.__large_preview.png" width="590" height="300" alt="Click and see that theme"/></a>
  <h2>Couponize - Responsive Coupons and Promo Theme</h2>
    <p><strong>Couponize</strong> is a responsive WordPress template which allows you to store coupons, promo codes and discounts from different brands and companies. Couponize is what you are looking for if you&rsquo;re looking to release a <strong>coupons</strong> and <strong>discounts</strong> website and want to be unique among the competition. <strong>It fully works with the latest WordPress 3.6!!!</strong></p>
    <p>Being <strong>fully responsive</strong>, it promises to be bullet-proof on both desktop and mobiles. It comes with a blog page and a blog post page for your personal use.</p>

<a href="http://themeforest.net/item/couponize-responsive-coupons-and-promo-theme/5306580?ref=shahzad11" target="_blank">
<img src="http://designsvalley.com/wp-content/uploads/moreinfo.jpg" width="200" height="63" alt="Click and see that theme" />
</a>
</div>

<div class="compatible_themes" style="border:solid 2px #7D7B7B; margin-bottom:20px; padding:0 30px;">
	<a href="http://themeforest.net/item/couponhut-coupons-deals-wordpress-theme/12876388?ref=shahzad11" target="_blank"><img src="https://image-tf.s3.envato.com/files/182947013/couponhut_preview.__large_preview.png" width="590" height="300" alt="Click and see that theme"/></a>
  <h2>CouponHut - Coupons & Deals WordPress Theme</h2>
    <p><strong>CouponHut</strong> is modern and clean Wordpress theme, created with great attention to detail to individualize and set you apart. Featuring an easy page builder, two types of deals (<strong>coupons</strong> and <strong>discounts</strong>), one click install and multitude of custom widgets so you can customize your site just the way you want to. Combined with a rating system and a fantastic options panel, <strong>CouponHut</strong> will be your first and last stop for coupons and deals theme.</p>

<a href="http://themeforest.net/item/couponhut-coupons-deals-wordpress-theme/12876388?ref=shahzad11" target="_blank">
<img src="http://designsvalley.com/wp-content/uploads/moreinfo.jpg" width="200" height="63" alt="Click and see that theme" />
</a>
</div>',
									"id" => $shortname."_compatible_themes",
									"type" => "html");


	$dv_general_options[] = array(	"type" => "heading_end");

	$dv_general_options[] = array(	"type" => "page_end");
