<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * Project		www.MyBookpages.com
 * @package		CodeIgniter
 * @author		Soumya Raj M
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

	class Authentication
	{
		var $CI = null;
		var $fb_id = null;
		
		function Authentication ()
		{
			$this->CI =& get_instance ();
			
                        $this->CI->load->helper(array("security"));
		}
			
		
		/**
		 * function for admin login process
		 * 
		 * @package		CodeIgniter
		 * @author		
		
		 * @return unknown
		 */
		function process_admin_login ($login = NULL, $request = '')
		{
			
    			if (!is_array ($login) || 0 >= count ($login))
    			{
    				return FALSE;
    			}
    			$emailid        = $login['email'];
    			$password        = $login['password'];
    			
				$this->CI->db->select ('username   AS USERNAME, crypted_password, CONCAT_WS(" ", first_name, last_name) as NAME, id as USERID, email as EMAIL,user_type_id,user_status', false);
				$this->CI->db->where ('email', $emailid);					
				$password =$this->CI->db->escape_like_str($password);
				$this->CI->db->where ("crypted_password = LEFT(MD5(CONCAT(MD5('$password'), 'cloud')), 50)", NULL, false );				
				$select_query    = $this->CI->db->get ('admin_users');
				
				if (0 < $select_query->num_rows ())
				{
                    $row 			= $select_query->row();  
                    if($row->user_status==1){
                    	$session_data 	= array (
                        'ADMIN_USERNAME'      => $row->USERNAME,
                        'ADMIN_NAME'          => $row->NAME,
                        'ADMIN_USERID'        => $row->USERID,
                        'ADMIN_EMAIL'         => $row->EMAIL,
                        'USER_TYPE_ID'        => $row->user_type_id,  
                        'startDate'           => $this->CI->config->item("start_date"), 
            			'endDate'             => date("Y-m-d"), 
            			'selected'			  => 'All',
            			'current_cont'		  => 'Home',
            			'metric1'	          =>  'new_users',
            			'date_picker_sec'	  => 'past',                               	
                    );

                    $this->CI->session->set_userdata ($session_data);	
					$myarray= array('last_login'=> date("Y-m-d H:i:s"));
				    $this->CI->db->update("admin_users", $myarray, array("id" => $row->USERID))	;							
                    return 'success';
                    }		
					else if($row->user_status==10){
						return 'deleted';
					}else{
						return false;	
					}
				}
				else 
                     return false;				
				
		}
			
		/**
		 * function for logout
		 * 
		 * @package		CodeIgniter
		 * @author		
		 * @return unknown
		 */
		function logout ()
		{
			/*$this->CI->load->helper('common');
			$ips = get_ip_to_location();*/
			$this->CI->db->where('id', $this->CI->session->userdata ('USERID'));
			$arr['logged_out']		= get_cur_date_time();
			$arr['login_status ']	= 0;
			$this->CI->db->update('user_details', $arr);	
			$session_data 	= array (                                       
										'USERID'        => 	'',
	                                   	'FBID'    		=> 	'',
	                                   	'USEREMAIL'    	=> 	'',
	                                   	'FIRSTNAME' 	=> 	'',
	                                   	'MIDDLENAME' 	=> 	'',
	                                   	'LASTNAME' 		=> 	'',
	                                   	'STATUS' 		=> 	''
                          	        );
		    $this->CI->session->unset_userdata($session_data);						
			$this->CI->session->sess_destroy();
			return TRUE;
		}
		
		/**
		 * To checked whether the user is logged in or not
		 * 
		 * @package		CodeIgniter
		 * @author		
		 * @return unknown
		 */
		/*function logged_in ()
		{
			
			if (!$this->CI->session->userdata ('USERID')) 
				return FALSE;
			else return TRUE;
					
		}*/
		function admin_logged_in(){
	
			if ('' == $this->CI->session->userdata ('ADMIN_USERID')){
				return FALSE;
			}
			return TRUE;
		}

		/*function check_user_logged_in(){
			($this->admin_logged_in()) ? redirect('dashboard') :'' ;
		}*/
		
		function admin_logout(){
			$session_data 	= array (                                       
									
                                    'ADMIN_USERNAME'      => '',
                                  	'ADMIN_NAME'      	  => '',
                                  	'ADMIN_USERID'        => '',
                                  	'ADMIN_EMAIL'      => ''
                               
                           	        );	    
		    $this->CI->session->unset_userdata($session_data);						
			$this->CI->session->sess_destroy();
			return TRUE;
		}
		
		function admin_has_permission($permission_id){
			
				if($this->CI->session->userdata ('ADMIN_USERTYPE')=="A") return true;
				else { 
					
					$permission_array = array();
					$this->CI->db->select ("role_id");
					$this->CI->db->where ('admin_id', $this->CI->session->userdata ('ADMIN_USERID'));
	
					$select_query    = $this->CI->db->get ("admin_role_details");		
					if (0 < $select_query->num_rows ())
					{
						$result = $select_query->result_array();
						foreach ($result as $result){
							array_push($permission_array,$result['role_id']);
						}
						if(in_array($permission_id,$permission_array)) return true;
						else return false;
					}
					else return false;
				}
				
		}
		/**
		 * auto login
		 *
		 * @param unknown_type $login
		 * @param unknown_type $request
		 * @return unknown
		 */
		function process_auto_admin_login ($login = NULL, $request = '')
		{
				    
    			if (!is_array ($login) || 0 >= count ($login))
    			{
    				return FALSE;
    			}
    			$emailid        = $login['emailid'];
    			$password        = $login['password'];
    			
				$this->CI->db->select ("username AS USERNAME, admin_id as USERID,  webmaster  as webmaster, first_name as NAME ");
				$this->CI->db->where ('email', $emailid);
				$this->CI->db->where ('password', $password);
				$this->CI->db->where ('status', 'A');
				$select_query    = $this->CI->db->get ('admin_user_details');	
								
				if (0 < $select_query->num_rows ())
				{
					$row 			= $select_query->row();  					
					
					
					$user_type =$row->webmaster;     		
	                $session_data 	= array (
	                                           	
	                                          	'ADMIN_USERNAME'      => $row->USERNAME,
	                                          	'ADMIN_NAME'      	  => $row->NAME,
	                                          	'ADMIN_USERID'        => $row->USERID,
	                                          	'ADMIN_USERTYPE'      => $user_type
	                                          	
	                                           	                                      
	                              	        );
					$this->CI->session->set_userdata ($session_data);
					return true;
				}
				else 	
					return false;
				
		}
		

		
		 
		function check_admin_status($user_id)
		{
			$status			 = 1;
			$this->CI->db->select ("id");
			$this->CI->db->where ('id', $user_id);
			$this->CI->db->where ('user_status', $status);
			$select_query    = $this->CI->db->get ('admin_users');
			
			
			if (0 < $select_query->num_rows ())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
                function check_logged_in ($user_type = "normal")
		{
                    switch ($user_type) {

                          case "admin":
							  		
                                    if (!$this->CI->session->userdata ('ADMIN_USERID')){
                                          return FALSE;
                                    }
									else if(!$this->check_admin_status($this->CI->session->userdata ('ADMIN_USERID'))){
										$session_data 	= array (                               
									
							                                    'ADMIN_USERNAME'      => '',
							                                  	'ADMIN_NAME'      	  => '',
							                                  	'ADMIN_USERID'        => '',
							                                  	'ADMIN_EMAIL'      => ''
							                               
							                           	        );	    
									    $this->CI->session->unset_userdata($session_data);						
										$this->CI->session->sess_destroy();
							  			return FALSE;
							  		}
                                    else{
                                        return true;
                                    }
                          break;					
                          default:
                          return FALSE;
                    }
		}
		
		public function check_user_permissions(&$user_permissions, $permissions) {
                    if(!empty($user_permissions)){
	                    foreach ($user_permissions as $k => $v) {
	                        foreach ($permissions as $pk => $pv) {
	                            if ( intval( $v['user_permission'] ) & intval($pv['value']) ){
	                                //echo $v['module_id'].'- can ', $pv['permission'], '<br />';
	                                $user_permissions[$k]['permissions'][$pv['permission_id']] = $pv;
	                            }
	                            else{
	                               //echo $v['module_id']. '- can not ', $pv['permission'], '<br />';
	                            }
	
	                        }
	                    }
					}	
                }

                public function check_single_user_permissions(&$user_permissions, $permissions) {
                    if ( empty ( $user_permissions ) || empty ( $permissions ) )
                                    return false;
                    foreach ($permissions as $pk => $pv) {
                        if (intval($user_permissions['user_permission']) & intval($pv['value'])) {
                            $user_permissions['permissions'][$pv['permission']] = $pv;
                        }
                    }
                }

                public function check_module_permissions(&$modules, $permissions) {
                    foreach ($modules as $k => $v) {
                        foreach ($permissions as $pk => $pv) {
                            if ( intval( $v['permission_value'] ) & intval($pv['value']) ){
                                $modules[$k]['permissions'][] = $pv;
                            }
                        }
                    }
                }

                public function check_access($thispermission,$moduleid,$userid,$user_type_id,$permissions){
                    $this->CI->load->model('permission_model');
                    $mymodule_permission = $this->CI->permission_model->get_single_user_permission($userid,$moduleid,$user_type_id);
                   // print_r($mymodule_permission);
                    if( empty ( $mymodule_permission ) )
                                    return false;
                    if ( ! empty ( $mymodule_permission ) ){
                        $permissions         = array_indexed($permissions, 'permission');
                        $permission_value    = intval($permissions[$thispermission]['value']);
                        $user_permission    = intval($mymodule_permission['user_permission']);
                        if ( ! ( $permission_value & $user_permission )  ){
                            return false;
                        }

                    }
                    return true;
                } 

	}
// End of library class
// Location: system/application/libraries/authentication.php