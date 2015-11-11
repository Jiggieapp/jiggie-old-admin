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

class Admin_model extends CI_Model {
	public function __construct()
		{
			parent::__construct();
		}
	 
	function forgot_password($email)
	{
		$this->db->where('email', $email);
		$this->db->where('status','A');
		$this->db->select('admin_id');
		$this->db->from('admin_user_details');
		$result_set   				= $this->db->get ();
		if (0 < $result_set->num_rows()){
			$row 					= $result_set->row();   
			$newPassword			= random_string('alnum', 10);
			$ret_result['newpass']	= $newPassword;
			/*$newPassword			=	get_encr_password($newPassword);
			$data					= array("password"=>$newPassword);*/
			$newPassword =$this->db->escape_like_str($newPassword);
			$this->db->set('password', "LEFT(MD5(CONCAT(MD5('$newPassword'), 'cloud')), 50)", false);
			$this->db->where('admin_id', $row->admin_id);
			$this->db->update('admin_user_details'); 

			$this->db->where('admin_id',$row->admin_id);
			$this->db->select('*');
			$this->db->from('admin_user_details');
			$result_set   			= $this->db->get();
			$row          			= $result_set->row();
			$ret_result['first_name']	=	$row->first_name;
			$ret_result['username']	=	$row->username;
			$ret_result['id']		=	$row->admin_id;
			$ret_result['email']	=	$row->email;
			return $ret_result;
		}else{
			return false;
		}
	}
	
	/**
	 * function to check admin email id exist
	 */
	function check_admin_email_exist($email,$admin=''){
		if(isset($email) && !empty($email)){
			if(isset($admin) && !empty($admin))
				$this->db->where('admin_id !=',$admin);
			$this->db->where('email',$email);
			$this->db->select('admin_id');
			$query	=	$this->db->get('admin_user_details');			
			if($query->row()){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	/**
	 * edit admin
	 *
	 * @param admin details $arr
	 * @param admin id $id
	 * @return unknown
	 */
	function edit_admin($arr,$id){
		if(isset ($arr) && '' != $arr &&  isset ($id) && '' != $id){			
			$this->db->where('admin_id', $id);
			if($this->db->update('admin_user_details', $arr)) 
			return true;
			else 
			return false;
				
		}else{ 
			return FALSE;
		}
	}
	
	function isAdmin($id){
		if(isset($id) && !empty($id)){			
			$this->db->where('admin_id',$id);
			$this->db->select('*');
			$query	=	$this->db->get('admin_user_details');			
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
	 * function for change admin password
	 *
	 */
	function change_admin_password($new_password,$id)
	{
		$new_password =$this->db->escape_like_str($new_password);
        $this->db->set('password', "LEFT(MD5(CONCAT(MD5('$new_password'), 'cloud')), 50)", false);
		$this->db->where('admin_id', $id);
		if($this->db->update('admin_user_details')) 
		return true;
		else 
		return false;
	}
	/**
	 * get admin details
	 *
	 * @param admin id $id
	 * @return unknown
	 */
	function getAdminDet($id){
		if(isset($id) && !empty($id)){			
			$this->db->where('admin_id',$id);
			$this->db->select('*');
			$query	=	$this->db->get('admin_user_details');			
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
	 * function getting the inbox count
	 *
	 * @return array
	 */
	function getCountInbox(){
		$this->db->select('*');
		$this->db->where('parent_id',0);
		$this->db->from('inbox');
		$result_set   = $this->db->get();
		return $result_set->num_rows();
	}
	
	function getCountNotification(){
		$this->db->select('*');
		$this->db->where('type',1);
		$this->db->where('status',1);
		$this->db->from('notifications');
		$result_set   = $this->db->get();
		return $result_set->num_rows();
	}
	
	/**
	 * function for getting inbox with pagination
	 *
	 * @param int $num
	 * @param int $offset
	 * @return array
	 */
	function getInbox($num, $offset = 0){
		
		$this->db->select('ib.*,ud.first_name,ud.last_name,
		(SELECT COUNT(inb.id) FROM cl_inbox inb where (inb.parent_id = ib.id or inb.id = ib.id ) and inb.read = "0" and from_user_id != "0") as unread,
		(SELECT COUNT(t.id) FROM cl_inbox t where (t.parent_id = ib.id  or t.id = ib.id  )  ) as totmsg');
		
		$this->db->from('inbox ib');
		$this->db->join('user_details ud','ud.id=ib.from_user_id  ' , 'left', false);  
		$this->db->where('ib.parent_id',0);
		$this->db->order_by('ib.id','DESC');
		$this->db->limit($num,$offset);
		$result   = $this->db->get();
		return $result->result_array();
	}
	function getNotifications($num, $offset = 0){
		
		$this->db->select('ns.*,aud.first_name,aud.last_name');
		$this->db->from('notifications ns');
		$this->db->join('admin_user_details aud','aud.admin_id =ns.to_user_id' , 'left', false);  
		$this->db->where('ns.type',1);
		$this->db->where('ns.status',1);
		$this->db->order_by('ns.id','DESC');
		$this->db->limit($num,$offset);
		$result   = $this->db->get();
		return $result->result_array();
	}
	
	/**
	 * funcion to fetch inbox
	 *
	 * @param String $id
	 */
	function getInboxDetails($id){
		
		$this->db->select('*');
		$this->db->from('inbox');
		$this->db->where('id',$id);
		
		$result   = $this->db->get();
		if($result->num_rows()>0){
			return $result->row();
		}else{
			return  array();
		}
	}
	
	function getInboxChildDetails($id){
		
		$this->db->select('*');
		$this->db->from('inbox');
		$this->db->where('parent_id',$id);
		$result   = $this->db->get();
		return $result->result_array();
	}
	
	function setReply($id){
		
        $this->db->set('read', "1", false);
		$this->db->where('id', $id);
		$this->db->where('from_user_id !=', "0");
		$this->db->or_where('parent_id', $id);
		if($this->db->update('inbox')) 
		return true;
		else 
		return false;
	}

	function saveToInbox($arr){
    	if(isset ($arr) && '' != $arr){
			$this->db->insert('inbox', $arr);
			$id = $this->db->insert_id();
			if ($id){
				return $id;
			}else{ 
				return FALSE;
			}
		}else{ 
			return FALSE;
		}
    }
    
    
	function deleteInboxDetails ($id=0){
		
		if($id){
			$this->db->where('id',$id);
			$this->db->or_where('parent_id',$id);
			$this->db->delete('inbox');
			return true;
		}else{
			return false;
		}
	}
	
	function deleteNotifications ($id=0){
		
		if($id){
			$this->db->where('id',$id);
			$this->db->delete('notifications');
			return true;
		}else{
			return false;
		}
	}
	
}
?>