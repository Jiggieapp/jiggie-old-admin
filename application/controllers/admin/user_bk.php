<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    var $gen_contents = array();

    public function __construct() {

        parent::__construct();
        $this->merror['error'] = '';
        $this->msuccess['msg'] = '';
        $this->load->model(array('admin/admin_model', 'common_model', 'admin/user_model','admin/permission_model','master_model'));
        $this->gen_contents['title'] = '';
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
        
        $this->access_userid = $this->session->userdata("ADMIN_USERID");
        $this->access_usertypeid = $this->session->userdata("USER_TYPE_ID");
        $this->access_permissions = $this->permission_model->get_all_permission();
    }

    public function index() {
        /* $seleced_fields = array("id", "CONCAT_WS(' ', first_name, last_name) as name", "gender", "TIMESTAMPDIFF(YEAR, birthday, CURDATE()) AS age", "location", "invited_by", "created_at");
          $start_date = $this->session->userdata('startDate')." 00:00:00";
          $end_date = $this->session->userdata('endDate'). " 23:59:59";
          $where = array("created_at >="=>"$start_date","created_at <="=>"$end_date");
          $users = $this->common_model->getDataExistsArray($seleced_fields, "users", $where, "", "first_name");
          $this->gen_contents['users'] = $users;
          $this->template->write_view('content', 'admin/user/listing',$this->gen_contents);
          $this->template->render(); */
        
        if(!$this->master_model->checkAccess('view', USERS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
           return FALSE;
        }
        else {
            $this->users();
        }
    }

    public function users() {
        $this->ajax_list();
      
        $this->gen_contents['p_title'] = 'Users List';
        $this->gen_contents['ci_view'] = 'admin/user/listing';
        $this->gen_contents['add_link'] = base_url() . 'admin/user/create_user';
        $this->gen_contents['export_link'] = base_url() . 'admin/user/export';
        $this->gen_contents['current_controller'] = "user";
        $breadCrumbs = array( 'admin/user'=>'Users');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        $this->template->write_view('content', 'admin/listing', $this->gen_contents);
        $this->template->render();
    }

    public function ajax_list() {


        $headers = apache_request_headers();
        $is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');
        $this->load->library('pagination');

        $config['base_url'] = base_url() . 'admin/user/ajax_list';
        $perPage = $this->session->userdata('per_page');
        if ('' != $this->input->post('per_page')) {
            $config['per_page'] = $this->input->post('per_page');            
            $perPage = '';
        }        
        else {
            $config['per_page'] = 10;
        }
        
        if($perPage==''):
            $this->session->set_userdata(array('per_page'=>$config['per_page']));
        else:
            $config['per_page'] = $this->session->userdata('per_page');
        endif;
        
        
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

        $config['total_rows'] = $this->user_model->getAllUsers($arr_where, $arr_sort, 'count', $config['per_page'], $offset, $arr_search);
        $this->gen_contents['total_count'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $this->gen_contents['paginate'] = $this->pagination->create_links_ajax();
        $this->gen_contents['data_url'] = $config['base_url'];
        $this->gen_contents['users'] = $this->user_model->getAllUsers($arr_where, $arr_sort, 'list', $config['per_page'], $offset, $arr_search);

        if ($is_ajax) {
            $this->load->view('admin/user/listing', $this->gen_contents);
        }
    }

    public function image_listing() {

        $seleced_fields = array("profile_image_url", "CONCAT_WS(' ', first_name, last_name) as name", "TIMESTAMPDIFF(YEAR, birthday, CURDATE()) AS age", "created_at", "location");
        $start_date = $this->session->userdata('startDate') . " 00:00:00";
        $end_date = $this->session->userdata('endDate') . " 23:59:59";
        $where = array("created_at >=" => "$start_date", "created_at <=" => "$end_date");
        $users = $this->common_model->getDataExistsArray($seleced_fields, "users", $where, "", "first_name");
        $this->gen_contents['users'] = $users;
        $this->template->write_view('content', 'admin/user/image_listing', $this->gen_contents);
        $this->template->render();
    }

    public function user_details($user_id = "") {
        $breadCrumbs = array( 'admin/user'=>'Users','admin/user/user_details/'.$user_id=>'User Details');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        try {
            $this->gen_contents['user_id'] = $user_id;
            
            if (empty($user_id)) {
                throw new Exception("User Id should not be empty");
            }
            
            if(!$this->master_model->checkAccess('update', USERS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            
            
            $seleced_fields = array("fb_id", "CONCAT_WS(' ', first_name, last_name) as name", "email", "first_name", "last_name", "nick_name", "profile_image_url", "about", "gender", "birthday", "location", "tagline", "phone", "invite_code", "invited_by", "created_at", "updated_at", "verified", "user_status");
            
            $user = $this->common_model->getDataExists($seleced_fields, "users", array("id" => $user_id));
            if (empty($user)) {
                throw new Exception("User details not found");
            }
            $this->gen_contents["user"] = $user;
            $this->template->write_view('content', 'admin/user/detail', $this->gen_contents);
            $this->template->render();
        } catch (Exception $e) {
            $this->gen_contents["error"] = $e->getMessage();
            $this->template->write_view('content', 'admin/user/detail', $this->gen_contents);
            $this->template->render();
        }
    }

    public function save($user_id="") {

        try {
            if(!$this->master_model->checkAccess('update', USERS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            $user = ($this->input->post('user')) ? $this->input->post('user') : $user_id;
            $data = $this->input->post('value');
            $label = $this->input->post('name');
            $data1 = $label1 = "";
            if ($this->user_model->updateUser($label, $data, $user, $data1, $label1)) {
                echo "Data updated";
            } else {
                echo "Updation failed";
            }
        } catch (Exception $e) {
            
        }
        exit;
    }

    public function save_gender() {
        try {
            if(!$this->master_model->checkAccess('update', USERS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            $data = $this->input->post('data');
            $user = $this->input->post('user');
            if ($this->user_model->updateUserGender($data, $user)) {
                echo "Data updated";
            } else {
                echo "Updation failed";
            }
        } catch (Exception $e) {
            
        }
        exit;
    }

    public function savebirthday($user_id="") {
        try {
            if(!$this->master_model->checkAccess('update', USERS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            $data = $this->input->post('value');
            $user = ($this->input->post('user')) ? $this->input->post('user') : $user_id;
            if ($this->user_model->updateBirthday($data, $user)) {
                echo $data;
            } else {
                echo "";
            }
        } catch (Exception $e) {
            
        }
        exit;
    }

    public function verified_host() {
        try {
            if(!$this->master_model->checkAccess('update', USERS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            $data = $this->input->post('data');
            $user = $this->input->post('user');
            if ($data == 0)
                $data = 1; else
                $data = 0;
            if ($this->user_model->verifyhost($data, $user)) {
                echo $data;
            } else {
                echo "";
            }
        } catch (Exception $e) {
            
        }
        exit;
    }

    public function suspend_user() {
        try {
            if(!$this->master_model->checkAccess('update', USERS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            $data = $this->input->post('data');
            $user = $this->input->post('user');
            //if($data == 5) $data = 1; else $data = 5;
            if ($this->user_model->suspend_user($data, $user)) {
                echo $data;
            } else {
                echo "";
            }
        } catch (Exception $e) {
            
        }
        exit;
    }

    public function delete_user($user_id) {
        try {
            if(!$this->master_model->checkAccess('delete', USERS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            if ($this->user_model->delete_user($user_id)) {
                sf('success_message', 'User deleted successfully');
                redirect("admin/user");
            } else {
                sf('error_message', 'Please try again');
                redirect("admin/user");
            }
        } catch (Exception $e) {
            sf('error_message', 'Please try again');
            redirect("admin/user");
        }
    }

    public function create_user() {
        $this->load->helper('string');
        
        try {
           
            if(!$this->master_model->checkAccess('create', USERS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }

            $this->mcontents = array();
            
            if (!empty($_POST)) {
                $this->_init_user_validation_rules();
                if ($this->form_validation->run() == FALSE) {// form validation
                    sf('error_message', 'Invalid Email');
                } else {
                    $post_data = array();
                    $post_data["email"] = $this->input->post("email");
                    $password = $this->input->post("password");
                    $post_data["first_name"] = $this->input->post("first_name");
                    $post_data["last_name"] = $this->input->post("last_name");
                    $post_data["nick_name"] = $this->input->post("nick_name");
                    $post_data["about"] = $this->input->post("about");
                    $post_data["gender"] = $this->input->post("gender");
                    $post_data["birthday"] = date("Y-m-d", strtotime($this->input->post("birthday")));
                    $post_data["location"] = $this->input->post("location");
                    $post_data["tagline"] = $this->input->post("tagline");
                    $post_data["is_promoter"] = $this->input->post("is_promoter");
                    $post_data["invite_code"] = random_string('alnum', 16);
                    $post_data["invited_by"] =  $this->session->userdata('ADMIN_NAME');                    
                    $post_data["user_status"] = 1;
                   
                    $profile_image ='';
                    $config =  array(
                      'upload_path'     => $this->config->item("upload_file_path") ."/users/",
                      'upload_url'      => base_url()."uploads/users/",
                      'allowed_types'   => "gif|jpg|png|jpeg",
                      'overwrite'       => FALSE,
                      'max_size'        => "10000",

                    );
                    $this->load->library('upload', $config);
                    
                    if ($this->upload->do_upload('image1')) {

                        $data = array('upload_data' => $this->upload->data());

                        $profile_image = $config['upload_url'].$data['upload_data']['file_name'].','; 
                        
                    }
                    
                    if ($this->upload->do_upload('image2')) {

                        $data = array('upload_data' => $this->upload->data());

                        $profile_image .= $config['upload_url'].$data['upload_data']['file_name'].','; 
                        
                    }
                    
                    if ($this->upload->do_upload('image3')) {

                        $data = array('upload_data' => $this->upload->data());

                        $profile_image .= $config['upload_url'].$data['upload_data']['file_name'].','; 
                        
                    }
                    
                    if ($this->upload->do_upload('image4')) {

                        $data = array('upload_data' => $this->upload->data());

                        $profile_image .= $config['upload_url'].$data['upload_data']['file_name'].','; 
                        
                    }
                                        
                    if ($this->upload->do_upload('image5')) {

                        $data = array('upload_data' => $this->upload->data());

                        $profile_image .= $config['upload_url'].$data['upload_data']['file_name']; 
                        
                    }
                    
                    $this->user_model->save_user($post_data, $profile_image, $password);
                    sf('success_message', 'User created successfully');
                    redirect("admin/user");
                }
            } else {
                
            }

            $this->template->write_view('content', 'admin/user/create_user');
            $this->template->render();
        } catch (Exception $e) {
            
        }
    }

    function _init_user_validation_rules() {
        $this->form_validation->set_rules('email', 'email', 'required|valid_email|max_length[50]');
    }

    function check_email() {
        $email = $this->input->post('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array("status" => 2));
            exit;
        }
        $email_exists = $this->common_model->getDataExists(array("email"), "users", array("email" => $email));
        if (empty($email_exists)) {
            echo json_encode(array("status" => 1));
        } else {
            echo json_encode(array("status" => 0));
        }

        exit;
    }

    public function upload($image) {
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
       
        $image='';
        $config['upload_path'] = $this->config->item("upload_file_path") . "users/";
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 1 * 1024 * 1024;
        $config['remove_spaces'] = TRUE;
        $config['overwrite'] = true;

        //echo json_encode($this->user_model->handle_upload("qqfile",$config));
        //$this->load->library("qqfileuploader");
        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        //$allowedExtensions = array("jpeg", "gif", "jpg", "png");
        // max file size in bytes
        //$sizeLimit = 10 * 1024 * 1024;




        // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
        //$result = $this->qqfileuploader->handleUpload('uploads/users/');

        // to pass data through iframe you will need to encode all html tags
        //print (json_encode($result));
        //echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        //exit;
        
        $this->load->library('upload', $config);
        if ($this->upload->do_upload($image)) {

            $data = array('upload_data' => $this->upload->data());

            $image = $data['upload_data']['file_name']; 
        }else{
                             throw new Exception('Error in Uploded Image.');
        }
        
        return $image;
    }

    public function image($user_id) {
        try {
           if(!$this->master_model->checkAccess('create', USERS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }

            if (empty($user_id)) {
                throw new Exception("User Id should not be empty");
            }
            $seleced_fields = array("profile_image_url", "profile_image_url_2", "profile_image_url_3", "profile_image_url_4", "profile_image_url_5");
            $this->gen_contents['user_id'] = $user_id;
            $user = $this->common_model->getDataExists($seleced_fields, "users", array("id" => $user_id));
            if (empty($user)) {
                throw new Exception("User details not found");
            }
            $this->gen_contents["user"] = $user;

            $this->template->write_view('content', 'admin/user/image', $this->gen_contents);
            $this->template->render();
        } catch (Exception $e) {
            
        }
    }

    public function profile_image($upload_file, $field, $user) {
        $old_img = $this->input->get('img', TRUE);
        if ($old_img) {
            $basename = basename(urldecode($old_img));
        }
        $config['upload_path'] = $this->config->item("upload_file_path") . "users/";
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 1 * 1024 * 1024;
        $config['remove_spaces'] = TRUE;
        $config['overwrite'] = true;
        $this->load->library('upload', $config);
        $this->upload->do_upload($upload_file);
        $file_data = $this->upload->data();
        if (file_exists($file_data['full_path'])) {
            $new_filename = time() . $file_data['file_ext'];
            rename($file_data['full_path'], $file_data['file_path'] . $new_filename);
            $data[$field] = $this->config->item("upload_file_url") . 'users/' . $new_filename;
            $data["updated_at"] = date("Y-m-d h:i:s");
            $this->user_model->profile_img($data, $user);
            if ($old_img) {
                $basename = basename(urldecode($old_img));
                unlink($this->config->item("upload_file_path") . 'users/' . $basename);
            }
        } else {
            echo 'falied';
        }
    }

    public function del_image() {
        $img = $this->input->post('old_img');
        $id = $this->input->post('id');
        $field = $this->input->post('field');
        $data[$field] = '';
        $data["updated_at"] = date("Y-m-d h:i:s");
        $this->user_model->profile_img($data, $id);
        if ($img) {
            $basename = basename(urldecode($img));
            unlink($this->config->item("upload_file_path") . 'users/' . $basename);
        }
        exit;
    }

    public function export() {
        try {
            if(!$this->master_model->checkAccess('export', USERS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            $start_date = $this->session->userdata('startDate') . " 00:00:00";
            $end_date = $this->session->userdata('endDate') . " 23:59:59";
            $this->user_model->export($start_date, $end_date, $this->session->userdata("user_search"));
            exit;
        } catch (Exception $e) {
            
        }
    }

    public function users_imageview() {
        if(!$this->master_model->checkAccess('view', USERS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            return FALSE;
        }
        $this->ajax_imageview();
        $this->gen_contents['p_title'] = 'Users Image View';
        $this->gen_contents['ci_view'] = 'admin/user/imageview';
        $this->gen_contents['add_link'] = base_url() . 'admin/user/create_user';
        $this->gen_contents['export_link'] = base_url() . 'admin/user/export';
        $this->gen_contents['current_controller'] = "user";
        $breadCrumbs = array( 'admin/user'=>'Users');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        $this->template->write_view('content', 'admin/listing', $this->gen_contents);
        $this->template->render();
    }

    public function ajax_imageview() {


        $headers = apache_request_headers();
        $is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');
        $this->load->library('pagination');

        $config['base_url'] = base_url() . 'admin/user/ajax_imageview';
        $perPage = $this->session->userdata('per_page');
        if ('' != $this->input->post('per_page')) {
            $config['per_page'] = $this->input->post('per_page');            
            $perPage ='';
        }else {
            $config['per_page'] = 10;
        }

        if($perPage==''):
            $this->session->set_userdata(array('per_page'=>$config['per_page']));
        else:
            $config['per_page'] = $this->session->userdata('per_page');
        endif;  
        
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

        $config['total_rows'] = $this->user_model->getAllUsers($arr_where, $arr_sort, 'count', $config['per_page'], $offset, $arr_search);
        $this->gen_contents['total_count'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $this->gen_contents['paginate'] = $this->pagination->create_links_ajax();
        $this->gen_contents['data_url'] = $config['base_url'];
        $this->gen_contents['users'] = $this->user_model->getAllUsers($arr_where, $arr_sort, 'list', $config['per_page'], $offset, $arr_search);

        if ($is_ajax) {
            $this->load->view('admin/user/imageview', $this->gen_contents);
        }
    }

}