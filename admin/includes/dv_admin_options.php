<?php
function hrw_enqueue()
{
  wp_enqueue_style('thickbox');
  wp_enqueue_script('media-upload');
  wp_enqueue_script('thickbox');
  // moved the js to an external file, you may want to change the path
}
add_action('admin_enqueue_scripts', 'hrw_enqueue');

function dv_admin_options_page($options){
	global $themename;
	global $title;


?>

<script type="text/javascript">

jQuery('#jquery-ui-style-css').remove();

</script>

<div id="wrapper" class="dv_admin_options">
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="dvao_form_vals" name="dvao_form_vals">
            <?php if ( isset($_REQUEST['saved']) ) { ?><div class="success">Options Saved</div><?php } ?>

			<span class="clear"></span>
            <div id="headers">
                <div id="title"><?php echo $title; ?> </div>
                <input type="hidden" name="action" value="save" />
                <input type="hidden" name="saved" value="true" />
                <input type="button" onClick="dvao_proccess_admin_data('d_val')" value="Save Changes" name="save" class="save" />
                <span class="clear"></span>
            </div>

            <div id="tabs">

                <ul>
                <?php $i	=	1; foreach ($options as $test){ ?>
                	<?php if ( $test['type'] == "page" ) { ?>
	                    <li><a href="#tabs-<?php echo $i; if($test['icon']!=''){ ?>" rel="<?php echo $test['icon']; ?>" style="background-image:url(<?php echo PLUGIN_DIR.'admin/icons/'.$test['icon'],'.png'; ?>)" <?php } ?>><?php echo $test['name']; ?></a></li>
                        <?php $i++;} ?>
                    <?php } ?>

                </ul>


                	<?php $n	=	1; foreach ($options as $value) { ?>


									<?php
										switch ( $value['type'] ) {

										case 'page';
											 echo '<div id="tabs-'.$n.'">';
										break;


										case 'heading';
											echo '<div class="page_section">';
												echo '<h2 class="section_title">'.$value['name'].'</h2>';
										break;

										case 'text':
									?>
                                    <div class="opt_element">
		        							<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" class="d_val" value="<?php if($value['no_val']!="true"){ if ( get_option( $value['id'] ) != "") { echo get_option($value['id']); } else { echo $value['std']; }  }else{ echo $value['static_val']; }?>" title="<?php echo $value['desc']; ?>" />
                                            <span class="m_info" title="<?php echo $value['desc']; ?>"></span>
									</div>
									<?php
										break;

										case 'select':
									?>
    <div class="opt_element opt_check">
        <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
        <select title="<?php echo $value['desc']; ?>" name="<?php echo $value['id']; ?>" class="choosen-select d_val" id="<?php echo $value['id']; ?>" <?php echo ($value['multiple']=="true" ? 'multiple' : ''); ?>>

		<?php

		if($value['multiple']=="true"){

				$mu_arr2	=	explode(',', get_option( $value['id'] ));

				foreach ($value['options'] as $option) { ?>
					<option<?php if ( in_array($option, $mu_arr2) ) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
				<?php }

		}else{

				foreach ($value['options'] as $option) { ?>
					<option<?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
				<?php }
		}

		 ?>

        </select>
        <span class="m_info" title="<?php echo $value['desc']; ?>"></span>
    </div>

								    <?php
										break;

										case 'button':
									?>
                                    <div class="opt_element" style="padding-bottom:0;">

                                        <input type="button" class="button <?php echo $value['el_classes']; ?>" value="<?php echo $value['name']; ?>" id="<?php echo $value['el_ids']; ?>" onClick="<?php echo $value['onclick']; ?>" title="<?php echo $value['desc']; ?>" />
                                        <span class="m_info" title="<?php echo $value['desc']; ?>"></span>
									</div>
									<?php
										break;
										case 'textarea':

										$ta_options = $value['options'];
									?>
                                    <div class="opt_element">
										<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                        <textarea class="d_val" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="<?php echo $ta_options['cols']; ?>" rows="8"><?php  if( get_option($value['id']) != "") { echo stripslashes(get_option($value['id'])); } else { echo $value['std']; } ?></textarea>
                                        <span class="m_info" title="<?php echo $value['desc']; ?>"></span>
                                    </div>
									<?php
										break;
										case "radio":
 										?>
                        <div class="opt_element">
                        	<label><?php echo $value['name']; ?></label>
                        	<div class="styled_dv_chkc">

                            <?php
                            foreach ($value['options'] as $key=>$option) {
                                        $radio_setting = get_option($value['id']);
                                        if($radio_setting != '') {
                                            if ($key == get_option($value['id']) ) { $checked = "checked=\"checked\""; } else { $checked = ""; }
                                        } else {
                                            if($key == $value['std']) { $checked = "checked=\"checked\""; } else { $checked = ""; }
                        } ?>
                                <input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $key; ?>" id="<?php echo $key; ?>" class="css-checkbox d_val" <?php echo $checked; ?> /><label for="<?php echo $key; ?>" class="css-label" title="<?php echo $value['desc']; ?>"><?php echo $option; ?></label>




									<?php }?>
                    </div>               <span class="clear"></span> <span class="m_info" title="<?php echo $value['desc']; ?>"></span> </div>
									<?php
										break;
										case "checkbox":
										if( get_option($value['id']) == 'true' ) { $checked = "checked=\"checked\"";  } else { $checked = ""; }
									?>


                                    <div class="opt_element">
	                                    <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                    	<div class="slideThree">
                                            <input type="checkbox" <?php echo $checked; ?> class="checkbox d_val" value="true" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" title="<?php echo $value['desc']; ?>" />
                                            <label for="<?php echo $value['id']; ?>"></label>
                                        </div>

                                        <span class="m_info" title="<?php echo $value['desc']; ?>"></span>

                                    </div>
									<?php
										break;
										case "multicheck":
 										?><div class="opt_element"><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><?php
										foreach ($value['options'] as $key=>$option) {
	 											$sb_key = $value['id'] . '_' . $key;
												$checkbox_setting = get_option($sb_key);
 												if($checkbox_setting != '') {
		    											if (get_option($sb_key) ) { $checked = "checked=\"checked\""; } else { $checked = ""; }
														} else { if($key == $value['std']) { $checked = "checked=\"checked\""; } else { $checked = ""; }
												} ?>
	            					<input type="checkbox" class="checkbox d_val" name="<?php echo $sb_key; ?>" id="<?php echo $sb_key; ?>" value="true" <?php echo $checked; ?> /><label for="<?php echo $sb_key; ?>" title="<?php echo $value['desc']; ?>"><?php echo $option; ?></label><br />
									<?php } ?>
                                    	<span class="m_info" title="<?php echo $value['desc']; ?>"></span>
									</div>

<?php										break;

										case 'color_picker';
?>
<script type="text/javascript">

jQuery(document).ready(function(e) {

	jQuery('.colorSelector').ColorPicker({
		color: '#<?php echo $value['default'] ?>',
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {

			jQuery('.<?php echo $value['id']; ?> div').css('backgroundColor', '#' + hex);
			jQuery('#<?php echo $value['id']; ?>').val(hex);;

		}
	});

});
</script>
<div class="opt_element">
        <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="hidden" class="d_val" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option($value['id']); } else { echo $value['default']; } ?>" title="<?php echo $value['desc']; ?>" />

	<div class="colorSelector <?php echo $value['id']; ?>"><div style="background-color: #<?php if ( get_option( $value['id'] ) != "") { echo get_option($value['id']); } else { echo $value['default']; } ?>;"></div></div>
    	<span class="m_info" title="<?php echo $value['desc']; ?>"></span>
 </div>

<?php
										break;


										case 'upload';
?>
<script type="text/javascript">
var image_field2;
jQuery(function($){
  $(document).on('click', 'input.<?php echo $value['id']; ?>', function(evt){
    image_field2 = $('.<?php echo $value['id']; ?>input');
    tb_show('', 'media-upload.php?TB_iframe=1');
    return false;
  });
  window.send_to_editor = function(html) {
    imgurl = $('img', html).attr('src');
    image_field2.val(imgurl);
	$( image_field2 ).trigger( "click" );
    tb_remove();
  }
});



</script>
<div class="opt_element">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
    <div class="right_cl" style="width: 605px;">
<?php
	  	if ( get_option( $value['id'] ) != "") {
			echo '<span style="display: inline-block; overflow: hidden; line-height: 0;">';
	  				echo '<img id="'.$value['id'].'iimg" src="'. get_option($value["id"]) .'" width="90" />';
			echo '</span>';
			echo '<span style="clear:both; display:block; float: none;"></span>';
		}
?>
      <input type="text" class="<?php echo $value['id']; ?>input d_val" onClick="update_img('<?php echo $value['id'].'iimg'; ?>', this.value)" onChange="" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option($value['id']); } else { echo $value['default']; } ?>" title="<?php echo $value['desc']; ?>" />
      <input type="button" class="<?php echo $value['id']; ?> button" value="Select Image" />
	</div>
    <span class="m_info" title="<?php echo $value['desc']; ?>"></span>
 </div>

