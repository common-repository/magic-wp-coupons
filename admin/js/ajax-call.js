function dvao_proccess_admin_data(vals){
	
	
	var elements	=	document.getElementsByClassName(vals);
	var json_vals	=	'';  
	  for (i=0; i<elements.length; i++){
		
		if(elements[i].type=='checkbox' || elements[i].type=='radio'){
			
			if(elements[i].checked==true){
				
				json_vals	+=	'"'+elements[i].name+'":"'+elements[i].value+'",';
			
			}else{
				
				if(elements[i].type=='checkbox'){
				
					json_vals	+=	'"'+elements[i].name+'":"false",';
				
				}
			}

		}else if( elements[i].type=='select-multiple' ){
			
			var val22		=	jQuery(elements[i]).val();
			var coma_val	=	val22.join();
			json_vals		+=	'"'+elements[i].name+'":"'+coma_val+'",';
			
		}else{
			
			json_vals	+=	'"'+elements[i].name+'":"'+elements[i].value+'",';

		}
		
	  }
		var json_to_send	= '[{' + json_vals + '}]';
		var evaled			= eval(json_to_send);
		dvao_save_admin_panel(evaled);
	
}


function	dvao_save_admin_panel(vals){
		
		jQuery.ajax({
			   type	:	'POST',
			   cache:	false,
			   url:	MAP_Ajax.ajaxurl,
			   data:'action=ajax-mapsinputtitleSubmit&vals='+encodeURIComponent(JSON.stringify(vals))+'&nextNonce='+MAP_Ajax.nextNonce,
			   beforeSend:function(){
				  jQuery("#headers").append('<span class="dvao_loading"></span>');
			   },
			   success:function(ifr){
					jQuery('.dv_admin_options form').append('<div class="success">Options Saved</div>');
					jQuery('.dvao_loading').each(function(index, element) {
						jQuery(this).remove();
                    });
					jQuery('.dv_admin_options form .success').fadeOut(3000);
					jQuery('body').append(ifr);
				   				   
			   }
			   
		});	
		
}