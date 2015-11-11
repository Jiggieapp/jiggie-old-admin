<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('showMessage')){
	function showMessage ($width=''){
		$CI 				= 	&get_instance();
		$error_message		=	$CI->session->flashdata ('error_message');
		$success_message	=	@$CI->msuccess['msg'] ? @$CI->msuccess['msg'] :$CI->session->flashdata ('success_message');
		$validation_errors  = 	validation_errors();
		$validation_errors	=	$CI->merror['error'] ? $CI->merror['error'] : (('' == trim($validation_errors))? $CI->merror['error']:$validation_errors);
		
		if ('' != trim ($error_message)){
			$data['message']	=	'<div class="alert alert-block  alert-danger fade in"><button class="close" type="button" data-dismiss="alert">×</button>'.$error_message.'</div>';
		}
		else if ('' != trim($success_message)){
			$data['message']	=	'<div class="alert  alert-block alert-success fade in"><button class="close" type="button" data-dismiss="alert">×</button>'.$success_message.'</div>';
		}else if ('' != trim ($validation_errors)){
			$data['message']	=	'<div class="alert alert-block alert-danger fade in"><button class="close" type="button" data-dismiss="alert">×</button>'.$validation_errors.'</div>';
		}else{		
			$data['message']	=	'<div style="display:none;" class="alert alert-block alert-success fade in"><button class="close" type="button" data-dismiss="alert">×</button>'.$success_message.'</div>'; 
		}
		$CI->load->view ('common/messages', $data);
	}
}

if(!function_exists('showQuickMessage')){
	function showQuickMessage ($width=''){
		$CI 				= 	&get_instance();
		$message			= 	"";
		$error_message		=	$CI->session->flashdata ('error_message');
		$success_message	=	@$CI->msuccess['msg'] ? @$CI->msuccess['msg'] :$CI->session->flashdata ('success_message');
		$validation_errors  = 	validation_errors();
		$validation_errors	=	$CI->merror['error'] ? $CI->merror['error'] : (('' == trim($validation_errors))? $CI->merror['error']:$validation_errors);
		 
		$width_px				=	('' != $width) ? ' style="width:'.$width.'px" ':'';
		#$close_spl_error	= '<div class="close_container"><a href="javascript:void(null)" class="close_spl_error" id="close_spl_error" title="Close"><img src="'.$CI->config->item ('images').'info_close.png" alt="Close" title="Close"/></a></div><div class="clear"></div>';
		#$close_spl_success	= '<div class="close_container"><a href="javascript:void(null)" class="close_spl_success" id="close_spl_success" title="Close"><img src="'.$CI->config->item ('images').'info_close.png" alt="Close" title="Close"/></a></div><div class="clear"></div>';
	
		if ('' != trim($success_message)){
			$message			.=	'<div '.$width_px.' id="show_spl_success" class="success_message"><div class="alert alert-success fade in">'.$success_message.'<button data-dismiss="alert" class="close" type="button">×</button></div></div>';
		} 
		if ('' != trim ($error_message)){
			$message		.=	'<div '.$width_px.' id="show_spl_error" class="error_message"><div class="alert alert-danger fade in">'.$error_message.'<button data-dismiss="alert" class="close" type="button">×</button></div></div>';
		} 
		
		if ('' != trim ($validation_errors)){
			$data['message']	=	'<div '.$width_px.'  id="show_spl_error" class="error_message"><div class="alert alert-danger fade in">'.$validation_errors.'<button data-dismiss="alert" class="close" type="button">×</button></div></div>';
		}
		
		$data['message']	=	$message;
		$CI->load->view ('common_messages', $data);
	}
}

if(!function_exists('general_email_body')){
	/**
	 * the common mail format for the email in the whole application
	 *
	 * @param String $data
	 * @return String $body
	 */
	function general_email_body($data){
		$CI = &get_instance();
		$body	= '<table cellpadding="0" cellspacing="0" border="0" style="border:1px solid #ccc; font:13px arial;">
					<tr>
						<td style="border-bottom:1px solid #ccc; padding:20px;"><a href="'.base_url().'" title="www.MyBookpages.com"></a></td>
					</tr>
					<tr>
						<td style="border-bottom:1px solid #ccc; padding:20px;">';
		$body	.= $data;
		$body	.= '<br/><br/><br/>
					Thanks and welcome to <a href="'.base_url().'">www.MyBookpages.com</a><br/><br/>Welcome Team <a href="'.base_url().'">www.MyBookpages.com</a>';
		$body	.= '</td>
						</tr>
						<tr>
							<td style="padding:5px; text-align:center; background:#e8e8e8">Have a question? Feel free to <a target="_blank" href="'.$CI->config->item('site_baseurl').'contact_us'.'">contact us</a></td>
						</tr>
					</table>';
		return $body;
	}
}

