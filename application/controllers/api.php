<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api extends CI_Controller {

    var $request_headers = array();

    /**
     * General contents
     *
     * @var Array
     */
    var $request_array = array();

    /**
     * Failed status text
     * 
     * @var string 
     */
    var $failed_string = 'failed';

    /**
     * success status string
     * 
     * @var stringconcert     */
   
    var $success_string = 'success';
	var $skey = 'dtrecnocZ9ydHhda98hd98n8hoONsdbffZjnLmadanA';
    
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->helper(array('url', 'form','security'));
        $this->load->model(array("common_model"));
        //get the request headers and request body
       
        $this->request_headers = $this->input->request_headers();
        $inputJSON = file_get_contents('php://input');		 
        $this->request_array = json_decode($inputJSON,true);
		
    }


	function chatcount() {
        $_POST = $this->request_array ;		
		
        try{
            $data = array();
            $input_fields = array("key", "id","count");
           
            $this->checkInputFields($input_fields, $_POST);

            $authKey = $_POST["key"];
            $id = $_POST["id"];
			$count = $_POST["count"];
            $this->form_validation->set_rules('key', 'key', 'required');
            $this->form_validation->set_rules('id', 'id', 'required');
			$this->form_validation->set_rules('count', 'count', 'required|number');
			if ($authKey !=$this->skey) {
			    $this->outputJSON($this->failed_string, "Sorry, unexpected error3");
            	return;
			}
            if ($this->form_validation->run()) {
                $userdata = $this->common_model->getDataExists(array("id","fb_id"), "users", array("fb_id" => $id));      
                if(is_object($userdata)) {              
						
						$this->db->update("users", array("cnt_chat" =>(int)$count), array("fb_id" => $id));
                        $message = "Count updated";
                        $this->outputJSON( $this->success_string, $message);
                   
                } else {
                    $message = "User not found";
                    $this->outputJSON( $this->failed_string, $message);
                }
            } else {
                $message = "Sorry, unexpected error1";
                $this->throwError($this->form_validation->error_array(), $message);
            }
        } catch (Exception $e) { 
            $this->outputJSON($this->failed_string, $e->getMessage());
            return;
        }
    }
	
    
	
	
	function outputJSON($status, $message) {
        $data_response = array();
        $data_response['status']    = $status;
        $data_response['message']   = $message;
        
        echo json_encode($data_response);        exit;
    }
    
    
	function checkInputFields($input_fields = array(), $post_data = array()) {
        if(empty($input_fields) || empty($post_data)) {
           throw new Exception("Sorry, input data missing");
        }
        $check_input_fields = count(array_diff($input_fields, array_keys($post_data)));
        $input_fields_defined = count($input_fields);
        $input_fields_posted = count($post_data);
        
        if($check_input_fields != 0 || ($input_fields_defined != $input_fields_posted)) {
            throw new Exception("Sorry, unexpected error occured");
        }
    }
	
    function throwError($error= array(), $data = array(), $custom_error = '') {
        $error_validation = '';
        if(!empty($error))
        $error_validation = reset($error);
        if(!empty($error_validation)) {
            $error_string = $error_validation;
        } elseif($custom_error) { 
            $error_string = $custom_error;
        }
        else {
            $error_string = "Sorry, unexpected error occured";
        }
        $data_response['status'] = $this->failed_string;
        $data_response['message'] = $error_string;
        $data_response['data'] = $data;
        $this->bookingapp_model->response($data_response);
        return;
    }
	
	function update_loginlog(){
		 
		$query	= $this->db->query("Select id , login_last_dt from users order by created_at desc");
        foreach ($query->result_array() as $count){
            $qryCheck	= 	$this->db->query("SELECT id FROM login_logs where  user_id = '" . $count['id'] . "'");       
	        	$resCheck 	= 	$qryCheck->row_array();
				if(!$resCheck){
			$data['user_id'] = $count['id'];
			$data['time'] = $count['login_last_dt'];
			$this->db->set($data);
            $this->db->insert('login_logs');}
        }
	}
    
}   