<?php
										break;




										case 'zip';
?>
<script type="text/javascript">
var image_field2;
jQuery(function($){
  jQuery(document).on('click', 'input.<?php echo $value['id']; ?>', function(evt){
    image_field2 = $('.<?php echo $value['id']; ?>input');
    tb_show('', 'media-upload.php?TB_iframe=1');
    return false;
  });
  window.send_to_editor = function(html) {
	console.log(html);
    imgurl = jQuery(html).attr('href');
    image_field2.val(imgurl);
	jQuery( image_field2 ).trigger( "click" );
    tb_remove();
  }
});



</script>
<div class="opt_element">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
    <div class="right_cl" style="width: 605px;">

        <input type="text" class="<?php echo $value['id']; ?>input <?php if($value['no_up']!==TRUE){ echo 'd_val'; } ?>" onClick="update_img('<?php echo $value['id'].'iimg'; ?>', this.value)" onChange="" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option($value['id']); } else { echo $value['default']; } ?>" title="<?php echo $value['desc']; ?>" />

        <input type="button" class="<?php echo $value['id']; ?> button" value="Select File" />

        <input type="button" class="button" value="Install" id="install_bt" onclick="install_template(jQuery('#dv_upload_template').val())" title="Click here to install the selected template.">

        <span id="loading_id"></span>

    </div>
    <span class="m_info" title="<?php echo $value['desc']; ?>"></span>

 </div>

