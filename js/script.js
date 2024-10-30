function print_mwc_snippet() {
	
	var newWindow = window.open();
	newWindow.document.write(document.getElementById("mwc_printable_wrapper").innerHTML);
	newWindow.print();

}
/*
jQuery(document).ready(function(e) {
    
	jQuery('#print_coupon_form').on( "submit", function( event ) {
		
		event.preventDefault();
		var data	=	jQuery('#print_coupon_form').serializeArray();
		console.log(data);
		
		return false;
	
	});

});*/

function make_coupons_clickable(){
	
	jQuery('.coupon-container').each(function(index, element) {
		
		var coupon_id				=	jQuery(element).data('coupon_id');
		var coupon_code				=	jQuery(element).data('coupon_code');
		var coupon_title			=	jQuery(element).data('coupon_title');
		var coupon_discount			=	jQuery(element).data('coupon_discount');
		var expiry_date				=	jQuery(element).data('expiry_date');
		var coupon_text_contents	=	jQuery(element).data('coupon_text_contents');
		
		jQuery(element).addClass('clickable_coupons');
		jQuery(element).append('<label class="tick"for="chk_bx_'+coupon_id+'"></label>');
		jQuery(element).append('<label class="tick_check" for="chk_bx_'+coupon_id+'"></label>');
		jQuery(element).append('<input type="checkbox" name="chk_bx_'+coupon_id+'" onchange="tick_the_snippet(\''+coupon_id+'\')" style="display: none;" id="chk_bx_'+coupon_id+'" />');
		
		jQuery("#print_coupon_cpanel").show();
		
/*		coupon_data	=	[{
							"coupon_id": coupon_id,
							"coupon_code": coupon_code,
							"coupon_title": coupon_title,
							"coupon_discount": coupon_discount,
							"expiry_date": expiry_date,
							"coupon_text_contents": coupon_text_contents
						}];

		jQuery('#chk_bx_'+coupon_id).val(JSON.stringify(coupon_data));*/
		
		//jQuery('.coupon-container').prepend();

		
    });
	
}

function tick_the_snippet(coupon_id){
	
	jQuery('.coupon-container[data-coupon_id='+coupon_id+']').toggleClass('checked');
	jQuery('.coupon-container[data-coupon_id='+coupon_id+']').find('.tick').toggleClass('checked');
	
}
	
	//print_hide_mwc