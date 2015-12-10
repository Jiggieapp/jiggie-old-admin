<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adminuser extends CI_Controller {

    var $gen_contents = array();

    public function __construct() {
		
        parent::__construct();
        $this->merror['error'] = '';
        $this->msuccess['msg'] = '';
        $this->gen_contents['current_controller'] = $this->router->fetch_class();
        $this->load->model(array('admin/admin_model', 'common_model', 'admin/user_model', 'master_model', 'admin/permission_model'));
        $this->load->helper(array('security','path'));
        presetpastdaterange();
        $this->gen_contents['title'] = '';
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
        $this->config->set_item('site_title', 'Party Host  Admin - Admin Users');
        $this->access_userid = $this->session->userdata("ADMIN_USERID");
        $this->access_usertypeid = $this->session->userdata("USER_TYPE_ID");
        $this->access_permissions = $this->permission_model->get_all_permission();
    }
    
     public function index() {
         $this->users();
     }
     
     public function users($init='') {
     	  if(!$this->master_model->checkAccess('create', ADMINUSER_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            return FALSE;
        }

        $this->gen_contents['p_title'] = 'Admin Users List';
        $this->gen_contents['ci_view'] = 'admin/admin_user/listing';
        $this->gen_contents['add_link'] = base_url() . 'admin/adminuser/create';
        $this->gen_contents['export_link'] = base_url() . 'admin/adminuser/export';
        $breadCrumbs = array( 'admin/adminuser/users/0'=>'Admin User');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        $this->template->write_view('content', 'admin/listing', $this->gen_contents);
        $this->template->render();
    }

    public function ajax_list($init='') {
        $config['base_url'] = base_url() . 'admin/adminuser/ajax_list';
    

    		if ('' != $this->input->post('per_page')) {
            $config['per_page'] = $this->input->post('per_page');            
            $perPage = '';
        }        
        else {
            $config['per_page'] = 10;
        }        
        	
		    if('' != $this->input->post ('offset')){
               $offset	= safeInteger($this->input->post ('offset'));			    
        }
		    else {
        	$offset = 1;
        } 
        $this->gen_contents['offset'] = $offset;
         
        $arr_where = array();

        $arr_sort = array();
        if ('' != $this->input->post('sort_field')) {
            $arr_sort['name'] = $this->input->post('sort_field');
        } else {
            $arr_sort['name'] = 'created_at';
        }
        if ('' != $this->input->post('sort_val')) {
            $arr_sort['value'] = $this->input->post('sort_val');
        } else {
            $arr_sort['value'] = 'DESC';
        }
        //Search Factor
        $arr_search = array();
         
        if ($this->input->post('search_name') != "") {
        	$arr_search["where"] = mysql_real_escape_string($this->input->post('search_name')) ;            		
        }else {
           	$arr_search["where"] = "";
            
        }


        $start_date = $this->session->userdata('startDate') . " 00:00:00";
        $end_date = $this->session->userdata('endDate') . " 23:59:59";
        $arr_where = array("created_at >=" => "$start_date", "created_at  <=" => "$end_date", "user_status !=" => "10");

        $config['total_rows'] = $this->master_model->getAllUsers($arr_where, $arr_sort, 'count', $config['per_page'], ($offset-1)*$config['per_page'], $arr_search);
        $config['total_page'] = ceil($config['total_rows'] / $config['per_page']);
		    $config['offset'] = $offset;
	      $this->gen_contents['total_count'] = $config['total_rows'];
        $this->gen_contents['data_search'] = $arr_search["where"];		
        $this->gen_contents['paginate'] = $config;
        $this->gen_contents['data_url'] = $config['base_url'];
        $this->gen_contents['users'] = $this->master_model->getAllUsers($arr_where, $arr_sort, 'list', $config['per_page'], ($offset-1)*$config['per_page'], $arr_search);

        echo json_encode($this->gen_contents);exit;
    }
    
    public function create() {
    	$breadCrumbs = array( 'admin/adminuser/users/0'=>'Admin Users');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs; 
        try {
            if(!$this->master_model->checkAccess('create', ADMINUSER_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }

            if (!empty($_POST)) {
                $this->form_validation->set_rules('email', 'Email', 'required');
                $this->form_validation->set_rules('user_type_id', 'User Type', 'required');
                $this->form_validation->set_rules('first_name', 'First Name', 'required');
                $this->form_validation->set_rules('last_name', 'Last Name', 'required');
                $this->form_validation->set_rules('password', 'Password', 'required');
                //$this->form_validation->set_rules('image_url', 'Image URL', 'required');
                if ( $this->form_validation->run() ){ 
                    $user_type_id = $this->input->post('user_type_id');
                    $usertypeavaible = $this->common_model->getDataExistsArray(array("user_type_id"), "user_type", array("user_type_id !=" => 1));
                    //$usertypeids = array();
                    foreach($usertypeavaible as $usertypeavaible_value) {
                        $usertypeids[] = $usertypeavaible_value["user_type_id"];
                    }
                    
                    if ( !in_array($user_type_id, $usertypeids) ){
                        throw new Exception('Invalid user type.');
                    }
                    
                    
                    if ( ! $this->master_model->check_email_bystatus(null) ){                        
                        
                        $image='';
                        $config =  array(
                          'upload_path'     => $this->config->item("upload_file_path") ."/admin_users/",
                          'upload_url'      => base_url()."uploads/admin_users/",
                          'allowed_types'   => "jpg|png|jpeg",
                          'overwrite'       => FALSE,
                          'max_size'        => "2000",
                            
                        );
                        $this->load->library('upload', $config);
						
						if ( $_FILES AND $_FILES['image']['name'] ) 
						{
	                        if ($this->upload->do_upload('image')) {                            
	                            $data = array('upload_data' => $this->upload->data());                           
	                            $image = $data['upload_data']['file_name']; 
	                       
						    }else{
						    	
								sf('error_message', $this->upload->display_errors ());  
                          		redirect('admin/adminuser/create');
						    }
						}
                        
                        $this->master_model->create_user($image);
                        $this->gen_contents["suc"]       = TRUE;
                        $this->gen_contents["sucmsg"]   = "You have successfully created a new admin account for ".$this->input->post('first_name');
                        
                        if ( $this->gen_contents["suc"] ){
                        	$to_email  = $this->input->post('email');					        
					        $name  = $this->input->post('first_name').' '.$this->input->post('last_name') ; 
					        $from = $this->config->item('smtp_from_name');
					        $subject = "New administrator account";
					        $body_content = "Dear ".$name.",<br /><br />";
					        $body_content .= "Partyhost  has created a new administrator account for you .<br />";
							$body_content .= "Login url :".base_url()."admin<br />";
							$body_content .= "User name :".$to_email."<br /><br />";
					        $body_content .= "Password :".$this->input->post('password')."<br /><br />";
					        $body_content .= "Partyhost";
					        $this->common_model->send_mail($to_email, $from, $subject, $body_content);
                            sf('success_message', $this->gen_contents["sucmsg"]);  
                            redirect('admin/adminuser');
                        }
                    }
                    else {
                        
						sf('error_message', 'User with this Email already exist.');  
                        redirect('admin/adminuser/create');
                    }
                }
                
            } else {
                
            }
            $this->gen_contents["user_types"] = $this->common_model->getDataExistsArray(array("user_type_id","user_type_name"), "user_type", array("user_type_id !=" => "1"));
            $this->template->write_view('content', 'admin/admin_user/create',$this->gen_contents);
            $this->template->render();
        } catch (Exception $e) {
            sf('error_message',  $e->getMessage());
            $this->gen_contents["user_types"] = $this->common_model->getDataExistsArray(array("user_type_id","user_type_name"), "user_type", array("user_type_id !=" => "1"));
            $this->template->write_view('content', 'admin/admin_user/create',$this->gen_contents);
            $this->template->render();
        }
    }
    

    public function details($adminuser_id = "") {
        $breadCrumbs = array( 'admin/adminuser/users/0'=>'Admin User','admin/adminuser/details/'.$adminuser_id=>'Admin User Details');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        try {
            (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
            if (empty($adminuser_id)) {
            	 sf('error_message', "User Id should not be empty");  
                 redirect('admin/adminuser');
                 //throw new Exception("User Id should not be empty");
            }
            $seleced_fields = array("id", "CONCAT_WS(' ', first_name, last_name) as name", "email", "first_name", "last_name", "user_type_id", "profile_image_url", "email", "user_status", "created_at");
            $this->gen_contents['adminuser_id'] = $adminuser_id;
            $admin_user = $this->common_model->getDataExists($seleced_fields, "admin_users", array("id" => $adminuser_id,'user_status !='=>10));
           
		    if (!$admin_user) {
		    	sf('error_message', "Admin User details not found");  
                redirect('admin/adminuser');		    	
               // throw new Exception("Admin User details not found");
            }
            
            if (!empty($_POST)) {
                
                //->form_validation->set_rules('email', 'Email', 'required');
                $this->form_validation->set_rules('user_type_id', 'User Type', 'required');
                $this->form_validation->set_rules('first_name', 'First Name', 'required');
                $this->form_validation->set_rules('last_name', 'Last Name', 'required');
                //$this->form_validation->set_rules('image_url', 'Image URL', 'required');
                if ( $this->form_validation->run() ){ 
                    $user_type_id = $this->input->post('user_type_id');
                    $usertypeavaible = $this->common_model->getDataExistsArray(array("user_type_id"), "user_type", array("user_type_id !=" => 1));
                    //$usertypeids = array();
                    foreach($usertypeavaible as $usertypeavaible_value) {
                        $usertypeids[] = $usertypeavaible_value["user_type_id"];
                    }
                    
                    if ( !in_array($user_type_id, $usertypeids) ){
                        throw new Exception('Invalid user type.');
                    }
                        
                    $image='';
                    $config =  array(
                      'upload_path'     => $this->config->item("upload_file_path") ."/admin_users/",
                      'upload_url'      => base_url()."uploads/admin_users/",
                      'allowed_types'   => "jpg|png|jpeg",
                      'overwrite'       => FALSE,
                      'max_size'        => "10000",

                    );
                    $this->load->library('upload', $config);
                   
                    if ( $_FILES AND $_FILES['image']['name'] ) 
					{
                        if ($this->upload->do_upload('image')) {                            
                            $data = array('upload_data' => $this->upload->data());                           
                            $image = $data['upload_data']['file_name']; 
                       
					    }else{
					    	
							sf('error_message', $this->upload->display_errors ());  
                      		redirect('admin/adminuser/details/'.$adminuser_id);
					    }
					}
                    $this->master_model->update_adminuser($adminuser_id,$image);
                    $this->gen_contents["suc"]       = TRUE;
                    $this->gen_contents["sucmsg"]   = "You have successfully edited admin account for ".$this->input->post('first_name');

                    if ( $this->gen_contents["suc"] ){
                      sf('success_message', $this->gen_contents["sucmsg"]);  
                      redirect('admin/adminuser');
                    }
                }
                
            }
            
            $this->gen_contents["admin_user"] = $admin_user;
            $this->gen_contents["user_types"] = $this->common_model->getDataExistsArray(array("user_type_id","user_type_name"), "user_type", array("user_type_id !=" => "1"));

            $this->template->write_view('content', 'admin/admin_user/detail', $this->gen_contents);
            $this->template->render();
        } catch (Exception $e) {
            $this->gen_contents["error"] = $e->getMessage();
            $this->template->write_view('content', 'admin/admin_user/detail', $this->gen_contents);
            $this->template->render();
        }
    }
    
    public function save($user_id="") {

        try {
            (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
            $user =  $user_id;
            $data = $this->input->post('value');
            $label = $this->input->post('name');
            $data1 = $label1 = "";
            if ($this->master_model->update_admin_user($label, $data, $user, $data1, $label1)) {
                echo "Data updated";
            } else {
                echo "Updation failed";
            }
        } catch (Exception $e) {
            
        }
        exit;
    }
    
    

    public function permission($userid=""){
    	 $breadCrumbs = array( 'admin/adminuser/users/0'=>'Admin User','admin/adminuser/details/'.$userid=>'Admin User Permissions');
         $this->gen_contents['breadcrumbs'] = $breadCrumbs;
    	 $admin_user = $this->common_model->getDataExists(array('id'), "admin_users", array("id" => $userid,'user_status !='=>10));
           
		    if (!$admin_user) {
		    	sf('error_message', "Admin User details not found");  
                redirect('admin/adminuser');		    	
               // throw new Exception("Admin User details not found");
            }
             
        if(!$this->master_model->checkAccess('update', ADMINUSER_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            return FALSE;
        }
		
        //$this->gen_contents = array();
        try {
            if($userid == 1) {
                throw new Exception("Access Restricted!");
            }
            if (empty($userid)) {
                throw new Exception("User Id should not be empty");
            }
            $this->gen_contents['item'] = $this->common_model->getDataExists(array("email","user_type_id","id"), "admin_users", array("id" => $userid));
            if (empty($this->gen_contents['item'])) {
                throw new Exception("Invalid user");
            }
            
            $this->permission_process($this->gen_contents['item']);
            if ( ! empty ( $this->gen_contents['my_permissions'] ) )$this->permission_create($this->gen_contents['my_permissions'],$this->gen_contents['item']);
            $this->permission_process($this->gen_contents['item']);

            $this->template->write_view('content', 'admin/admin_user/permission',$this->gen_contents);
            $this->template->render();
        }
        catch (Exception $e) {
            //echo $e->getMessage();
            $this->template->write_view('content', 'admin/admin_user/permission',$this->gen_contents);
            $this->template->render();
        }
    }
    
    private function permission_process($userdetails){
        
        $user_type_permission = $this->permission_model->get_usertype_permission($userdetails->user_type_id);
        
        if( ! empty ( $user_type_permission ) ){
            $user_type_permissions = array_indexed($user_type_permission, "module_id");
            $module_ids = array_keys($user_type_permissions);
        }
        $modules = $this->permission_model->get_module_byids($module_ids);
        $my_permissions = $this->permission_model->get_user_permissions($userdetails->id,$userdetails->user_type_id);
        
        $permissions = $this->permission_model->get_all_permission();
        
        $my_permissions = array_indexed($my_permissions, "module_id");
        $modules        = array_indexed($modules, "module_id");
        $permissions    = array_indexed($permissions, "permission_id");
        
        $this->authentication->check_user_permissions($my_permissions, $permissions);
        $this->authentication->check_module_permissions($modules, $permissions);
        
        $finalmodule = array();
        foreach ($modules as $mk => $mod) {
             $finalmodule[$mod['module_id']] = $mod;
             $finalmodule[$mod['module_id']]['modulepermission'] = array_indexed($mod['permissions'],'permission_id');
             
             if (!empty($my_permissions[$mod['module_id']])) {
                 foreach ( $mod['permissions'] as $mdk=>$modper){
                    foreach ( $my_permissions[$mod['module_id']]['permissions'] as $myper){
                        if( $modper['permission_id'] == $myper['permission_id'] ){
                            $finalmodule[$mod['module_id']]['modulepermission'][$modper['permission_id']]['haspermission'] = true;
                        }
                    }
                 }
                //$finalmodule[$mod['module_id']]['modulepermission'] = array_indexed($modules[$mod['module_id']]['permissions'], 'permission_id');
            }
        }
        foreach ($finalmodule as $fk=>$fm){
            if( ! empty ( $fm['modulepermission'] ) ){
                $finalmodule[$fk]['nochild'] = $this->check_submodule($fm['modulepermission']);
            }
        }
        
        $tree = $this->buildTree($finalmodule);
//        print "<pre>";
//        print_r($tree);
//        exit;
        //$trees = $this->getChildrenFor($finalmodule[9],9);
        
        //unset ($my_permissions);
        $this->gen_contents['modules'] = $tree;
        $this->gen_contents['my_permissions'] = $my_permissions;
        unset($modules);
        unset($finalmodule);
        unset($tree);
        unset($my_permissions);
        
    }
    
    private function check_submodule($modules) {
        $error = true;
        foreach ($modules as $key => $value) {
            if(  isset ( $value['haspermission'] ) ){
                $error = false;
            }
        }
        return $error;
    }
    
    private function buildTree(&$categories) {

        $map = array(
            0 => array('subcategories' => array())
        );

        foreach ($categories as &$category) {
            $category['subcategories'] = array();
            $map[$category['module_id']] = &$category;
        }

        foreach ($categories as &$category) {
            $map[$category['sub_of']]['subcategories'][] = &$category;
        }

        return $map[0]['subcategories'];

    }
    private function permission_create($my_permissions,$userdetails){ 
        if(!empty($_POST)) {    
        $this->form_validation->set_rules('user_permission[]', 'Permission', 'required');
        if ( $this->form_validation->run() ){
            try{
                $user_permission = $this->input->post('user_permission');
                if ( ! empty ( $my_permissions ) ){
                    foreach ( $my_permissions as $mp ){
                        $this->permission_model->delete_user_permission($mp['user_permission_id'],$mp['user_id'],$mp['user_type_id']);
                    }
                }
                
                if ( ! empty ( $user_permission ) ){ 
                    foreach ( $user_permission as $module_id=>$up ){
                        $data = array();
                        $data['user_permission']    = array_sum($up);
                        $data['module_id']          = $module_id;
                        $data['user_id']            = $userdetails->id;
                        $data['user_type_id']       = $userdetails->user_type_id;
                        $data['status_id'] = 1;
                        $new_data                   = array_merge($data, $this->permission_model->default_fields_create());
                        $ins_success                = $this->permission_model->insert_user_permission($new_data);
                    }
                }
                
                //$this->gen_contents["suc"]         = TRUE;
                $this->gen_contents["sucmsg"]      = "User permissions has been set successfully";
                //if ( notify_success($this->gen_contents["sucmsg"]) ){
                    //redirect('admin/device');
                    //exit;
                //}
            }
            catch (Exception $e){
                notify_error($e->getMessage());
            }
        }
        else{
        	$this->gen_contents["errormsg"]      = "Please select at least one permission ";
             //notify_error(validation_errors());
        }
        }
    }
    
    public function suspend_user() {
        try {
            (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
            $data = $this->input->post('data');
            $user = $this->input->post('user');
            
            if ($this->master_model->suspend_admin_user($data, $user)) {
                echo $data;
            } else {
                echo "";
            }
        } catch (Exception $e) {
            
        }
        exit;
    }
    

    public function delete($userid=""){ 
        if(!$this->master_model->checkAccess('delete', ADMINUSER_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            return FALSE;
        }
	    $seleced_fields = array("id", "CONCAT_WS(' ', first_name, last_name) as name", "email", "first_name", "last_name", "user_type_id", "profile_image_url", "email", "user_status", "created_at");
       
        $admin_user = $this->common_model->getDataExists($seleced_fields, "admin_users", array("id" => $userid,'user_status !='=>10));
           
        $this->master_model->delete_user($userid);
		$to_email  = $admin_user->email;					        
        
        $from = $this->config->item('smtp_from_name');
        $subject = "Administrator account deleted";
        $body_content = "Dear ".$admin_user->name.",<br />";
        $body_content .= "Partyhost  has deleted your administrator account .<br /><br />";
		
        $body_content .= "Partyhost";
        $this->common_model->send_mail($to_email, $from, $subject, $body_content);
        sf('success_message', 'Admin User has been deleted successfully');
        redirect("admin/adminuser");
    }
    
    function check_email() {
        $email = $this->input->post('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array("status" => 2));
            exit;
        }
        $email_exists = $this->common_model->getDataExists(array("email"), "admin_users", array("email" => $email,"user_status !=" => 10));
        if (empty($email_exists)) {
            echo json_encode(array("status" => 1));
        } else {
            echo json_encode(array("status" => 0));
        }

        exit;
    }
    
    public function export() {
    	
        try {
            if(!$this->master_model->checkAccess('export', ADMINUSER_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            $start_date = $this->session->userdata('startDate') . " 00:00:00";
            $end_date = $this->session->userdata('endDate') . " 23:59:59";
            $url= $_SERVER['QUERY_STRING'];
			$pars = explode('/',$url);
			$data = array();
			if($url){
				foreach ($pars as $values){
				 	$val = explode('=' ,$values);
					$data[$val[0]] =$val[1];
				 }
			 }
			if(isset($data['sort_field']))
				$arr_sort['name'] = @$data['sort_field'] ; 
			if(isset($data['sort_val']))
				$arr_sort['value'] = @$data['sort_val'] ;  
			$arr_search["where"] = mysql_real_escape_string(@$data['search_name']) ;              
            $this->master_model->export($start_date, $end_date,  @$arr_sort,  @$arr_search);
            
            //exit;
        } catch (Exception $e) {
            echo  $e->getMessage();
			
        }
    }
}    