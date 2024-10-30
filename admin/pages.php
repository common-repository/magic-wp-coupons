<?php
	
	
//---------------------------------------  Function which is called on admin menu link ------------------------------------------//
###################################################################################################################################	
	function	call_back_function(){
		global	$html;
		global  $util;
		echo 	'<div class="wrap">';
				$html->html_tag("Text Heading","h1", array("id"=>"test"));
				$util->notification("Success", "success");
				$util->notification("Info", "info");
				$util->notification("Warning");
		echo 	'</div>';
	}
//-------------------------------------------------------------------------------------------------------------------------------//

?>