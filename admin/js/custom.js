jQuery(document).ready(function(e) {
    
	var tooltips = jQuery( ".m_info" ).tooltip();
	
	jQuery(function() {
		jQuery( "#tabs" ).tabs();
	});
	
	jQuery(".success").fadeOut(3000);
	
	jQuery(".choosen-select").chosen();		

});

function update_img(img_id, img_src){
	jQuery(document).ready(function(e) {
		jQuery('#'+img_id).attr('src', img_src);
		return false;
	});	
}


function install_template(url){
	
	if(url==''){
		alert('No template is selected.');
	}else{
		
		jQuery.ajax({
			type:	'POST',
			cache:	false,
			url:	Template_Ajax.ajaxurl,
			data:'action=ajax-installsinputtitleSubmit&nextNonce='+Template_Ajax.nextNonce+'&url='+url,
			beforeSend:function(){
				jQuery("#loading_id").addClass('dvao_loading');
			},
			success:function(ifr){
				if(ifr=='failed'){
					alert('Installation Failed.');
				}else{
					jQuery('.dv_admin_options form').append('<div class="success">Template Installed !</div>');
					jQuery('#loading_id').removeClass('dvao_loading');
					jQuery('.dv_admin_options form .success').fadeOut(5000);
				}
			}
			
		});	
		
		
	}
	
}