/**
 * Function to clean pagination offset
 *
 * @param int $segment_number
 * @return integer value
 */
if(!function_exists("safeOffset")){
	function safeOffset ($segment_number, $default = 0 ){
		$CI = &get_instance();
		return abs(intval($CI->uri->segment($segment_number, $default)));
	}	
}


/**
 * Function for checking the safe html data to transfer or post
 */
if(!function_exists('safeHTML')){
	function safeHTML($input_field){
		return htmlspecialchars(trim(strip_tags($input_field)));
	}
}
function safe_remove($input_field){
	return trim(strip_tags($input_field));
}
/**
	 * set post values
	 *
	 * @param $field (feild name)
	 * @param $default (default value)
	 * @return set vale
	 */
	if(!function_exists('set_post_value')){
		function set_post_value($field = '', $default = ''){
			if ( ! isset($_POST[$field]))
			{
				return $default;
			}
	
			return $_POST[$field];
			
		}
	} 
	
	/**
	 * set post check box values
	 *
	 * @param  $field
	 * @param  $value
	 * @param  $default
	 * @return true or false
	 */
	if(!function_exists('set_post_check_value')){
		function set_post_check_value($field = '',$value = '', $default = ''){
			if ( ! isset($_POST[$field]))
			{
				if (is_array($default) and in_array($value,$default)){
					return TRUE;
				}
				if ($default === $value){
					return TRUE;
				}
				return FALSE;
			}
			
			if (is_array($_POST[$field]) and in_array($value,$_POST[$field])){
				return TRUE;
			}
			if ($_POST[$field] === $value){
				return TRUE;
			}
			return FALSE;
	
			//return $_POST[$field];
			
		}
	}
	
	
	/**
	 * get current date time
	 *
	 * @return current date time for gmt + 5:30
	 */
	
	/*function get_cur_date_time($time=true) {
		
		$daylight_saving = timestamp_is_dst() ? 1 : 0;
		$hour = 10 + $daylight_saving; 
		if($time)	
		return date('Y-m-d H:i:s',(mktime(gmdate('H') + $hour, gmdate('i'), gmdate('s'), gmdate('m')  , gmdate('d'), gmdate('Y'))));
		else
		return date('Y-m-d',(mktime(gmdate('H') + $hour, gmdate('i'), gmdate('s'), gmdate('m')  , gmdate('d'), gmdate('Y'))));
	}*/
	
	function get_cur_date_time($time=true){
		if($time)	
		return date('Y-m-d H:i:s',(mktime(gmdate('H') + 5, gmdate('i') + 30, gmdate('s'), gmdate('m')  , gmdate('d'), gmdate('Y'))));
		else
		return date('Y-m-d',(mktime(gmdate('H') + 5, gmdate('i') + 30, gmdate('s'), gmdate('m')  , gmdate('d'), gmdate('Y'))));
	}
	
	function gettoken($time=true) {
		$daylight_saving = timestamp_is_dst() ? 1 : 0;
		$hour = 10 + $daylight_saving;  
		return strtotime(date('Y-m-d H:i:s',(mktime(gmdate('H') + $hour, gmdate('i'), gmdate('s'), gmdate('m')  , gmdate('d'), gmdate('Y')))));
	}
	
	if(!function_exists('YRMMDATE_format')){
		function YRMMDATE_format($date = ''){
			if($date == ''){
				$date = get_cur_date_time(false);
			}
			$formateddate = date('ymd',strtotime($date));
			return $formateddate;
		}
	}
	
	// get last day of week
	if(!function_exists('get_common_date_format')){
		function get_common_date_format($type = '')
		{
			if ($type == 'jquery'){
				return 'M d, yy';
			}else if ($type == 'jquery_with_time'){
				return 'mmm dd, yyyy hh:MM TT';
			}else if($type == 'with_time'){
				return 'M d, Y h:i A';
			}else{
				return 'M d, Y';
			}
			return date('Y-m-d',strtotime($date));
		}
	}
	
	// 
	if(!function_exists('convert_date_YMD')){
		function convert_date_YMDhia($date)
		{
			return date('M d, Y h:i A',strtotime($date));
		}
	}
	
	// get last day of week
	if(!function_exists('convert_date_YMD')){
		function convert_date_YMD($date)
		{
			return date('Y-m-d',strtotime($date));
		}
	}
	
	// get last day of week
	if(!function_exists('convert_date_YMDHis')){
		function convert_date_YMDHis($date_time)
		{
			if($date_time == "0000-00-00 00:00:00") 
				return "---";
			else 
				return date('Y-m-d H:i:s',strtotime($date_time));
		}
	}

