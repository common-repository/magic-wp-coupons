<?php

class Html {
    
    function __construct(){
        
    }
    
    /**
     * print out the css tag
     * @param string $filepath: path of the css file
     */
    function css($filepath){
        echo '<link href="' . TEMPLATE_URL . '/' . $filepath . '" type="text/css" rel="stylesheet"/>';
    }
    
	
	/**
     * print out the Heading tag
     * @param string $text: The text to show in heading
	 * @param string $tag: The heading tag to build ie.. h1, h2, h3
	 * @param array  $param: extra params  eg array("class"=>"main_heading")
     */
	function	html_tag($text, $tag, $param=array() ){
		foreach($param	as	$key=>$val){
			$parameters .=	' '.$key . '="' . $val.'" ';	
		}
		echo '<'.$tag.$parameters.'>'.$text.'</'.$tag.'>';
	}
    /**
     * print out the javascrip tag
     * @param string $filepath: path of the javascript file
     */
    function js($filepath){
        echo '<script src="' . TEMPLATE_URL . '/js/' . $filepath . '" type="text/javascript"></script>';
    }
    
    /**
     * print out the meta tag
     * @param string $name: the meta name
     * @param string $content: the meta content
     */
    function meta($name, $content){
        echo "<meta name=\"$name\" content=\"$content\" />";
    }
    
    /**
     * print out the meta tag
     */
    function favicon(){
    	$filpath = TEMPLATE_URL . '/' . get_option('tgt_favicon'); 
        echo "<link href=\"$filpath\" rel=\"shortcut icon\"/>";
    }
    
    /**
     * print out the meta tag
     * @param string $charset: the charset
     */
    function charset($name){
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; $name\" />";
    }   
    
    /**
     * print out the image tag
     * @param string $src: image source
     * @param string $alt: alt attribute
     * @param array $args: more attribute
     */
    function image($src, $alt='', $args=array()){
        $link = '';
        $img = '';    
        if ($this->is_inner_link($src)){
            if (strpos($src, '/') === 0)
                $link = TEMPLATE_URL . $src;
            else
                $link = TEMPLATE_URL . "/images/". $src;
        }
        else{
            $link = $src;
        }
        
        $img .= "<img src=\"$link\" alt=\"$alt\"";
        if (!empty($args)){
            foreach ($args as $key => $arg){
                $img .= " $key=\"$arg\" ";
            }
        }
        $img .=  "/>";
        
        return $img;
    }
    
    /**
     * print out the a tag
     * @param string $label: label of the href
     * @param string $href: alt attribute
     * @param array $args: more attribute
     */
    function link($label, $href, $args = array()){
        $link = '';
        $a = '';
        if ($this->is_inner_link($href)){
            if (strpos($href, '/') != 0)
                $link = HOME_URL . "/". $href;
            else
                $link = HOME_URL . "/". $href;
        }
        else{
            $link = $href;
            if ($this->startsWith($link, 'www'))
                $link = 'http://' . $link;
        }
        
        $a .= '<a href="'. $link.'"' ;
        if (!empty($args)){
            foreach ($args as $key => $arg){
                $a .= ' ' . $key. '="' . $arg .'" ';
            }
        }
        $a .= ">" . $label . "</a>";
        return $a;
    }
    
    function startsWith($string, $prefix, $caseSensitive = false) {
	    if(!$caseSensitive) {
	        return stripos($string, $prefix, 0) === 0;
	    }
	    return strpos($string, $prefix, 0) === 0;
	}    
	
    private function is_inner_link($src){  
    	if ($this->startsWith($src, 'www') || $this->startsWith($src, 'http'))
    		return false;        
        return true;
    }
    
    
}



?>