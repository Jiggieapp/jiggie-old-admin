<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Handles admin functions.
 *
 * @package		CodeIgniter
 * @subpackage	Models
 * @category	Models
 * @author
 */

// ------------------------------------------------------------------------

class Common_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	 
	function safe_html($input_field)
	{ 
		return htmlspecialchars(trim(strip_tags($input_field)));
	}
	
	function safe_sql($input_field)
	{ 
		return mysql_real_escape_string(trim(strip_tags($input_field)));
	}
	
	
	/**
	 * to send emails to a given email address
	 *
	 * @param Sring $to_email
	 * @param Sring $from
	 * @param Sring $subject
	 * @param Sring $body_content
	 * @param Array $attachment
	 * @param Sring $from_mail
	 * @return Boolean
	 */
	
	
	
	function send_mail($to_email='', $from='', $subject, $body_content, $attachment = array(), $from_mail='', $cc=array(), $bcc=array(), $batch_mode=false, $batch_size=200)
	{
		$this->load->library ('email');
		$this->email->_smtp_auth	= TRUE; 	    
		$this->email->protocol		= "smtp";
		$this->email->smtp_host		= $this->config->item('smtp_host');
		$this->email->smtp_user		= $this->config->item('smtp_from');
		$this->email->smtp_pass		= $this->config->item('smtp_password');
		$this->email->mailtype		= $this->config->item('mailtype');
		
		$this->email->smtp_timeout	= $this->config->item('smtp_timeout');
		$this->email->smtp_port		= $this->config->item('smtp_port');
		$this->email->smtp_crypto	= $this->config->item('smtp_crypto');
		$this->email->charset		= $this->config->item('charset');
 		
		$from_name					= ($from=='')?$this->config->item('smtp_from_name'):$from;
		$reply_mail					= ($from_mail=='')?$this->config->item('smtp_from'):$from_mail;
		$this->email->from($reply_mail, $from_name);
		$this->email->to($to_email);
		if(!empty($cc)){
			$this->email->cc($cc);
		}
		if(!empty($bcc)){
			$this->email->bcc($bcc);
		}
		$this->email->reply_to($reply_mail,$from_name);        
		//$this->email->set_mailtype('html');
		$this->email->subject($subject);
		$this->email->message($body_content);
		if($attachment !=''){
			foreach($attachment as $attach ){
				$this->email->attach($attach);
			}
		}
                
		if ($this->email->send ()){
			
 echo $this->email->print_debugger();return TRUE;
		}
		else{
			
 echo $this->email->print_debugger();return FALSE;
		}
	}
			
	/**
	 * get the config value
	 *
	 * @return unknown
	 */
	function get_config_item($conf_name){				
		$query = $this->db->query("SELECT config_value FROM ".$this->db->dbprefix."site_configuration WHERE config_name='".$conf_name."'");
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			if($row->config_value){
				return $row->config_value;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	/**
	 * get the config value
	 *
	 * @return unknown
	 */
	function get_config_script($conf_name){				
		$query = $this->db->query("SELECT config_text FROM ".$this->db->dbprefix."site_configuration WHERE config_name='".$conf_name."'");
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			if($row->config_text){
				return $row->config_text;			
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	/**
	 * to get the url of the post page
	 *
	 * @return String
	 */
	function get_post_url()
	{
		$postURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$postURL .= "s";}
		$postURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$postURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$postURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $postURL;
	}
	
	function prepare_select_box_data( $table, $fields, $where = array(), $insert_null = false,$order_by = '',$other_array = array()){
		
		list($key, $val) 	= explode(',',$fields);
		$key 				= trim($key);
		$val 				= trim($val);
		$order_by			= $order_by ? $order_by : $val;
		$input_array 		= $this->get_data( $table, $fields, $where, $order_by );

		$select_box_array 	= array();
		$total_records 		= count($input_array);
		if($insert_null) {
			$select_box_array[''] = $insert_null===true ? '' : $insert_null;
		}
		for($i = 0; $i < $total_records; $i++){
		 	$select_box_array[$input_array[$i][$key]] = $input_array[$i][$val];
		}
		if (is_array($other_array) and count($other_array) > 0){
			foreach ($other_array as $key => $val){
				$select_box_array[$key]				=	$val;
			}
		}
		return $select_box_array;
	}
	
	function get_data( $table, $fields = '*', $where = array(),$order_by = '' ){
		if((is_array($where) && count($where)>0) or (!is_array($where) && trim($where) != '')) $this->db->where($where);
		if($order_by) $this->db->order_by($order_by);
		$this->db->select($fields);
		$query = $this->db->get($table);
		return $query->result_array();
	}
	
	function check_selectbox_values($aWhere=array(), $table_name=""){
		if($aWhere){
			$this->db->where($aWhere, "", false); 
		}
		$this->db->select('id');
		$this->db->from( $table_name ); 
		$result   = $this->db->get();
		$result = $result->result_array();
		$array_list = array();
		foreach ($result AS $res):
			$array_list[] = $res['id'];
		endforeach;
		return $array_list;
	}
	 
	function get_custom_data( $table, $fields = '*', $where = array(),$order_by = '' ){
		if((is_array($where) && count($where)>0) or (!is_array($where) && trim($where) != '')) 
		
		$this->db->where($where, "", false); 
		if($order_by) $this->db->order_by($order_by);
		$this->db->select($fields);
		$query = $this->db->get($table);
		return $query->result_array();
	}
	
	/**
	 * function to get the page titles and meta details
	 *
	 * @return unknown
	 */
	function get_page_meta($meta_id){
		$this->db->select('*');			
		$this->db->where('meta_id', $meta_id);			
		$query = $this->db->get("page_meta_details"); 			
		$result = array();
		if($query->row()) {	
			return $query->row();
		} 
		else return false;
	}
	 
	/**
	 * function for file_upload musik_track musik_mix_track
	 */  
	function file_upload($field_name = '',$upload_path = '',$allowed_type = '',$max_size = 1024){  
		if(@$_FILES[$field_name]['name']){
			$file  							=	explode('.', $_FILES[$field_name]['name']); 
			$name  							=	$file[0];
			$upload_path					=	upload_path().$upload_path; 
			
			if (!@$allowed_type){
				$config['allowed_types'] 	=   'gif|jpg|png|jpeg';
			}else{
				$config['allowed_types'] 	=   $allowed_type;
			}
			$config['upload_path'] 			=	$upload_path;
			$config['max_size'] 			=   $max_size;
			$config['remove_spaces'] 		=   TRUE; 
			$file_name						=	str_replace('(','_',$_FILES[$field_name]['name']);
			$file_name						=	str_replace(')','_',$file_name);
			$file_name						=	str_replace('-','_',$file_name);
			$config['file_name']	    	=   time().$file_name;
			
			$this->load->library('upload');
			$this->upload->initialize($config);
			
			//$uplod_result					=	 ;
			$bStatus = false;
			$data = array();
			
			if( $this->upload->do_upload($field_name) ){
				
				$bStatus 	= true;
				
				//echoo('test 1');
				//_print_r($this->upload->data());
				$data 		= array('upload_data' => $this->upload->data());
				//return $data["upload_data"]["file_name"];
			}else{
				//echoo('test 1');
				$bStatus 	= false;
				$data['error_msg'] = $this->upload->display_errors();
			}
			
			return array($bStatus, $data);
			//return false; 
		} 
	}
 
	/**
	 * function to send email
	 *
	 * @return unknown
	 */
	function process_send_mail  ($email, $email_array, $title = '' ,$from_name ='',$from='',$attachment=array(), $mailsubject='')
	{
		$values_array		= array ();			
		$result_array       = $this->common_model->get_mail_content_and_title ($title);
		foreach ($result_array as $key=>$value)
		{
			$mail_subject   = ($mailsubject) ? $mailsubject : $key;
			$email_body     = $value;
		}
		$matches            = array();
		preg_match_all("/\{\%([a-z_A-Z0-9]*)\%\}/",$email_body, $matches);
		$variables_array    = $matches[1];
	
		if (count($variables_array) > 0) 
		foreach (@$variables_array as $key)
		{
			@$values_array[] = @$email_array[$key];
		}
	
		$new_variables_array    = array();
		foreach($variables_array as $variable)
		{
			$new_variables_array[] = '/\{\%'.$variable.'\%\}/';
		}
		$body_content ='';
		$body_content .= preg_replace ($new_variables_array, $values_array, $email_body);		
		
		if ($this->common_model->send_mail($email,$from_name, $mail_subject, $body_content,array(),$from))
			return TRUE;
		else
			return FALSE;
		
	}
	
	// function to get email title and content 
	function get_mail_content_and_title ($message_title = '')
	{
        $this->db->select('subject AS TITLE, content AS BODY_CONTENT');
        $this->db->from('email_templates');
        $this->db->where('title', $message_title);		
        $select_query   = $this->db->get ();
		if (0 < $select_query->num_rows ())
		{
            foreach ($select_query->result() as $row)
            {
                $result_array[$row->TITLE] = $row->BODY_CONTENT;
            }
            return $result_array;
		}
		else
		{
		    return FALSE;
		}
	}
	
	/**
	 * check any user exist
	 */
	function isUser($email)
	{
		$this->db->where('email', $email);
		$this->db->where('status','1');
		$this->db->select('*');
		$this->db->from('user_details');
		$result		= $this->db->get ();
		if ($result->row()){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * check any user exist
	 */
	function getUserbyEmail($email)
	{
		$this->db->where('email', $email);
		$this->db->select('*');
		$this->db->from('user_details');
		$result		= $this->db->get ();
		if ($result->row()){
			return $result->row();   
		}else{
			return false;
		}
	}
	
	/**
	 * get user details
	 *
	 * @return unknown
	 */
	function getCAProfileDetails($id){	
		if(isset ($id) && '' != $id){			
			$this->db->where('user_id',$id);
			$this->db->where('status','1');
			$this->db->select('*');
			$query	=	$this->db->get('user_profile');			
			if($query->row()){
				return $query->row();
			}else{
				return FALSE;
			}
		}else{ 
			return FALSE;
		}		
	}
	
	/**
	 * forgot password
	 */
	function forgot_password($email)
	{
		
		$this->db->where('email', $email);
		$this->db->where('status','1');
		$this->db->select('*');
		$this->db->from('user_details');
		$result_set   				= $this->db->get ();
		if (0 < $result_set->num_rows()){
			$row 					= $result_set->row();   
			
			$forgot_pwd				= random_string('alnum', 10);
			$ret_result['forgot_pwd']	= $forgot_pwd;
			$this->db->set('forgot_pwd', $forgot_pwd);
			$this->db->where('id', $row->id);
			$this->db->update('user_details'); 			
			$ret_result['first_name']	=	$row->first_name;
			$ret_result['last_name']	=	$row->last_name;
			$ret_result['id']		=	$row->id;
			$ret_result['email']	=	$row->email;
			return $ret_result;
		}else{
			return false;
		}
	}
	
	/**
	 * check any user exist
	 */
	function isPenindingUser($email)
	{
		$this->db->where('email', $email);
		$this->db->where('status','2');
		$this->db->select('*');
		$this->db->from('user_details');
		$result		= $this->db->get ();
		if ($result->row()){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * resend_link
	 */
	function resend_link($email)
	{
		$this->db->where('email', $email);
		$this->db->where('status','2');
		$this->db->select('*');
		$this->db->from('user_details');
		$result		= $this->db->get ();
		if ($result->row()){
			return $result->row();   
		}else{
			return false;
		}
		
	}
	
	// Common save
	function save($table, $data){
		$this->db->insert($table,$data);
		return $this->db->insert_id();
	}
	
	// Update
	function update($table, $data, $where){
		 if(!empty($data)){ 
			$this->db->where($where, "", false); 
			$this->db->set ($data);
			if($this->db->update ($table)){
				return TRUE;
			}else{
				return FALSE;
			}
        }
	}
	 
	// Common Delete function
	function delete($table, $where=array()) {
		if(!empty($table)){  
			if( $this->db->delete($table, $where) ){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	//list_notifications
	function list_notifications($user_id=0,$st=1){
		$this->db->select('*');
        $this->db->from('notifications');
        $this->db->where('to_user_id', $user_id);		
        $this->db->where('status', $st);	
        $this->db->where('type', 2);		
        $this->db->order_by('id DESC');
        $select_query   = $this->db->get ();
		if (0 < $select_query->num_rows ())
		{
            return $select_query->result_array();
		} else {
		    return FALSE;
		} 
	}
	
	// Delete notifications
	function deletenotifications($id=0,$to_user_id=0){
		if($id){ 
			$this->db->where('id',$id);
			$this->db->where('to_user_id',$to_user_id);
			$this->db->delete('notifications');   
			return true;
		}else{
			return false;
		}
	}
	 
	/**
	 * save_notification
	 *
	 * @param unknown_type $notification = msg
	 * @param unknown_type $to_user_id = to which user that notification send, for not logged in user set $to_user_id
	 * @return unknown
	 */
	function save_notification($notification='',$to_user_id=''){ 
		if( trim($to_user_id) == '') { 
			$to_user_id = s('USERID');
		}
		if( $this->save('notifications',array('to_user_id'=>$to_user_id,'notifications'=>$notification,'created_on'=>get_cur_date_time())) ){
			return true;
		}else{
			return false;
		}
	}
	
	function save_admin_notification($notification=''){ 
		
		if( $this->save('notifications',array('to_user_id'=>1,'type'=>1,'notifications'=>$notification,'created_on'=>get_cur_date_time())) ){
			return true;
		}else{
			return false;
		}
	}
	
	// get_my_credits
	function get_my_credits($user_id){ 
		
		if($user_id){
			$this->db->where('user_id', $user_id, false);
		}
		
		$this->db->where("status", "1", false);
		$this->db->select('SUM(credits) AS credits' , false);
		$this->db->from('customer_interview_credits');
		$result   = $this->db->get();
		$credits  = $result->result_array(); 
		return $credits[0]['credits']; 
	}
	
	// get_my_free_credits
	function get_my_free_credits($user_id, $added_by=0){ // Type=1 Free, 2 = Paid
		
		if($user_id){
			$this->db->where('user_id', $user_id, false);
		}
		
		if( $added_by > 0){ 
			$this->db->where('added_by', $added_by, false);
		}
		 
		$this->db->where('type', 1, false); 
		$this->db->where("status", "1", false);
		$this->db->select('SUM(credits) AS credits' , false);
		$this->db->from('customer_interview_credits');
		$result   = $this->db->get();
		$credits  = $result->result_array(); 
		return $credits[0]['credits']; 
	}
	
	// get_my_balance_credits
	function get_my_balance_credits($user_id){
		
		if($user_id){
			$this->db->where('user_id', $user_id, false);
		}
		$this->db->where("status", "1", false);
		$this->db->select('SUM(credits) AS credits' , false);
		$this->db->from('customer_interview_credits_used');
		$result   = $this->db->get();
		$credits  = $result->result_array();
		return $credits[0]['credits'];
	}
	
	//Get prevoius Transaction ID from customer_payments
	function checkPreviousTransactionDetails(){
		$this->db->select('id'); 
		$this->db->where('user_id', s('USERID'));
		$this->db->where('transaction_type', 1); // transaction_type is direct do payment with billing information
		$this->db->where('payment_status', 1);
		$query = $this->db->get("customer_payments");
		if ($query->num_rows() > 0) {
			
			$this->db->select('*');
			$this->db->where('user_id', s('USERID'));
			$this->db->where('transaction_type', 1); // transaction_type is direct do payment with billing information
			$this->db->where('payment_status', 1);
			$this->db->order_by('payment_date', 'DESC');
			$this->db->limit(1);
			$result = $this->db->get("customer_payments");
			if ($query->num_rows() > 0) {
				return $result->result_array();
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		} 
	}
	 
	// generating captcha
	function generate_captcha ($configVal = array (),$from = "")
    {
    	$this->load->helper ('_captcha');
        if (!empty ($configVal))
        {
    		$this->vals = $configVal;
    	}
        else
        {
        	$this->vals = array (
                                    'word'               => '',
                                    'word_length'  	     => 6,
                                    'img_path'       	 => $this->config->item ('captcha_image_path'),
                                   	'img_url'       	 => $this->config->item ('captcha_image_url'),
                                    'font_path'     	 => $this->config->item ('captcha_font_path'),
                                    'img_width'     	 => 150,
                                    'img_height'    	 => 32,
                                    'expiration'     	 => 3600
                                );
        }          
        $captcha = create_captcha ($this->vals);               
        $this->session->set_userdata ("captcha_word", $captcha['word']);
        return $captcha;
    }	
    
    public function getDataExistsArray($column_name = array(), $table_name, $where = array(), $or_where = array(), $order_by = array(),$group_by = array(), $join = "", $join_type="", $join_field="", $join_field1="", $where_type="", $join2 = array(), $limit="") { 
        
        try {
            $this->db->select($column_name);
            $this->db->from($table_name);
            if(!empty($join)) {
                $this->db->join($join,"$join.$join_field = $table_name.$join_field1", $join_type);
            }
            if(!empty($join2)) {
                 $this->db->join($join2["table"],$join2["table"].".".$join2["field1"]." = ".$join2["table2"].".".$join2["field2"], $join2["join_type"]);
            }
            if(!empty($where)) {
                 if($where_type == 1) {
                     $this->db->where($where, '', FALSE);
                 } else {
                     $this->db->where($where);
                 }
            }     
            if(!empty($or_where))
                 $this->db->or_where($or_where);
            if(!empty($order_by))
                $this->db->order_by($order_by);
            if(!empty($limit))
                $this->db->limit($limit);
            $query = $this->db->get();#echo $this->db->last_query();
            if($query->num_rows() > 0) {
                $data = $query->result_array();
                return $data;
            } else { 
                return FALSE;
            }
        } catch (Exception $e) { 
            throw new ErrorException('An unexpexted error occurs!');
        }
        
    }
    
    public function getDataExists($column_name = array(), $table_name, $where = array(), $or_where = array(), $order_by = array(),$group_by = array(), $join = "", $join_type="", $join_field="", $where_type="", $join2 = array()) { 
        try {
            $this->db->select($column_name);
            $this->db->from($table_name);
            if(!empty($join)) {
                $this->db->join($join,"$join.$join_field = $table_name.$join_field", $join_type);
            }
            if(!empty($join2)) {
                 $this->db->join($join2["table"],$join2["table"].".".$join2["field1"]." = ".$join2["table2"].".".$join2["field2"], $join2["join_type"]);
            }
            if(!empty($where))
                 $this->db->where($where);
            if(!empty($or_where))
                 $this->db->or_where($or_where);
            if(!empty($order_by))
                $this->db->order_by($order_by); 
            $query = $this->db->get();//echo $this->db->last_query();
            if($query->num_rows() > 0) {
                $data = $query->row();
                return $data;
            } else { 
                return FALSE;
            }
        } catch (Exception $e) { 
            throw new ErrorException('An unexpexted error occurs!');
        }
        
    }
    
}
 
?>