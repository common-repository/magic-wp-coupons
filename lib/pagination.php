<?php 

/**
 * 
 * Class helper for getting paginated pages
 * @author ToanNM
 * @name tgt_pagination
 * @property int $current_page
 * @property string @type 'paged' (post page) or 'cpage' (comment page)
 * @property int @range is the number of nearest page from the current page can be displayed
 * @method get_paginated_pages() generate a array contain paginated pages
 * @global $wp_query
 */
class tgt_pagination {
	
	var $current_page = '';
	/***
	 * TYPE: There are two type:
	 * 	1. 'paged' - POST PAGE
	 *  2. 'cpage' - COMMENT PAGE
	 */
	var $type = ''; 
	var $range = 2;
	
	function tgt_pagination($type = 'paged', $range = 2){				
		$this->type =  $type === 'cpage' ? 'cpage' : 'paged';
		$this->range = $range;
		$paged = get_query_var($type);
		if ( !$paged )
			$paged = 1;
		$this->current_page = $paged;
	}
	
	/**
	 * generate a array contain paginated pages	 
	 * @return array of paginated pages 
	 */
	function get_paginated_pages(){
		global $wp_query;
		$curr = $this->current_page;
		$type = $this->type;
		$range = $this->range;
		$pagination = array();
		$totalPage = 0;
		
		$paged = get_query_var($type);
		if ( !$paged )
			$paged = 1;
		
		if ($type == 'paged')
		{
			$totalPage = absint($wp_query->max_num_pages);
		}else {
			$totalPage = absint($wp_query->max_num_comment_pages);
		}
		if ($totalPage == 0){
			$totalPage = 1;
		}
	
		$start 	= $paged - $range > 0 	? $paged - $range : 1;
		$end 	= $paged + $range  < $totalPage ? $paged + $range : $totalPage;
		
		if ($start != 1){
			$pagination[] = 1;
			if ($start > 2)
				$pagination[] = '...';
		}
		
		for ($i = $start; $i <= $end; $i++) {
			$pagination[] = $i ;
		}
		
		if ($end != $totalPage){
			if ($end < $totalPage - 1)
				$pagination[] = '...';				
			$pagination[] = $totalPage;
		}
		
		return $pagination;
	}
}

?>