$(document).ready(function(){
	var 	post_id		=	'';
	var 	coupon_code	=	'';
	var 	coupon_url	=	'';
	var 	uri 		= 	window.location.pathname;
 $('.copy_to_clipboard').zclip({
		beforeCopy:function(){ 
			post_id		=	$(this).attr("post_id");
			coupon_code	=	$(this).attr("coupon_code");
			coupon_url	=	$(this).attr("href");
			$(this).text(coupon_code);			
			update_clicks(post_id);
		},
		afterCopy:function(){ 
		
			if(coupon_code!='No Coupon Needed'){
				alert("Coupon code {"+coupon_code+"} is copied!");
			}
			window.open(coupon_url);
			
		},
		path:dv_coupon_active_tmpl_url+'/js/ZeroClipboard.swf',
        copy:function(){return $(this).attr("coupon_code");},
		setCSSEffects:true
    });

});