<?php
										break;






										case 'range';
?>
<div class="opt_element range_slider_container">
<script>
jQuery(function() {
	jQuery( "#<?php echo $value['id']; ?>" ).slider({
		range: "max",
		min: <?php echo ($value['min_num']!='' ? $value['min_num'] : '2'); ?>,
		max: <?php echo ($value['max_num']!='' ? $value['max_num'] : '16'); ?>,
		value: <?php echo (get_option( $value['id'] ) != "" ? get_option( $value['id'] ) : $value['default']); ?>,
		step: <?php echo ($value['interval']!='' ? $value['interval'] : '2'); ?>,
		slide: function( event, ui ) {
				jQuery( ".<?php echo $value['id']; ?>" ).val( ui.value );
				jQuery( ".<?php echo $value['id']; ?>val" ).val( ui.value );
			}
	});

	jQuery( ".<?php echo $value['id']; ?>" ).val( jQuery( "#<?php echo $value['id']; ?>" ).slider( "value" ) );

});
</script>

  <label><?php echo $value['name']; ?></label>
  <input type="text" disabled class="<?php echo $value['id']; ?>val slider_visible_val" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option($value['id']); } else { echo $value['default']; } ?>" title="<?php echo $value['desc']; ?>" />

  <input type="hidden" class="<?php echo $value['id']; ?> d_val" name="<?php echo $value['id']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option($value['id']); } else { echo $value['default']; } ?>">

	<div id="<?php echo $value['id']; ?>" class="dv_slider"></div>
    <span class="m_info" title="<?php echo $value['desc']; ?>"></span>
</div>

<?php
										break;

										case 'html': ?>
											<div class="opt_element">
												<?php echo $value['desc']; ?>
											</div>
									<?php
										break;

										case "heading_end":
												echo '</div>';
										break;

										case "page_end":
												echo '</div>';
												$n++;
										break;

									} ?>


							<?php } ?>


                   </div></form> <span class="clear"></span></div>
<?php
}

$dv_categories_obj = get_categories('hide_empty=0');
$dv_categories = array();
foreach ($dv_categories_obj as $dv_cat) {
	$dv_categories[$dv_cat->cat_ID] = $dv_cat->cat_name;
}
	$categories_tmp = array_unshift($dv_categories, "Select a category:");

include("data_options.php");

	function dv_admin_options() {

		global $dv_general_options;
		dv_admin_options_page($dv_general_options);


	}


?>
