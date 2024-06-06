<?php
// AJ's Wordpress Plugin MVC Framework




class AJWPMVC {

	public function load_view($filename,$data=null) {
	    //echo 'inside view loader<br/>';
	    
	    $filename=dirname(__FILE__).'/../views/'.$filename.'.php';
	    
	    //echo dirname(__FILE__).'<br/>';
	    
	    if (is_file($filename)) {
		//echo 'inside view loader if file<br/>';
		ob_start();
		extract($data);
		include $filename;
		return ob_get_clean();
	    }
	    return false;
	}
	
	
	
		


}