if (!function_exists('json_encode'))
{
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}

if ( !function_exists('json_decode') ){
	function json_decode($json)
	{ 
	    // Author: walidator.info 2009
	    $comment = false;
	    $out = '$x=';
	   
	    for ($i=0; $i<strlen($json); $i++)
	    {
	        if (!$comment)
	        {
	            if ($json[$i] == '{')        $out .= ' array(';
	            else if ($json[$i] == '}')    $out .= ')';
	            else if ($json[$i] == ':')    $out .= '=>';
	            else                         $out .= $json[$i];           
	        }
	        else $out .= $json[$i];
	        if ($json[$i] == '"')    $comment = !$comment;
	    }
	    eval($out . ';');
	    return $x;
	} 
} 
/**
 * to access config settings easily
 * 
 */
function c($setting_name){
	$CI = &get_instance();
	return $CI->config->item($setting_name);
}
/**
 * to access config settings array easily
 * 
 */
function ca($setting_name,$key){
	$CI = &get_instance();
	$val= $CI->config->item($setting_name);
	return $val[$key];
}

/**
 * to access content from session easily
 * 
 */
function s($item_name){

	//global $CI;
	$CI = &get_instance();

	return $CI->session->userdata($item_name);
}

/**
 * to access content from flash session easily
 * 
 */
function f($item_name){
	$CI = &get_instance();
	return $CI->session->flashdata($item_name);
}

/**
 * SET SESSION - to set an item to session easily
 * 
 */
function ss($item_name, $item_value){
	$CI = &get_instance();
	return $CI->session->set_userdata($item_name, $item_value);
}

/**
 * SET FLASH DATA - to set an item to session easily
 * 
 */
function sf($item_name, $item_value){
	$CI = &get_instance();
	return $CI->session->set_flashdata($item_name, $item_value);
}
/**
 * KEEP FLASH DATA - to keep an item to session easily
 * 
 */
function kf($item_name){
	$CI = &get_instance();
	return $CI->session->keep_flashdata($item_name);
}

/**
 * KEEP ALL FLASH DATA - to keep all item to session easily
 * 
 */
function kaf(){
	$CI = &get_instance();
	return $CI->session->keep_all_flashdata();
}

function l($item_name){
	$CI = &get_instance();
	return $CI->lang->line($item_name);
}

//get_general_settings_value from Table
function get_general_settings_value($config_name){
	$CI =& get_instance();
	#$CI->db->_reset_select();
	$CI->db->where('config_name',$config_name);
	$CI->db->select('config_name,config_value');
	$general_settings	=	$CI->db->get('site_configuration');
	if ($general_settings->num_rows() > 0){
		$settings			=	$general_settings->result_array();
		if($settings[0]['config_value'] ){
			return $settings[0]['config_value'];
		}
	}
	return '';
}


/**
 * *
 *
 * @param unknown_type $contents
 * @param unknown_type $page
 * @param unknown_type $title
 */
function load_template($contents,$page,$title=''){
	$CI = &get_instance();
	$CI->load->view("header",$contents);
	$CI->load->view("table_header",array('title'=>$title));
	$CI->load->view($page);
	$CI->load->view("table_footer",$contents);
	$CI->load->view("footer");
}

/**
 * UNSET SESSION - to unset an item from session easily
 * 
 */
function us($item_name){
	$CI = &get_instance();
	return $CI->session->unset_userdata($item_name);
}
function get_config_item($conf_name){
	$CI = &get_instance();
	$CI->load->model('common_model');
	
	$conf_value = $CI->common_model->get_config_item($conf_name);
	return trim($conf_value);
}

