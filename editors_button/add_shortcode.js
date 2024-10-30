(function() {
	tinymce.create('tinymce.plugins.dv_coupon_shortcode', {
		init : function(ed, url) {

			ed.addButton('add_coupon_shortcode', {
				title : 'Add DV Coupon Shortcode',
				cmd : 'add_coupon_shortcode',
				image : url + '/icon.png'
			});


			ed.addCommand('add_coupon_shortcode', function() {


								ed.windowManager.open( {
									title: 'Insert DV Coupon Shortcode',
									body: [
										{
											type: 'listbox',
											name: 'category',
											label: 'Store',
											'values': all_stores.stores
										},
										{
											type: 'textbox',
											name: 'limit',
											label: 'Number of coupon to show on one page',
											value: '10'
										},
										{
											type: 'listbox',
											name: 'orderby',
											label: 'Order By',
											'values': [
														{text: 'Date (Default)', value: 'date'},
														{text: 'ID', value: 'ID'},
														{text: 'Title', value: 'title'},
														{text: 'Modified', value: 'modified'},
														{text: 'Random', value: 'rand'},
														{text: 'Menu Order', value: 'menu_order'}
															
													]
										},
										{
											type: 'listbox',
											name: 'order',
											label: 'Order',
											'values': [
														{text: 'Descending', value: 'DESC'},
														{text: 'Ascending', value: 'ASC'}
														
													]
										}
										
									],
									onsubmit: function( e ) {
										if(e.data.category!=='all'){
											
											if(e.data.limit!==''){
												ed.insertContent( '[coupons store="' + e.data.category + '" limit="'+ e.data.limit +'" orderby="'+e.data.orderby+'" order="'+e.data.order+'"]');
											}else{
												ed.insertContent( '[coupons store="' + e.data.category + '" orderby="'+e.data.orderby+'" order="'+e.data.order+'"]');
											}
										
										}else{
											
											if(e.data.limit!==''){
												ed.insertContent( '[coupons limit="'+ e.data.limit +'" orderby="'+e.data.orderby+'" order="'+e.data.order+'"]');
											}else{
												ed.insertContent( '[coupons orderby="'+e.data.orderby+'" order="'+e.data.order+'"]');
											}
											
										}
									}
								});
								
				
				
			});
		},

		createControl : function(n, cm) {
			return null;
		},
		
		getInfo : function() {
			return {
				longname : 'DV Coupon Shortcode Buttons',
				author : 'Designsvalley Team',
				authorurl : 'http://designsvalley.com/',
				infourl : 'http://designsvalley.com/',
				version : "1"
			};
		}


	});
	// Register plugin
	tinymce.PluginManager.add( 'dv_coupon_shortcode', tinymce.plugins.dv_coupon_shortcode );
})();