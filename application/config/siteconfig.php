<?php 

$proto = "http" . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s" : "") . "://";

// For the script that is running:
$script_directory 								= substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/'));

// If your script is included from another script:
$config['site_baseurl'] 						= $proto.$_SERVER['SERVER_NAME'];
$config['site_baseurl'] 						.= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

$config['site_ssl_baseurl'] 					= "https://".$_SERVER['SERVER_NAME'];
$config['site_ssl_baseurl'] 					.= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);


//if(!defined('DOCUMENT_ROOT')) define('DOCUMENT_ROOT',str_replace('application\config','',substr(__FILE__, 0, strrpos(__FILE__, '\\'))));
if(!defined('DOCUMENT_ROOT')) define('DOCUMENT_ROOT',str_replace('application/config','',substr(__FILE__, 0, strrpos(__FILE__, '/'))));
//if(!defined('DOCUMENT_ROOT')) define('DOCUMENT_ROOT',str_replace('application\config','',substr(__FILE__, 0, strrpos(__FILE__, '\\'))));


$config['site_basepath']						= constant("DOCUMENT_ROOT").'';


$config['assets_url']        					= $config['site_baseurl'].'assets/';
$config['assets_path']        					= $config['site_basepath'].'assets/';

$config['assets_js_url']        				= $config['assets_url'].'js/';
$config['assets_css_url']        				= $config['assets_url'].'css/';
$config['assets_img_url']        				= $config['assets_url'].'images/';
$config['assets_plugins_url']        		 	= $config['assets_url'].'plugins/';


$config['image_url']    			   			= $config['assets_url'].'images/';


$config['js_path_parsed']        				= $config['site_basepath'].'js/parsed/';
$config['js_url_path']        					= $config['site_baseurl'].'js/parsed/';



$config['upload_file_path']	   					= $config['site_basepath']."uploads/";
$config['upload_file_url']	   					= $config['site_baseurl']."uploads/";
$config['upload_file_ssl_url']	   				= $config['site_ssl_baseurl']."uploads/";


$config['protocol']             				= 'smtp';
$config['smtp_host']           					= 'mail.societyfirst.com';
$config['smtp_from_name']      	 				= 'Partyhost Admin';
$config['smtp_from']            				= 'smtpuser@societyfirst.com';
$config['smtp_user']            				= 'smtpuser@societyfirst.com';
$config['smtp_password']       					= 'tm55ZefHsk';
$config['mailtype']								= 'html';
$config['admin_email']            				= 'perumal.m@rainconcert.in';
$config['smtp_timeout']							= 30;
$config['smtp_port']         					= 25;


$config['email_logo']       					= "";
$config['mailtype']								= 'html';
$config['smtp_timeout']							= 30;


$config['unblock_url']							= "http://api-dev.partyhostapp.com/unblock_user";
$config['site_title']        					= 'Party Host- Administrator pages';
$config['start_date']        					= '2014-04-01';
$config['metrics']								=  array(
													'returning_users'=>'Returning users',
													'new_users'=>'New Users',													
													'active_hostings'=>'Total New Hostings',
													'nonrec_hostings'=>'New Non-Recurring Hostings',
													'rec_hostings'=>'New Recurring Hostings',
													'total_chat'=>'Total active Chats',
													'new_chat'=>'New Chats',													
													'updated_chat'=>'Updated Chats',
													'unique_chatter' =>'Total Unique Chatter',
													'guest_chat'=>'Unique Guest Chatter',
													'host_chat'=>'Unique Host Chatter',
												);
 