function get_config_script($conf_name){
	$CI = &get_instance();
	$CI->load->model('common_model');
	
	$conf_value = $CI->common_model->get_config_script($conf_name);
	return $conf_value;
}

if (!function_exists('wrapString')){
	function wrapString ($string, $limit = 0, $append = ''){
		if (mb_strlen($string,'utf-8') > $limit and $limit !=0){
			$string	= mb_substr(safeHTML(fncReplaceQuotes_reverese($string)), 0, $limit,'utf-8');
			$pos = strrchr($string, " "); // $pos = 7, not 0			
			$acount	=	mb_strlen($string)-mb_strlen($pos);
			$string= mb_substr($string, 0,$acount,'utf-8');
			$string .= $append;
		}else{
			
			$string = safeHTML(fncReplaceQuotes_reverese($string));
		}
		return $string;
	}
}
function fncReplaceQuotes_reverese($string) { 
	$code_entities_match = array('&nbsp;','&quot;','&amp;','nbsp;','quot;','amp;');
	$code_entities_replace =array(' ',' ',' ');
	$text = str_replace($code_entities_match, $code_entities_replace, $string); 
	 return $text;
} 

if (!function_exists('force_ssl'))
	{
	    function force_ssl()
	    {
	    	if('development'==ENVIRONMENT){
	    		return false;
	    	}
	    	
			
	        $CI =& get_instance();
	        $CI->config->config['base_url'] =  str_replace('http://', 'https://', $CI->config->config['base_url']);
	        $CI->config->config['site_baseurl'] =  str_replace('http://', 'https://', $CI->config->config['site_baseurl']);
	        $CI->config->config['js_url_path'] =  str_replace('http://', 'https://', $CI->config->config['js_url_path']);
	        $CI->config->config['js_path_url'] =  str_replace('http://', 'https://', $CI->config->config['js_path_url']);
	        $CI->config->config['css_url_path'] =  str_replace('http://', 'https://', $CI->config->config['css_url_path']);
	        $CI->config->config['css_path_url'] =  str_replace('http://', 'https://', $CI->config->config['css_path_url']);
	        $CI->config->config['image_url'] =  str_replace('http://', 'https://', $CI->config->config['image_url']);
	        $CI->config->config['images'] =  str_replace('http://', 'https://', $CI->config->config['images']);
	        $CI->config->config['assets_url']   		= str_replace('http://', 'https://', $CI->config->config['assets_url']);
			$CI->config->config['assets_js_url']        = str_replace('http://', 'https://', $CI->config->config['assets_js_url']);
			$CI->config->config['assets_css_url']       = str_replace('http://', 'https://', $CI->config->config['assets_css_url']);
			$CI->config->config['assets_img_url']       = str_replace('http://', 'https://', $CI->config->config['assets_img_url']);
			$CI->config->config['assets_plugins_url']   = str_replace('http://', 'https://', $CI->config->config['assets_plugins_url']);
	        if ($_SERVER['SERVER_PORT'] != 443)
	        {
	            redirect($CI->uri->uri_string());
	        }
	    }
	}
	
	function remove_ssl()
	{
		if('development'==c(ENVIRONMENT)){
	    		return false;
	    }
		
	   	$CI =& get_instance();
	    $CI->config->config['base_url'] = str_replace('https://', 'http://', $CI->config->config['base_url']);
	    $CI->config->config['site_baseurl'] = str_replace('https://', 'http://', $CI->config->config['site_baseurl']);
	    $CI->config->config['js_url_path'] = str_replace('https://', 'http://', $CI->config->config['js_url_path']);
	    $CI->config->config['js_path_url'] = str_replace('https://', 'http://', $CI->config->config['js_path_url']);
	    $CI->config->config['css_url_path'] = str_replace('https://', 'http://', $CI->config->config['css_url_path']);
	    $CI->config->config['css_path_url'] = str_replace('https://', 'http://', $CI->config->config['css_path_url']);
	    $CI->config->config['image_url'] = str_replace('https://', 'http://', $CI->config->config['image_url']);
	    $CI->config->config['images'] = str_replace('https://', 'http://', $CI->config->config['images']);
		$CI->config->config['assets_url']   		= str_replace('https://', 'http://', $CI->config->config['assets_url']);
		$CI->config->config['assets_js_url']        = str_replace('https://', 'http://', $CI->config->config['assets_js_url']);
		$CI->config->config['assets_css_url']       = str_replace('https://', 'http://', $CI->config->config['assets_css_url']);
		$CI->config->config['assets_img_url']       = str_replace('https://', 'http://', $CI->config->config['assets_img_url']);
		$CI->config->config['assets_plugins_url']   = str_replace('https://', 'http://', $CI->config->config['assets_plugins_url']);
	    if ($_SERVER['SERVER_PORT'] != 80)
	    { 
	    	kf('success_message');
	    	kf('error_message');
	        redirect($CI->uri->uri_string());
	    }
	}
