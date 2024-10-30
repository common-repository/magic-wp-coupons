function copy_to_clipboard(text,postid)  
  {  
 
      if(window.clipboardData){  
      	window.clipboardData.setData('text',text);  
		alert('The coupon code has been copied to your clipboard.');
      }else{  
      	//alert('Could not copy coupon to your clipboard.');
	  }  
       update_clicks(postid);
	   alert('The coupon code has been copied to your clipboard.');
      return false;  
  }
  
 
  
  
/*
function update_clicks(postid){
		jQuery.post(the_ajax_script.ajaxurl, 'action=the_ajax_hook&type=clicks&postid='+postid,
			function(response_from_the_action_function){
				jQuery("#coupon_clicks"+postid).html(response_from_the_action_function);
				
			}
		);
}*/


function update_clicks(postid){

		jQuery.ajax({
			type:'POST',
			cache:false,
			url:DVC_Ajax.ajaxurl,
			data:'action=mapsinputtitleSubmit2&type=clicks&postid='+postid+'&nextNonce='+DVC_Ajax.nextNonce,
			beforeSend:function(){},
			success:function(res){
				jQuery("#coupon_clicks"+postid).html(res);	
			}
			
		});
}

function post_likes(obj, type, postid){
			
		jQuery.ajax({
			type:'POST',
			cache:false,
			url:DVC_Ajax.ajaxurl,
			data:'action=mapsinputtitleSubmit2&type='+type+'&postid='+postid+'&nextNonce='+DVC_Ajax.nextNonce,
			beforeSend:function(){
				jQuery(obj).removeClass("likes").addClass("dv_coupons_ajax_load");
			},
			success:function(res){
				
				jQuery(obj).removeClass("dv_coupons_ajax_load").addClass("likes").addClass(type+'d');
				jQuery(obj).find(' .'+type+'_count').remove();
				jQuery(obj).append('<span class="'+type+'_count">'+res+'</span>');
			}
			
		});
		
		
}

function add_to_bookmark(title, url, id){
	
	if (window.sidebar && window.sidebar.addPanel) { // Mozilla Firefox Bookmark
		window.sidebar.addPanel(title,url,'');
	} else if(window.external && ('AddFavorite' in window.external)) { // IE Favorite
		window.external.AddFavorite(url,title); 
	} else if(window.opera && window.print) { // Opera Hotlist
		this.title=title;
		return true;
	} else { // webkit - safari/chrome
		alert('Press ' + (navigator.userAgent.toLowerCase().indexOf('mac') != - 1 ? 'Command/Cmd' : 'CTRL') + ' + D to bookmark this coupon.');
	}
	
	jQuery('#'+id).addClass('clicked');

}