<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    var $email = "";

    /**
     * Admin password
     *
     * @var string
     */
    var $password = "";
    var $cpassword = "";
    var $gen_contents = array();

    public function __construct() {

        parent::__construct();
         $this->gen_contents['current_controller'] = $this->router->fetch_class();
        $this->merror['error'] = '';
        $this->msuccess['msg'] = '';
        $this->load->model(array('admin/admin_model','admin/user_model',
            'admin/hosting_model','admin/chat_model','master_model'));
        $this->gen_contents['title'] = '';
        /*
          if( ($this->uri->segment(2) == "" || $this->uri->segment(2) === "user") && ($this->uri->segment(3) == "" || $this->uri->segment(3) == "change_password" || $this->uri->segment(3) == "edit" || $this->uri->segment(3) == "forgot_password")){
          force_ssl();
          }else{
          remove_ssl();
          } */
    }

    public function index() {
        $this->mcontents = array();
		($this->authentication->check_logged_in("admin")) ? redirect('admin/home/dashboard') : ''; 
        if (!empty($_POST)) {

            $this->load->library('form_validation');
            $this->_init_adminlogin_validation_rules(); //server side validation of input values
            if ($this->form_validation->run() == FALSE) {// form validation
                sf('error_message', 'Invalid Username or Password');
                redirect("admin");
            } else {
                $this->_init_adminlogin_details();
                $login_details['email'] = $this->email;
                $login_details['password'] = $this->password;
				$login_status =  $this->authentication->process_admin_login($login_details);
                if ($login_status == 'success') {
                    redirect("admin/home/dashboard");
                } else if ($login_status == 'deleted') {
                    sf('error_message', 'Your account has been deleted. Please contact Party Host Admin');
                    redirect("admin");
                } else {
                    sf('error_message', 'The email or password you entered is incorrect.');
                    redirect("admin");
                }
            }
        }

        // define form attributes
        $data = array(
            'id' => 'login_form',
            'method' => 'post'
        );

        $this->gen_contents['attributes'] = $data;
        $this->gen_contents['title'] = $this->gen_contents['title'] . " - Login";


        $this->load->view('admin/login');
    }
    
        
    public function profile(){
        
        
        //if(isset($this->session->userdata('ADMIN_USERID'))){
        $this->gen_contents['admin_details'] = $this->master_model->getAdminDet($this->session->userdata('ADMIN_USERID'));
        $this->template->write_view('content', 'admin/profile', $this->gen_contents);
        $this->template->render();
        //}    
        //else{
           // redirect("admin/home");
        //}
    }

    /**
     * validating the form elemnets
     */
    function _init_adminlogin_validation_rules() {
        $this->form_validation->set_rules('email', 'username', 'required|max_length[50]');
        $this->form_validation->set_rules('password', 'password', 'required|max_length[20]');
    }

    function _init_adminlogin_details() {
        $this->email = $this->common_model->safe_html($this->input->post("email"));
        $this->password = $this->common_model->safe_html($this->input->post("password"));
    }

    public function dashboard() {

        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';  
		$this->config->set_item('site_title', 'Party Host - Administrator DashBoard');      
        $this->gen_contents['user_count'] = $this->user_model->getUserCounts();
        $this->gen_contents['hosting_count'] = $this->hosting_model->getHostingCounts();
        $this->gen_contents['chats_count'] = $this->chat_model->getChatCounts();
		
        $this->template->write_view('content', 'admin/dashboard', $this->gen_contents);
        $this->template->render();
    }

    function logout() {
        $this->authentication->admin_logout();
        redirect('admin');
    }

    function dateRange() {

        $data = array(
            'selected'=> $this->input->post('selected'),
            'startDate' => date("Y-m-d",strtotime($this->input->post('startDate'))),
            'endDate' => date("Y-m-d",strtotime($this->input->post('endDate')))
        );

        $this->session->set_userdata($data);

        echo $this->session->userdata('endDate');
        exit;
    }
    
	function check_email_validate(){
		$email = $this->input->post('fp_mail');       
        $email_exists = $this->common_model->getDataExists(array("email"), "admin_users", array("email" => $email));
        if (empty($email_exists)) {
            echo "false";
        } else {
            echo "true";
        }

        exit;
	}
    function forget_password() {
    	$email = $this->input->post('fp_mail');       
        $email_exists = $this->common_model->getDataExists(array("email"), "admin_users", array("email" => $email));
        if (empty($email_exists)) {
            echo "false";
        } else {
            $to_email =$email;			
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
     
			$password =substr(str_shuffle($chars),6,6);
	        $from = $this->config->item('smtp_from_name');
	        $subject = "Reset password";
	        $body_content = "Dear Admin,<br /><br />";
	        $body_content .= "Your Partyhost password has been changed.<br />";
	        $body_content .= "New password :".$password."<br /><br />";
	        $body_content .= "Partyhost";
	        if($this->common_model->send_mail($to_email, $from, $subject, $body_content)) {
	        
	            $this->user_model->update_password($reset['password'],$email_exists->email);
				echo "true";
	        } else {
	            echo "false";
	        }
        }
        exit;
    }
    
    function reset_password() {
        $this->mcontents = array();

        if (!empty($_POST)) {

            $this->load->library('form_validation');
            $this->_init_adminreset_validation_rules(); //server side validation of input values
            if ($this->form_validation->run() == FALSE) {// form validation
                sf('error_message', 'Invalid Username or Password');
                redirect("admin");
            } else {
                $this->_init_adminreset_details();
                $reset['password'] = $this->password;
                $reset['cpassword'] = $this->cpassword;
                $email_exists = $this->common_model->getDataExists(array("email"), "admin_users", array("email" => $this->email));
                if(empty($email_exists)) {
                    sf('error_message', 'Email not exists');
                    redirect("admin/home");
                }
                else if ($reset['password'] == $reset['cpassword']) {
                    $this->user_model->update_password($reset['password'],$email_exists->email);
                    sf('success_message', 'Password reset successfull');
                    redirect("admin/home");
                } else {
                    sf('error_message', 'Password mismatching');
                    redirect("admin");
                }
            }
        }

        // define form attributes
        $data = array(
            'id' => 'login_form',
            'method' => 'post'
        );

        $this->gen_contents['attributes'] = $data;
        $this->gen_contents['title'] = $this->gen_contents['title'] . " - Reset Password";


        $this->load->view('admin/reset_password');
    }
    
    function _init_adminreset_validation_rules() {
        $this->form_validation->set_rules('password', 'password', 'required|max_length[50]');
        $this->form_validation->set_rules('cpassword', 'cpassword', 'required|max_length[20]');
    }

    function _init_adminreset_details() {
        $this->email = $this->common_model->safe_html($this->input->post("email"));
        $this->password = $this->common_model->safe_html($this->input->post("password"));
        $this->cpassword = $this->common_model->safe_html($this->input->post("cpassword"));
    }


}
