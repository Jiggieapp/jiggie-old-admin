<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hostings extends CI_Controller {
    
    var $gen_contents	=	array();
    
    public function __construct()
    {
        parent::__construct();
        $this->merror['error']	= '';
        $this->msuccess['msg']	= '';
        $this->load->model(array('admin/admin_model','common_model','admin/hosting_model', 'admin/permission_model', 'master_model'));
        $this->gen_contents['title']	=	'';
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
        
        $this->access_userid = $this->session->userdata("ADMIN_USERID");
        $this->access_usertypeid = $this->session->userdata("USER_TYPE_ID");
        $this->access_permissions = $this->permission_model->get_all_permission();
    }
    
    public function index()
    {
    	if(!$this->master_model->checkAccess('view', HOSTINGS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            //echo $this->db->last_query();exit;
            return FALSE;
        }
        else {
            $this->hosting();
        }
        // $seleced_fields = array("name", "hostings.description as description", "time", "hostings.created_at as created_at");
        // $start_date = $this->session->userdata('startDate')." 00:00:00";
        // $end_date = $this->session->userdata('endDate'). " 23:59:59";
        // $where = array("hostings.created_at >="=>"$start_date","hostings.created_at <="=>"$end_date");
        // $hostings = $this->common_model->getDataExistsArray($seleced_fields, "hostings", $where, "", "venue_id", "", "venues", "", "id","venue_id");
//        
        // $this->gen_contents['hostings'] = $hostings;         
		// $this->template->write_view('content', 'admin/hostings/listing',$this->gen_contents);
		// $this->template->render();
    }
	
	public function hosting(){
            $this->ajax_list();
            $this->gen_contents['p_title']= 'Hosting listing';
            $this->gen_contents['ci_view']= 'admin/hostings/listing';
            $this->gen_contents['add_link']= base_url().'admin/hostings/create';
            $this->gen_contents['export_link']= base_url().'admin/hostings/export';
            $this->gen_contents['current_controller'] = $this->router->fetch_class();
            $breadCrumbs = array( 'admin/hostings/hosting'=>'Hosting');
            $this->gen_contents['breadcrumbs'] = $breadCrumbs;
            $this->gen_contents["venues"] = $this->common_model->getDataExistsArray(array("id", "name"), "venues", "", "", "name ASC");
            $this->template->write_view('content', 'admin/listing',$this->gen_contents);
            $this->template->render();
	}
	
    public function ajax_list(){
		

		$headers = apache_request_headers();
		$is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');		
		$this->load->library('pagination');	
		
		$config['base_url'] = base_url().'admin/hostings/ajax_list';
		if('' != $this->input->post ('per_page')){
			$config['per_page']	= $this->input->post ('per_page');
		}else{
			$config['per_page'] =10;
		}
	 
	 
	 	if('' != $this->uri->segment(4)){
			$offset	= safeOffset(4);
		}else{
			$offset	= 0;
		}
		$this->mcontents['offset'] = $offset;
		$config['uri_segment']					= 4;
		$config['callbackfunction'] 			= 'loadListing';
		$arr_where								= array();
		
		$arr_sort								= array();
		if('' != $this->input->post ('sort_field')){
			$arr_sort['name']	= $this->input->post ('sort_field');
		}else{
			$arr_sort['name'] ='time';
		}
		if('' != $this->input->post ('sort_val')){
			$arr_sort['value']	= $this->input->post ('sort_val');
		}else{
			$arr_sort['value'] ='DESC';
		}
                
                //Search Factor
                $arr_search = array();
                if($this->input->post ('search_name') != "") {
                    $arr_search["search_name"]    = $this->input->post ('search_name');
                    $this->session->set_userdata("hosting_search", $arr_search["search_name"]);
                } else {
                    $arr_search["search_name"]    = "";
                    $this->session->set_userdata("hosting_search", "");
                }
                
                if($this->input->post ('search_venue') != "") {
                    $arr_search["search_venue"]    = $this->input->post ('search_venue');
                    $this->session->set_userdata("hosting_venue", $arr_search["search_venue"]);
                } else {
                    $arr_search["search_venue"]    = "";
                    $this->session->set_userdata("hosting_venue", "");
                }
                
                if($this->input->post ('search_promoter') != "") {
                    $arr_search["search_promoter"]    = $this->input->post ('search_promoter');
                    $this->session->set_userdata("hosting_promoter", $arr_search["search_promoter"]);
                } else {
                    $arr_search["search_promoter"]    = "";
                    $this->session->set_userdata("hosting_promoter", "");
                }
                
                if($this->input->post ('search_recurring') != "") {
                    $arr_search["search_recurring"]    = $this->input->post ('search_recurring');
                    $this->session->set_userdata("hosting_recurring", $arr_search["search_recurring"]);
                } else {
                    $arr_search["search_recurring"]    = "";
                    $this->session->set_userdata("hosting_recurring", "");
                }
                
                if($this->input->post ('search_verified') != "") {
                    $arr_search["search_verified"]    = $this->input->post ('search_verified');
                    $this->session->set_userdata("hosting_verified", $arr_search["search_verified"]);
                } else {
                    $arr_search["search_verified"]    = "";
                    $this->session->set_userdata("hosting_verified", "");
                }
                ///////////////////////
                
		$start_date = $this->session->userdata('startDate')." 00:00:00";
        $end_date = $this->session->userdata('endDate'). " 23:59:59";
        $arr_where = array("h.created_at >="=>"$start_date","h.created_at <="=>"$end_date", "hosting_status !=" => "10");
		
		$config['total_rows'] 					= $this->hosting_model->getAllHostings ($arr_where, $arr_sort, 'count', $config['per_page'], $offset, $arr_search);
		$this->gen_contents['total_count']			= $config['total_rows'];
		$this->pagination->initialize($config);
		$this->gen_contents['data_url']		= $config['base_url'];
		$this->gen_contents['paginate']		= $this->pagination->create_links_ajax();
		$this->gen_contents['hostings']		= $this->hosting_model->getAllHostings ($arr_where, $arr_sort,'list', $config['per_page'], $offset, $arr_search);
		#echo $this->db->last_query();exit;
                if($is_ajax){
			$this->load->view('admin/hostings/listing',$this->gen_contents); 
		}
			
	}
    public function create()
    {
        try 
        {
            if(!$this->master_model->checkAccess('create', HOSTINGS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            
            $this->gen_contents["venues"] = $this->common_model->getDataExistsArray(array("id", "name"), "venues", array("venue_status !=" => "10" ), "", "name ASC");
            if(!empty($_POST)) {
                $post_data = array();
                $post_data["user_id"]           = $this->hosting_model->search_user($this->input->post("name"));
                $this->_init_hosting_validation_rules(); 
                if ($this->form_validation->run() == FALSE) {// form validation
                   sf('error_message', 'Please enter name, venue, date, time');
                } elseif(empty($post_data["user_id"])) {
                    sf('error_message', 'Please select valid user');
                }
                else {
                   $post_data["venue_id"]          = $this->input->post("venue");
                    $post_data["time"]              = date("Y-m-d",strtotime($this->input->post("date")))." ".$this->input->post("time");
                    /*$is_venue_available = $this->hosting_model->getBookedVenue($post_data["venue_id"], $post_data["time"]);
                    
                    if($is_venue_available >= 0) { 
                        throw new Exception('Venue already booked for this time');
                    }*/
                    $user_details = $this->common_model->getDataExists(array("profile_image_url"), "users", array("id =" => $post_data["user_id"]));
                    $post_data["theme"]             = $this->input->post("theme");
                    $post_data["description"]       = $this->input->post("description");
                    $post_data["is_recurring"]      = $this->input->post("is_recurring");
                    $post_data["is_promoter"]       = $this->input->post("is_promoter");
                    $post_data["rank"]              = $this->input->post("rank");
                    $post_data["hosting_status"]    = $this->input->post("hstatus");
                    $post_data["user_image_url"]    = $user_details->profile_image_url;
                    $this->hosting_model->save_hosting($post_data);
                    sf('success_message', 'Hostings created successfully');
                    redirect("admin/hostings");
                }
            }
            $this->template->write_view('content', 'admin/hostings/create', $this->gen_contents);
            $this->template->render();
        }
        catch (Exception $e) 
        { 
            $this->gen_contents["error"] = $e->getMessage();
            $this->template->write_view('content', 'admin/hostings/create',$this->gen_contents);
            $this->template->render();
        }
    }
    
    function _init_hosting_validation_rules ()
    {
       $this->form_validation->set_rules('name', 'name', 'required');
       $this->form_validation->set_rules('venue', 'venue', 'required');
       $this->form_validation->set_rules('date', 'date', 'required');
       $this->form_validation->set_rules('time', 'time', 'required');
    }
    
    public function search($search)
    {
        $datas = $this->hosting_model->search($search);
        $array = array();
        foreach($datas as $data) {
            $array[] = $data['email'];
        }
        echo json_encode ($array); //Return the JSON Array
        exit;
    }
    
    public function details($hosting_id = "")
    {
        $breadCrumbs = array( 'admin/hostings/hosting'=>'Hosting', 'admin/hostings/details/'.$hosting_id=>'Hosting Details');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
            
        try 
        {
            if(!$this->master_model->checkAccess('update', HOSTINGS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            if(empty($hosting_id)) 
            {
                throw new Exception("Hosting Id should not be empty");
            }
            
            $this->gen_contents['hosting_id'] = $hosting_id;
            $hosting = $this->hosting_model->getHostings($hosting_id);
            if(empty($hosting)) 
            { 
                throw new Exception("Hosting details not found");
            }
            $this->gen_contents["hosting"] = $hosting;
            $this->gen_contents["hosting_chat"] = $this->hosting_model->getChatsByHosting( $hosting_id);
            $this->gen_contents["venue"] = $this->common_model->getDataExists(array("name","id"),"venues", array("id" => $hosting->venue_id));
            $this->gen_contents["user"] = $this->common_model->getDataExists(array("id","CONCAT_WS(' ', first_name, last_name) as name,email"),"users", array("id" => $hosting->user_id));
            $this->gen_contents["venue_all"] = $this->common_model->getDataExistsArray(array("id","name"),"venues");
            $this->gen_contents["users_all"] = $this->common_model->getDataExistsArray(array("id","CONCAT_WS(' ', first_name, last_name) as name"),"users",array("first_name !="=>"' '"), "", "", "", "", "", "", "",1);
            $this->template->write_view('content', 'admin/hostings/detail',$this->gen_contents);
            $this->template->render();
        } 
        catch (Exception $e) 
        { 
            $this->gen_contents["error"] = $e->getMessage();
            $this->template->write_view('content', 'admin/hostings/detail',$this->gen_contents);
            $this->template->render();
        }
    }
    
      
    public function save($hosting="") {
        try {
            if(!$this->master_model->checkAccess('update', HOSTINGS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            $data = $this->input->post('value');
            $label = $this->input->post('name');
            $updated = $this->hosting_model->updateHosting($label, $data, $hosting);
            echo $updated;
        } catch (Exception $e) {
            
        }
        exit;
    }
    public function delete_hosting($hosting_id) {
        try
        {
            if(!$this->master_model->checkAccess('delete', HOSTINGS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            if($this->hosting_model->delete_hosting($hosting_id)) {
                sf('success_message', 'Hosting deleted successfully');
                redirect("admin/hostings");
            } else {
                sf('error_message', 'Please try again');
                redirect("admin/hostings");
            }
        }
        catch (Exception $e) 
        {
            sf('error_message', 'Please try again');
            redirect("admin/hostings");
        }
    }
    
    public function duplicate_hosting($hosting_id="") {
        try 
        {
            (!$this->authentication->check_logged_in("admin")) ? redirect('admin') :'' ;
            $this->gen_contents["venues"] = $this->common_model->getDataExistsArray(array("id", "name"), "venues", "", "", "name ASC");
            if(!empty($_POST)) {
                $post_data = array();
                $post_data["user_id"]           = $this->hosting_model->search_user($this->input->post("name"));
                $this->_init_hosting_validation_rules(); 
                if ($this->form_validation->run() == FALSE) {// form validation
                   sf('error_message', 'Please enter name, venue, date, time');
                } elseif(empty($post_data["user_id"])) {
                    sf('error_message', 'Please select valid user');
                }
                else {
                   $post_data["venue_id"]          = $this->input->post("venue");
                    $post_data["time"]              = date("Y-m-d",strtotime($this->input->post("date")))." ".$this->input->post("time");
                    /*$is_venue_available = $this->hosting_model->getBookedVenue($post_data["venue_id"], $post_data["time"]);
                    
                    if($is_venue_available >= 0) { 
                        throw new Exception('Venue already booked for this time');
                    }*/
                    $user_details = $this->common_model->getDataExists(array("profile_image_url"), "users", array("id =" => $post_data["user_id"]));
                    $post_data["theme"]             = $this->input->post("theme");
                    $post_data["description"]       = $this->input->post("description");
                    $post_data["is_recurring"]      = $this->input->post("is_recurring");
                    $post_data["is_promoter"]       = $this->input->post("promoter");
                    $post_data["rank"]              = $this->input->post("rank");
                    $post_data["hosting_status"]    = $this->input->post("hstatus");
                    $post_data["user_image_url"]    = $user_details->profile_image_url;
                    $this->hosting_model->save_hosting($post_data);
                    sf('success_message', 'Hostings created successfully');
                    redirect("admin/hostings");
                }
            }
            $selected_fields = array("venue_id", "theme", "description", "is_recurring", "is_promoter", "rank", "user_image_url", "hosting_status");
            $this->gen_contents["hostings"] = $this->common_model->getDataExists($selected_fields, "hostings" ,array("id" => $hosting_id));
            $this->template->write_view('content', 'admin/hostings/duplicate', $this->gen_contents);
            $this->template->render();
        }
        catch (Exception $e) 
        { 
            $this->gen_contents["error"] = $e->getMessage();
            $this->template->write_view('content', 'admin/hostings/duplicate',$this->gen_contents);
            $this->template->render();
        }
    }
    
    public function export() {
        try {
            if(!$this->master_model->checkAccess('export', HOSTINGS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            $start_date = $this->session->userdata('startDate') . " 00:00:00";
            $end_date = $this->session->userdata('endDate') . " 23:59:59";
            $this->hosting_model->export($start_date, $end_date, $this->session->userdata("hosting_search"), $this->session->userdata("hosting_venue"), $this->session->userdata("hosting_promoter"), $this->session->userdata("hosting_recurring"));
            exit;
        } catch (Exception $e) {
            
        }
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
}