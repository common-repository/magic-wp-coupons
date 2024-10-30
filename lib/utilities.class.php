<?php


	class	utilities{
		
		var	$conf;
		var $js_files	=	array();
		var $css_files	=	array();
		
		function	__construct(){
			
		}
			
		function sanitize($input) {
			if (is_array($input)) {
				foreach($input as $var=>$val) {
					$output[$var] = $this->sanitize($val);
				}
			}else {
				if (get_magic_quotes_gpc()) {
					$input = stripslashes($input);
				}
				$input  = $this->clean_input($input);
				$output = mysql_real_escape_string($input);
			}
			return $output;
		}
		
		
		function clean_input($input) {

		  $search = array(
			'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
		  );
		
			$output = preg_replace($search, '', $input);
			return $output;
		  }
		
		
		function get_file_extension($file){
			$file_parts = pathinfo($file);
			$fileExt = strtolower($file_parts['extension']);
			return $fileExt;
		}
		
		
		function is_allowed_image_type($img, $allowedTypes){
			$imgInfo = getimagesize($img);
			$imgType = $imgInfo["mime"];
			$allowed = false;
			
			if(strpos($allowedTypes, $imgType) === false)
			{
				$allowed = false;
			} else {
				$allowed = true;
			}
			
			return $allowed;
		}
			
		function is_valid_email($email){
			if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){ 
				return true;
			}else{
				return false;
			}
		}
			
		
		
		/*function array_to_object($array) {
			    if(!is_array($array)) {
			        return $array;
			    }
			    
			    $object = new stdClass();
			    if (is_array($array) && count($array) > 0) {
			      foreach ($array as $name=>$value) {
			         $name = strtolower(trim($name));
			         if (!empty($name)) {
			            $object->$name = arrayToObject($value);
			         }
			      }
			      return $object; 
			    }
			    else {
			      return FALSE;
			    }
			}*/
			
			
		function 	check_empty($array){
			foreach($array as $key=>$val){
				if(empty($val)){
					$key	=	str_replace("_"," ",$key);
					$error	.=	'<li>Please fill out the <strong>'.$key.'</strong> field</li>';
				}
			}
			
			if(!empty($error)){
				$res['error']	=	$error;
				$res['status']	=	"error";
			}else{
				$res['status']	=	"noerror";
			}
			return $res;
		}

		
		function	safe_redirect($url="index.php", $type="both"){
			
			switch($type){
				case "both":				
				@header("Location:$url");
				echo '<script type="text/javascript">window.location="'.$url.'"</script>';
				break;
				
				case "javascript":
				echo '<script type="text/javascript">window.location="'.$url.'"</script>';
				break;
				
				case "header":
				@header("Location:$url");
				break;
			}
		}
		
		
		
		function	notification($message,$type=''){
			echo   '<div class="'.$type.' warning">'.$message.'</div>';
		}
		
		
		
		
		
		function	paginate($total,$current_page,$url,$arrows=false){
		
			$pages	=	ceil($total/$this->conf->records_per_page);
			
			if($arrows){
				$prev	=	($current_page>1)?'<a class="prev_page" href="'.$url.'?page='.($current_page-1).'"><</a>':'<span class="prev_page_inactive"><</span>';
				$next	=	($current_page<$pages)?'<a class="next_page" href="'.$url.'?page='.($current_page+1).'">></a>':'<span class="next_page_inactive">></span>';
			}
			
			for($i=1; $i<=$pages; $i++){
				if($current_page==$i){
					$res .=	'<span class="current_page">'.$i.'</span>';						
				}else{
					$res .=	'<a href="'.$url.'?page='.$i.'">'. $i.'</a>';
				}
			}
			
			
			echo ($pages>1)?'<div class="pages">'.$prev.$res.$next.'</div>':'';
		}
	function get_current_file_name()
	{
		$file_name	=	$_SERVER['PHP_SELF'];
		$parts		=	explode('/',$file_name);
		return	$parts[count($parts)-1];
	}
		
	}
	
	
?>