if ( ! function_exists('random_string'))
{
	function random_string($type = 'alnum', $len = 8)
	{
		switch($type)
		{
			case 'basic'	: return mt_rand();
				break;
			case 'alnum'	:
			case 'numeric'	:
			case 'nozero'	:
			case 'alpha'	:

					switch ($type)
					{
						case 'alpha'	:	$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
							break;
						case 'alnum'	:	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
							break;
						case 'numeric'	:	$pool = '0123456789';
							break;
						case 'nozero'	:	$pool = '123456789';
							break;
					}

					$str = '';
					for ($i=0; $i < $len; $i++)
					{
						$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
					}
					return $str;
				break;
			case 'unique'	:
			case 'md5'		:

						return md5(uniqid(mt_rand()));
				break;
			case 'encrypt'	:
			case 'sha1'	:

						$CI =& get_instance();
						$CI->load->helper('security');

						return do_hash(uniqid(mt_rand(), TRUE), 'sha1');
				break;
		}
	}
}

if(!function_exists('time_to_string_format')){
	function time_to_string_format ($date,$istime=0){
		
		$str_time	=	strtotime($date);
		//$time		=	($istime)?date("g:i a.",$str_time):'';
		$time		=	date("g:i a",$str_time);
		return date("F",$str_time).' '.date("d",$str_time).', '.date("Y",$str_time).' '.$time;
	}
}
if(!function_exists('unique_id')){
	function unique_id(){
		return sprintf(
				 '%08x-%04x-%04x-%02x%02x-%012x', mt_rand(), mt_rand(0, 65535),	 bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '0100', 11, 4)), bindec(substr_replace(sprintf('%08b', mt_rand(0, 255)), '01', 5, 2)), mt_rand(0, 255), mt_rand() );
	}
} 

// PRE
function pre($array=array()){
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}



function process_expressions  ($content,$expressions)
{
	$values_array  = array (); 			  
	$matches            = array();
	preg_match_all("/\&lt;\%([a-z_A-Z0-9]*)\%\&gt;/",$content, $matches);
	$variables_array    = $matches[1];			 
	if (count($variables_array) > 0) 
	foreach (@$variables_array as $key)
	{
		@$values_array[] = @$expressions[$key];
	}

	$new_variables_array    = array();
	foreach($variables_array as $variable)
	{
		$new_variables_array[] = '/\&lt;\%'.$variable.'\%\&gt;/';
	}
	$body_content ='';
	$body_content .= preg_replace ($new_variables_array, $values_array, $content);  
	return($body_content);
}
	 

// check currentplan
function chkCurrentPlanIsValid(){
	$CI =& get_instance();
	$CI->load->model('apps_model');
	if( s('USERID') == ''){
  	 	redirect("home");
	}
	
	$current_plan = $CI->apps_model->getAppsCurrentPlan( s('USERID')  );	 
 	
 	$valid_to		= 0;
	if( !empty($current_plan) ){
		$valid_to 		= strtotime(date("Y-m-d H:i:s", strtotime($current_plan->valid_to)) ); 
	} 
	$date_now 		= strtotime( get_cur_date_time() );
 	if( $date_now 	>= $valid_to){
 		sf('error_message',  l('expired_page_msg') );
 	 	redirect("accounts/myaccount");
	}
}

// remove http 
function rhttp($str=''){
	if( trim($str) ){
		$str = str_replace('http://', '//', $str);
	}
	
	return $str;
}

/*End of file common_helper.php*/
/* Location: ./system/application/helpers/common_helper.php */