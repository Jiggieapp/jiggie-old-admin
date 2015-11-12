<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Venue extends CI_Controller {
    
    var $gen_contents	=	array();
    
    public function __construct()
    {
        parent::__construct();
        $this->merror['error']	= '';
        $this->msuccess['msg']	= '';
        $this->load->model(array('admin/venue_model','common_model','admin/permission_model','master_model'));
        $this->gen_contents['title']	=	'';
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
        
        $this->access_userid = $this->session->userdata("ADMIN_USERID");
        $this->access_usertypeid = $this->session->userdata("USER_TYPE_ID");
        $this->access_permissions = $this->permission_model->get_all_permission();
    }
    
    public function index()
    {
    	if(!$this->master_model->checkAccess('view', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            return FALSE;
        }
        else {	
            $this->venues();
        }
    }
	
	public function venues(){

            $this->ajax_list();
            $this->gen_contents['p_title']= 'Venue listing';
            $this->gen_contents['ci_view']= 'admin/venue/list';
            $this->gen_contents['add_link']= base_url().'admin/venue/create_venue';
            $this->gen_contents['export_link']= base_url().'admin/venue/export';
            $this->gen_contents['current_controller'] = "";
            $breadCrumbs = array( 'admin/venue/venues'=>'Venues');
            $this->gen_contents['breadcrumbs'] = $breadCrumbs;
            $this->template->write_view('content', 'admin/listing',$this->gen_contents);
            $this->template->render();

	}
	
	public function ajax_list(){
		

		$headers = apache_request_headers();
		$is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');		
		$this->load->library('pagination');	
		
		$config['base_url'] = base_url().'admin/venue/ajax_list';
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
			$arr_sort['name'] ='name';
		}
		if('' != $this->input->post ('sort_val')){
			$arr_sort['value']	= $this->input->post ('sort_val');
		}else{
			$arr_sort['value'] ='DESC';
		}
                
                //Search Factor
                $arr_search = array();
                if($this->input->post ('search_name') != "") {
                    $arr_search["where"]    = $this->input->post ('search_name');
                    $this->session->set_userdata("venue_search",$arr_search["where"]);
                } else {
                    $arr_search["where"]    = "";
                    $this->session->set_userdata("venue_search","");
                }
                
		$start_date = $this->session->userdata('startDate')." 00:00:00";
                $end_date = $this->session->userdata('endDate'). " 23:59:59";
                $arr_where = array("created_at >="=>"$start_date","created_at <="=>"$end_date", "venue_status !=" => "10");
		
		$config['total_rows'] 					= $this->venue_model->getAllVenues ($arr_where, $arr_sort, 'count', $config['per_page'], $offset, $arr_search);
		$this->gen_contents['total_count']			= $config['total_rows'];
		$this->pagination->initialize($config);
		$this->gen_contents['data_url']		= $config['base_url'];
		$this->gen_contents['paginate']		= $this->pagination->create_links_ajax();
		$this->gen_contents['venues']		= $this->venue_model->getAllVenues ($arr_where, $arr_sort,'list', $config['per_page'], $offset, $arr_search);
		
                if($is_ajax){
			$this->load->view('admin/venue/list',$this->gen_contents); 
		}
			
	}
        
     public function venue_details($venue_id = "")
     {
        $breadCrumbs = array( 'admin/venue/venues'=>'Venues','admin/venue/venue_details/'.$venue_id=>'Venue Details');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs; 
        try 
        {
            if(!$this->master_model->checkAccess('update', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            
            if(empty($venue_id)) 
            {
                throw new Exception("Venue Id should not be empty");
            }
            $seleced_fields = array("name", "address", "neighborhood","venues.rank", "cross_street", "city", "state", "zip", "venues.country", "phone", "lat", "lng", "venues.url as venue_url", "description", "img_title", "img_1","ref_venue_city.venue_city_id","venue_city_name","venue_status");
            $this->gen_contents["venue_id"] = $venue_id;
            $join = array("table"   => "ref_venue_city",
                          "field1"  => "venue_city_id",
                          "table2"  => "venues",
                          "field2"  => "city",
                          "join_type"   => "left"
                        );
            $venue = $this->common_model->getDataExists($seleced_fields, "venues", array("venues.id" => $venue_id),"","","","","","","",$join);
            
            if(empty($venue)) 
            {
                throw new Exception("Venue details not found");
            }
            $this->gen_contents["data"] = $venue;
            $this->gen_contents["cities"] = $this->common_model->getDataExistsArray(array("venue_city_id", "venue_city_name"), "ref_venue_city", array("venue_city_status"=>1), "", "venue_city_name ASC");
            $this->template->write_view('content', 'admin/venue/detail',$this->gen_contents);
            $this->template->render();
        } 
        catch (Exception $e) 
        { 
            $this->gen_contents["error"] = $e->getMessage();
            $this->template->write_view('content', 'admin/venue/detail',$this->gen_contents);
            $this->template->render();
        }
    }
	public function image($venue_id) {
        try 
        {
            if(!$this->master_model->checkAccess('update', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            
            if(empty($venue_id)) 
            {
                throw new Exception("User Id should not be empty");
            }
            $seleced_fields = array("img_1", "img_2", "img_3", "img_4", "img_5");
            $this->gen_contents['venue_id'] = $venue_id;
            $venue  = $this->common_model->getDataExists($seleced_fields, "venues", array("id" => $venue_id));
            if(empty($venue)) 
            {
                throw new Exception("Venue details not found");
            }
            $this->gen_contents["venue"] = $venue;
            
            $this->template->write_view('content', 'admin/venue/image', $this->gen_contents);
            $this->template->render();
        }
        catch (Exception $e) 
        {
            
        }
    }     
	
	public function profile_image($upload_file, $field,$venue){		 
		 $old_img= $this->input->get('img', TRUE);
		 if($old_img){
		 	$basename=  basename(urldecode($old_img));
		 	
		 }
		  
         $config['upload_path'] = $this->config->item("upload_file_path").'/venues';
         $config['allowed_types'] = 'gif|jpg|png|jpeg';
         $config['max_size']    = 1 * 1024 * 1024;
         $config['remove_spaces']  = TRUE;
         $config['overwrite'] = true;
         $this->load->library('upload', $config);
		 $this->upload->do_upload($upload_file);
		 $file_data = $this->upload->data();
		   if(file_exists($file_data['full_path']))
        {
        	$new_filename =  time().$file_data['file_ext'];
             rename($file_data['full_path'],  $file_data['file_path'].$new_filename);  
			$data[$field] =$this->config->item("upload_file_url").'/venues/'.$new_filename;	
			$data["updated_at"] = date("Y-m-d h:i:s");		
			 $this->venue_model->profile_img($data, $venue); 
			 if($old_img){
			 	$basename=  basename(urldecode($old_img));
			 	unlink($this->config->item("upload_file_path").'/venues/'.$basename);
			 } 
			
        }else{
            echo 'falied';
        }
	 
	} 

	public function del_image(){
                if(!$this->master_model->checkAccess('delete', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                    show_error("You don't have access to whatever you are trying to view", $status_code = 500);
                }
		$img =  $this->input->post('old_img');
		$id =  $this->input->post('id');
		$field = $this->input->post('field');
		$data[$field] ='';	
		$data["updated_at"] = date("Y-m-d h:i:s");
		$this->venue_model->profile_img($data, $id); 
		 if($img){
		 	$basename=  basename(urldecode($img));
		 	unlink($this->config->item("upload_file_path").'/venues/'.$basename);
		 } 
		 exit;		
	}
    public function save( $venue="") {
        try {
            if(!$this->master_model->checkAccess('update', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                    show_error("You don't have access to whatever you are trying to view", $status_code = 500);
            }
            $data =  $this->input->post('value');
            $label = $this->input->post('name');

            if ($label == "phone") {
                if (!$this->validate_phone($data)) {
                    throw new Exception("Invalid Phone Number");
                } else {
                    $this->venue_model->updateVenue($label, $data, $venue);
                }
            } else if ($label == "url") {
                if (!filter_var($data, FILTER_VALIDATE_URL)) {
                    throw new Exception("URL is not valid");
                }
                else{
                    $this->venue_model->updateVenue($label, $data, $venue);
                }
            } else {
                if ($this->venue_model->updateVenue($label, $data, $venue)) {
                    
                } else {
                    echo "Updation failed";
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        exit;
    }
    
    

    function validate_phone( $phone_number ) {
    
        $formats = array(
        //"/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", // [1-]555-555-5555
        //"/^([1]-)?[0-9]{3}.[0-9]{3}.[0-9]{4}$/i", // [1-]555.555.5555
        //"/^([1]-)?\([0-9]{3}\)-[0-9]{3}-[0-9]{4}$/i", // [1-](555)-555-5555
        "/^[0-9]{10}$/i", // 5555555555
        );
    
        foreach( $formats as $format ) {
            // Loop through formats, if a match is found return true
            if( preg_match( $format, $phone_number ) ) return true;
        }
        return false; // If no formats match
    }
    public function delete_venue($venue_id) {
        try
        {
            if(!$this->master_model->checkAccess('delete', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                    return FALSE;
            }
            if($this->venue_model->delete_venue($venue_id)) {
                sf('success_message', 'Venue deleted successfully');
                redirect("admin/venue");
            } else {
                sf('error_message', 'Please try again');
                redirect("admin/venue");
            }
        }
        catch (Exception $e) 
        {
            sf('error_message', 'Please try again');
            redirect("admin/venue");
        }
    }
    
    public function create_venue() {
        try 
        {
            if(!$this->master_model->checkAccess('create', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                    return FALSE;
            }
            
            $this->mcontents = array();
            $this->gen_contents["cities"] = $this->common_model->getDataExistsArray(array("venue_city_id", "venue_city_name"), "ref_venue_city", array("venue_city_status"=>1), "", "venue_city_name ASC");
            if(!empty($_POST)) {
                $this->_init_venue_validation_rules(); 
                if ($this->form_validation->run() == FALSE) {// form validation
                   sf('error_message', 'Invalid Email');
                } 
                else { 
                    $post_data = array();
                    $post_data["name"]              = $this->input->post("name");
                    $post_data["address"]           = $this->input->post("address");
                    $post_data["neighborhood"]      = $this->input->post("neighborhood");
                    $post_data["cross_street"]      = $this->input->post("cross_street");
                    $post_data["city"]              = $this->input->post("city");
                    $post_data["state"]             = $this->input->post("state");
                    $post_data["zip"]               = $this->input->post("zip");
                    $post_data["country"]           = $this->input->post("country");
                    $post_data["phone"]             = $this->input->post("phone");
                    $post_data["lat"]               = $this->input->post("lat");
                    $post_data["lng"]               = $this->input->post("lng");
                    $post_data["url"]               = $this->input->post("url");
                    $post_data["description"]       = $this->input->post("description");
                    $post_data["venue_status"]      = $this->input->post("venue_status");
                    $post_data["grade"]             = $this->input->post("grade");
                    //$venue_image                  = $this->input->post("profile_image");
                    $venue_image ='';
                    $config =  array(
                      'upload_path'     => $this->config->item("upload_file_path") ."/venues/",
                      'upload_url'      => base_url()."uploads/venues/",
                      'allowed_types'   => "gif|jpg|png|jpeg",
                      'overwrite'       => FALSE,
                      'max_size'        => "10000",

                    );
                    $this->load->library('upload', $config);
                    
                    if ($this->upload->do_upload('image1')) {

                        $data = array('upload_data' => $this->upload->data());

                        $venue_image = $config['upload_url'].$data['upload_data']['file_name'].','; 
                        
                    }
                    
                    if ($this->upload->do_upload('image2')) {

                        $data = array('upload_data' => $this->upload->data());

                        $venue_image .= $config['upload_url'].$data['upload_data']['file_name'].','; 
                        
                    }
                    
                    if ($this->upload->do_upload('image3')) {

                        $data = array('upload_data' => $this->upload->data());

                        $venue_image .= $config['upload_url'].$data['upload_data']['file_name'].','; 
                        
                    }
                    
                    if ($this->upload->do_upload('image4')) {

                        $data = array('upload_data' => $this->upload->data());

                        $venue_image .= $config['upload_url'].$data['upload_data']['file_name'].','; 
                        
                    }
                                        
                    if ($this->upload->do_upload('image5')) {

                        $data = array('upload_data' => $this->upload->data());

                        $venue_image .= $config['upload_url'].$data['upload_data']['file_name']; 
                        
                    }
                    
                    $post_data["grade"]             = $this->input->post("grade");

                    $this->venue_model->save_venue($post_data, $venue_image);
                    sf('success_message', 'Venue created successfully');
                    redirect("admin/venue");
                }
            } else {
                
            }
            
            $this->template->write_view('content', 'admin/venue/create_venue', $this->gen_contents);
            $this->template->render();
        }
        catch (Exception $e) 
        {
            
        }
    }
    
    function _init_venue_validation_rules ()
    {
       $this->form_validation->set_rules('name', 'name', 'required');
       
    }
    
    function check_venue() {
         $name = $this->input->post('name');
         
         $name_exists = $this->common_model->getDataExists(array("name"), "venues",array("name"=>$name));
         if(empty($name_exists)) {
             echo json_encode(array("status"=>1));
         } else {
             echo json_encode(array("status"=>0));
         }
         
         exit;
    }
    
    public function upload()
    {
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') :'' ;		
        $config['upload_path'] = $this->config->item("upload_file_path").'/venues';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']    = 1 * 1024 * 1024;
        $config['remove_spaces']  = TRUE;
        $config['overwrite'] = true;
        
        //echo json_encode($this->user_model->handle_upload("qqfile",$config));
        $this->load->library("qqfileuploader");
        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array("jpeg", "gif", "jpg", "png");
        // max file size in bytes
        $sizeLimit = 10 * 1024 * 1024;


        

        // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
        $result = $this->qqfileuploader->handleUpload('uploads/venues/');

        // to pass data through iframe you will need to encode all html tags
        print (json_encode($result));
        //echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        exit;
    }
    
    public function export() {
        try {
            if(!$this->master_model->checkAccess('export', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            $start_date = $this->session->userdata('startDate')." 00:00:00";
            $end_date = $this->session->userdata('endDate'). " 23:59:59";
            $this->venue_model->export($start_date, $end_date, $this->session->userdata("venue_search"));
            exit;
        } catch (Exception $e) {
            
        }
    }
    
    public function duplicate_venue($venue_id) {
        try 
        {
            (!$this->authentication->check_logged_in("admin")) ? redirect('admin') :'' ;
            
            $this->mcontents = array();
            $this->gen_contents["cities"] = $this->common_model->getDataExistsArray(array("venue_city_id", "venue_city_name"), "ref_venue_city", array("venue_city_status"=>1), "", "venue_city_name ASC");
            if(!empty($_POST)) {
                $this->_init_venue_validation_rules(); 
                if ($this->form_validation->run() == FALSE) {// form validation
                   sf('error_message', 'Invalid Email');
                } 
                else { 
                    $post_data = array();
                    $post_data["name"]              = $this->input->post("name");
                    $post_data["address"]           = $this->input->post("address");
                    $post_data["neighborhood"]      = $this->input->post("neighborhood");
                    $post_data["cross_street"]      = $this->input->post("cross_street");
                    $post_data["city"]              = $this->input->post("city");
                    $post_data["state"]             = $this->input->post("state");
                    $post_data["zip"]               = $this->input->post("zip");
                    $post_data["country"]           = $this->input->post("country");
                    $post_data["phone"]             = $this->input->post("phone");
                    $post_data["lat"]               = $this->input->post("lat");
                    $post_data["lng"]               = $this->input->post("lng");
                    $post_data["url"]               = $this->input->post("url");
                    $post_data["description"]       = $this->input->post("description");
                    $post_data["venue_status"]      = $this->input->post("venue_status");
                    //$venue_image                  = $this->input->post("profile_image");
                    $post_data["grade"]             = $this->input->post("grade");
                    
                    $venue_image ='';
                    $config =  array(
                      'upload_path'     => $this->config->item("upload_file_path") ."/venues/",
                      'upload_url'      => base_url()."uploads/venues/",
                      'allowed_types'   => "gif|jpg|png|jpeg",
                      'overwrite'       => FALSE,
                      'max_size'        => "10000",

                    );
                    $this->load->library('upload', $config);
                    
                    if ($this->upload->do_upload('image1')) {

                        $data = array('upload_data' => $this->upload->data());

                        $venue_image = $config['upload_url'].$data['upload_data']['file_name'].','; 
                        
                    }
                    
                    if ($this->upload->do_upload('image2')) {

                        $data = array('upload_data' => $this->upload->data());

                        $venue_image .= $config['upload_url'].$data['upload_data']['file_name'].','; 
                        
                    }
                    
                    if ($this->upload->do_upload('image3')) {

                        $data = array('upload_data' => $this->upload->data());

                        $venue_image .= $config['upload_url'].$data['upload_data']['file_name'].','; 
                        
                    }
                    
                    if ($this->upload->do_upload('image4')) {

                        $data = array('upload_data' => $this->upload->data());

                        $venue_image .= $config['upload_url'].$data['upload_data']['file_name'].','; 
                        
                    }
                                        
                    if ($this->upload->do_upload('image5')) {

                        $data = array('upload_data' => $this->upload->data());

                        $venue_image .= $config['upload_url'].$data['upload_data']['file_name']; 
                        
                    }
                    

                    $this->venue_model->save_venue($post_data, $venue_image);
                    sf('success_message', 'Venue created successfully');
                    redirect("admin/venue");
                    
                }
            } else {
                
            }
            $selected_fields = array("address", "neighborhood", "cross_street", "city", "state", "zip", "country", "phone", "lat", "lng", "url", "description");
            $this->gen_contents["venue"] = $this->common_model->getDataExists($selected_fields, "venues" ,array("id" => $venue_id));
            $this->template->write_view('content', 'admin/venue/duplicate_venue', $this->gen_contents);
            $this->template->render();
        }
        catch (Exception $e) 
        {
            
        }
    }
}