<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adminuser extends CI_Controller {

    var $gen_contents = array();

    public function __construct() {

        parent::__construct();
        $this->merror['error'] = '';
        $this->msuccess['msg'] = '';
        
        $this->load->model(array('admin/admin_model', 'common_model', 'admin/user_model', 'master_model', 'admin/permission_model'));
        $this->load->helper(array('security','path'));
        
        $this->gen_contents['title'] = '';
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
        
        $this->access_userid = $this->session->userdata("ADMIN_USERID");
        $this->access_usertypeid = $this->session->userdata("USER_TYPE_ID");
        $this->access_permissions = $this->permission_model->get_all_permission();
        
    }
    
     public function index() {
         if(!$this->master_model->checkAccess('view', ADMINUSER_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            return FALSE;
        } else {
            $this->users();
        }
     }
     
     public function users() {
        $this->ajax_list();
      
        $this->gen_contents['p_title'] = 'Admin Users List';
        $this->gen_contents['ci_view'] = 'admin/admin_user/listing';
        $this->gen_contents['add_link'] = base_url() . 'admin/adminuser/create';
        $this->gen_contents['export_link'] = base_url() . 'admin/adminuser/export';
        $this->gen_contents['current_controller'] = "adminuser";
        $breadCrumbs = array( 'admin/adminuser'=>'Admin User');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        $this->template->write_view('content', 'admin/listing', $this->gen_contents);
        $this->template->render();
    }

    public function ajax_list() {


        $headers = apache_request_headers();
        $is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');
        $this->load->library('pagination');

        $config['base_url'] = base_url() . 'admin/adminuser/ajax_list';
        if ('' != $this->input->post('per_page')) {
            $config['per_page'] = $this->input->post('per_page');
        } else {
            $config['per_page'] = 10;
        }


        if ('' != $this->uri->segment(4)) {
            $offset = safeOffset(4);
        } else {
            $offset = 0;
        }
        $this->mcontents['offset'] = $offset;
        $config['uri_segment'] = 4;
        $config['callbackfunction'] = 'loadListing';
        $arr_where = array();

        $arr_sort = array();
        if ('' != $this->input->post('sort_field')) {
            $arr_sort['name'] = $this->input->post('sort_field');
        } else {
            $arr_sort['name'] = 'name';
        }
        if ('' != $this->input->post('sort_val')) {
            $arr_sort['value'] = $this->input->post('sort_val');
        } else {
            $arr_sort['value'] = 'DESC';
        }
        //Search Factor
        $arr_search = array();
        if ($this->input->post('search_name') != "") {
            $arr_search["where"] = $this->input->post('search_name');
            $this->session->set_userdata("user_search", $arr_search["where"]);
        } else {
            $arr_search["where"] = "";
            $this->session->set_userdata("user_search", "");
        }


        $start_date = $this->session->userdata('startDate') . " 00:00:00";
        $end_date = $this->session->userdata('endDate') . " 23:59:59";
        $arr_where = array("created_at >=" => "$start_date", "created_at  <=" => "$end_date", "user_status !=" => "10");

        $config['total_rows'] = $this->master_model->getAllUsers($arr_where, $arr_sort, 'count', $config['per_page'], $offset, $arr_search);
        $this->gen_contents['total_count'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $this->gen_contents['paginate'] = $this->pagination->create_links_ajax();
        $this->gen_contents['data_url'] = $config['base_url'];
        $this->gen_contents['users'] = $this->master_model->getAllUsers($arr_where, $arr_sort, 'list', $config['per_page'], $offset, $arr_search);

        if ($is_ajax) {
            $this->load->view('admin/admin_user/listing', $this->gen_contents);
        }
    }
    
    public function create() { 
        try {
            if(!$this->master_model->checkAccess('create', ADMINUSER_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }

            $this->mcontents = array();

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
                          'allowed_types'   => "gif|jpg|png|jpeg",
                          'overwrite'       => FALSE,
                          'max_size'        => "10000",
                            
                        );
                        $this->load->library('upload', $config);
                        if ($this->upload->do_upload('image')) {
                            
                            $data = array('upload_data' => $this->upload->data());
                           
                            $image = $data['upload_data']['file_name']; 
                        }
                        
                        
                        $this->master_model->create_user($image);
                        $this->mcontents["suc"]       = TRUE;
                        $this->mcontents["sucmsg"]   = "You have successfully created a new admin account for ".$this->input->post('first_name');
                        
                        if ( $this->mcontents["suc"] ){
                          sf('success_message', $this->mcontents["sucmsg"]);  
                          redirect('admin/adminuser');
                        }
                    }
                    else {
                        throw new Exception('User with this Email already exist.');
                    }
                }
                
            } else {
                
            }
            $this->mcontents["user_types"] = $this->common_model->getDataExistsArray(array("user_type_id","user_type_name"), "user_type", array("user_type_id !=" => "1"));
            $this->template->write_view('content', 'admin/admin_user/create',$this->mcontents);
            $this->template->render();
        } catch (Exception $e) {
            sf('error_message',  $e->getMessage());
            $this->mcontents["user_types"] = $this->common_model->getDataExistsArray(array("user_type_id","user_type_name"), "user_type", array("user_type_id !=" => "1"));
            $this->template->write_view('content', 'admin/admin_user/create',$this->mcontents);
            $this->template->render();
        }
    }
    

    public function details($adminuser_id = "") {
        $breadCrumbs = array( 'admin/adminuser'=>'Admin User','admin/adminuser/details/'.$adminuser_id=>'Admin User Details');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        try {
            (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
            if (empty($adminuser_id)) {
                throw new Exception("User Id should not be empty");
            }
            $seleced_fields = array("id", "CONCAT_WS(' ', first_name, last_name) as name", "email", "first_name", "last_name", "user_type_id", "profile_image_url", "email", "user_status", "created_at");
            $this->gen_contents['adminuser_id'] = $adminuser_id;
            $admin_user = $this->common_model->getDataExists($seleced_fields, "admin_users", array("id" => $adminuser_id));
            if (empty($admin_user)) {
                throw new Exception("Admin User details not found");
            }
            
            if (!empty($_POST)) {
                
                $this->form_validation->set_rules('email', 'Email', 'required');
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
                      'allowed_types'   => "gif|jpg|png|jpeg",
                      'overwrite'       => FALSE,
                      'max_size'        => "10000",

                    );
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('image')) {

                        $data = array('upload_data' => $this->upload->data());

                        $image = $data['upload_data']['file_name']; 
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
        if(!$this->master_model->checkAccess('update', ADMINUSER_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            return FALSE;
        }
        $this->mcontents = array();
        try {
            if($userid == 1) {
                throw new Exception("Access Restricted!");
            }
            if (empty($userid)) {
                throw new Exception("User Id should not be empty");
            }
            $this->mcontents['item'] = $this->common_model->getDataExists(array("email","user_type_id","id"), "admin_users", array("id" => $userid));
            if (empty($this->mcontents['item'])) {
                throw new Exception("Invalid user");
            }
            
            $this->permission_process($this->mcontents['item']);
            if ( ! empty ( $this->mcontents['my_permissions'] ) )$this->permission_create($this->mcontents['my_permissions'],$this->mcontents['item']);
            $this->permission_process($this->mcontents['item']);

            //$this->load->view('fleetadmin/header', $this->mcontents);
            //$this->load->view('fleetadmin/fleetpermission');
            //$this->load->view('fleetadmin/footer');
            $this->template->write_view('content', 'admin/admin_user/permission',$this->mcontents);
            $this->template->render();
        }
        catch (Exception $e) {
            //echo $e->getMessage();
            $this->template->write_view('content', 'admin/admin_user/permission',$this->mcontents);
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
        $this->mcontents['modules'] = $tree;
        $this->mcontents['my_permissions'] = $my_permissions;
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
                
                //$this->mcontents["suc"]         = TRUE;
                $this->mcontents["sucmsg"]      = "User permissions has been set successfully";
                //if ( notify_success($this->mcontents["sucmsg"]) ){
                    //redirect('admin/device');
                    //exit;
                //}
            }
            catch (Exception $e){
                notify_error($e->getMessage());
            }
        }
        else{
             notify_error(validation_errors());
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
        $this->master_model->delete_user($userid);
        sf('success_message', 'Admin User deleted successfully');
        redirect("admin/adminuser");
    }
    
    function check_email() {
        $email = $this->input->post('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array("status" => 2));
            exit;
        }
        $email_exists = $this->common_model->getDataExists(array("email"), "admin_users", array("email" => $email));
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
            $this->master_model->export($start_date, $end_date, $this->session->userdata("user_search"));
            exit;
        } catch (Exception $e) {
            
        }
    }
}    