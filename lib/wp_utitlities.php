<?php


		class	wp_util{
			
			var	$menu_items				=	array();
			var	$sub_menu_items			=	array();
			var	$side_bar_params		=	array();
			function	__construct(){
				
			}
			
			function	add_menu_item($params){
				$this->menu_items[]	=	$params;
			}
			
			function	add_sub_menu_item($params){
				$this->sub_menu_items[]	=	$params;
			}
			
			
			
			function	build_admin_menu(){
				foreach($this->menu_items as $menu_item){
					add_menu_page($menu_item[0],$menu_item[1],$menu_item[2],$menu_item[3],$menu_item[4]);
				}
				
				foreach($this->sub_menu_items as $menu_item){
					add_submenu_page($menu_item[0],$menu_item[1],$menu_item[2],$menu_item[3],$menu_item[4],$menu_item[5]);
				}
			}
			
			
			function	enable_featured_image($location	=	array( 'page','post' )){
				add_theme_support( 'post-thumbnails', $location );	
			}
			
			function	add_sidebar($params){
				$this->side_bar_params[]	=	$params;
			}
			
			function	build_side_bars(){
				foreach ($this->side_bar_params	as	$side_bar_param){
					register_sidebar($side_bar_param);
				}
			}
			
		}

?>