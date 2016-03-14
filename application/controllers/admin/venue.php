<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Venue extends CI_Controller {
    
    var $gen_contents	=	array();
    
    public function __construct()
    {
        parent::__construct();
        $this->merror['error']	= '';
        $this->msuccess['msg']	= '';
		    $this->gen_contents['current_controller'] = $this->router->fetch_class();
        $this->load->model(array('admin/venue_model','common_model','admin/permission_model','master_model'));
        $this->gen_contents['title']	=	'';
		    $this->config->set_item('site_title', 'Jiggie  Admin - Venues');
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
        presetpastdaterange();
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
	
    public function venues($init=''){
        if(!$this->master_model->checkAccess('view', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
          return FALSE;
        }

        $this->gen_contents['p_title']= 'Venue listing';
        $this->gen_contents['ci_view']= 'admin/venue/list';
        $this->gen_contents['add_link']= base_url().'admin/venue/create_venue';
        $this->gen_contents['export_link']= base_url().'admin/venue/export';

        $breadCrumbs = array( 'admin/venue/venues/'=>'Venues');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        $this->template->write_view('content', 'admin/listing',$this->gen_contents);
        $this->template->render();

	  }
	
	public function ajax_list($init=''){
		 
		$config['base_url'] = base_url().'admin/venue/ajax_list';
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
		$this->mcontents['offset'] = $offset;
		 
		 
		$arr_where								= array();		
		$arr_sort								= array();
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
                
        $arr_search = array();
        if ($this->input->post('search_name') != "") {
        	$arr_search["where"] = mysql_real_escape_string($this->input->post('search_name')) ;
			$search_string  = "&search_fields=name,address,neighborhood,city,description,&search_value=".urlencode($arr_search["where"]);                   		
        }else {
            
			$search_string  = "";                   
        }
                
		 $start_date = $this->input->post('startDate_iso');		 
         $end_date =   $this->input->post('endDate_iso');
      
		 $url =APIURL."admin/admin/venues/list?".TOKEN."&per_page=".$config['per_page']."&offset=".
		 $offset."&sort_field=".$arr_sort['name']."&sort_val=".$arr_sort['value']."&start_date=$start_date&end_date=$end_date".$search_string;
	 
		 
		
		echo $json = file_get_contents($url);exit;
			
	}
        
     public function venue_details($venue_id = "")
     {
        $breadCrumbs = array( 'admin/venue/venues/'=>'Venues','admin/venue/venue_details/'.$venue_id=>'Venue Details');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs; 
		$this->gen_contents["error"] = false; 
        try 
        {
            if(!$this->master_model->checkAccess('update', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            
            if(empty($venue_id)) 
            {
                
                $this->gen_contents["error"] = true;
                $this->gen_contents["error_msg"] = "Venue details not found"; 
			    echo json_encode($this->gen_contents);exit;
            }
            $url =APIURL."admin/admin/venue/details/$venue_id"."?".TOKEN;
		 
	 		$json = @file_get_contents($url);            
            echo $json ;exit;
        } 
        catch (Exception $e) 
        { 
            $this->gen_contents["error"] = true;
            $this->gen_contents["error_msg"] =  $e->getMessage(); 
			echo json_encode($this->gen_contents);exit;
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
                 sf('error_message', "Venue details not found");  
                 redirect('admin/venue');
            }
            $seleced_fields = array("img_1", "img_2", "img_3", "img_4", "img_5");
            $this->gen_contents['venue_id'] = $venue_id;
            $venue  = $this->common_model->getDataExists($seleced_fields, "venues", array("id" => $venue_id,'venue_status !=' =>10));
            if(empty($venue)) 
            {
                 sf('error_message', "Venue details not found");  
                 redirect('admin/venue');
            }
            $this->gen_contents["venue"] = $venue;
            $breadCrumbs = array( 'admin/venue/venues/'=>'Venues','admin/venue/venues/#/venue-details='.$venue_id=>'Venue Details');
            $this->gen_contents['breadcrumbs'] = $breadCrumbs;
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
         $config['max_size']    = 2000;
         $config['remove_spaces']  = TRUE;
         $config['overwrite'] = true;
         $this->load->library('upload', $config);

		if ($this->upload->do_upload($upload_file)) {                            
            $file_data = $this->upload->data();
			if (file_exists($file_data['full_path'])) {
	            $new_filename = time() . $file_data['file_ext'];
	            rename($file_data['full_path'], $file_data['file_path'] . $new_filename);
	            $data[$field] = $this->config->item("upload_file_url") . 'venues/' . $new_filename;
	            $data["updated_at"] = date("Y-m-d H:i:s");
	            $this->venue_model->profile_img($data, $venue);
	            if ($old_img) {
	                $basename = basename(urldecode($old_img));
	                unlink($this->config->item("upload_file_path") . 'venues/' . $basename);
					sf('success_message', 'Successfully uploaded image');
	            }
	        } else {
	           
				sf('error_message', 'Failed to upload file');
	        }
       
	    }else{
	    	
			sf('error_message', $this->upload->display_errors ());  		
      		
	    }
		
        
        
		exit;
	 
	} 

	public function del_image(){
                if(!$this->master_model->checkAccess('delete', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                    show_error("You don't have access to whatever you are trying to view", $status_code = 500);
                }
		$img =  $this->input->post('old_img');
		$id =  $this->input->post('id');
		$field = $this->input->post('field');
		$data[$field] ='';	
		$data["updated_at"] = date("Y-m-d H:i:s");
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
			$posrval =$this->input->post('value');
             if(isset($posrval)) $data =  $this->input->post('value');else $data =' ';
			 
            $label = $this->input->post('name');
             
            if ($label == "phone") {
                 $res =$this->updateVenue($label, $data, $venue);
            } else if ($label == "url") {
               
                    $res =$this->updateVenue($label, $data, $venue);
                
            }else if ($label == "lat" || $label == "long") {
            	 $data ? $data:0;
                  $res = $this->updateVenue($label, $data, $venue);
            }else {
            	
                if ($res = $this->updateVenue($label, $data, $venue)) {
                    
                } else {
                    echo "Updation failed";
                }
            }
			echo $res;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        exit;
    }
    
    function updateVenue($label, $data, $venue){
    	$url =APIURL."admin/admin/venue/update/".$venue."?".TOKEN;
		if($label=='venue_status'){
			$post_data["object"]          = 'visible';
		}else{
			$post_data["object"]          = $label; 
		}
		
		$post_data["value"]           = $data?$data:' ' ; 
		 
		$post_data["venue_id"]        = $venue;	
		 
		$ch = curl_init($url);					 
		$payload = json_encode( $post_data );	
					  					
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		# Send request.
		$result_set = curl_exec($ch);
		curl_close($ch);
		
		return $result_set;
		
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
    public function delete_venue($venue) {
        if(!$this->master_model->checkAccess('delete', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            return false;
        }

        $url =APIURL."admin/admin/venue/update/".$venue."?".TOKEN;
        $post_data["object"]          = 'active';
		
        $post_data["value"]           = '0' ;

        $post_data["venue_id"]        = $venue;
        $ch = curl_init($url);
        $payload = json_encode( $post_data );

        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result_set = curl_exec($ch);
        curl_close($ch);

        $result =  json_decode($result_set);
		 
        if($result->success=='true') {
            sf('success_message', 'Venue deleted successfully');
            redirect("admin/venue/venues/");
        } else {
            sf('error_message', 'Please try again');
            redirect("admin/venue/venues/");
        }
    }
    
    public function create_venue() {
    	
        try 
        {
            if(!$this->master_model->checkAccess('create', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                    return FALSE;
            }
            
            $this->mcontents = array();
           
            if(!empty($_POST)) {
                $this->_init_venue_validation_rules(); 
                if ($this->form_validation->run() == FALSE) {// form validation
                    sf('error_message', validation_errors());
				   
                } 
                else {
                	//$sel_city = $this->common_model->getDataExists('venue_city_name', "ref_venue_city", array("venue_city_id"=>$this->input->post("city")));  
                    $post_data = array();
                    $post_data["name"]              = $this->input->post("name");
                    $post_data["address"]           = $this->input->post("address");
					$post_data["address2"]           = $this->input->post("address2");
                    $post_data["neighborhood"]      = $this->input->post("neighborhood");                     
                    $post_data["city"]              = $this->input->post("city");					 
                    $post_data["state"]             = $this->input->post("state");
                    $post_data["zip"]               = $this->input->post("zip");                    
                    $post_data["phone"]             = $this->input->post("phone_number");//?preg_replace('/[^0-9]/','', $this->input->post("phone")):'';
                    //$post_data["lat"]               = ($this->input->post("lat"))?preg_replace('/[^0-9\.]/','', $this->input->post("lat")):0;
                    //$post_data["long"]              = ($this->input->post("long"))?preg_replace('/[^0-9\.]/','', $this->input->post("long")):0;
                    $post_data["lat"]               = $this->input->post("lat");
                    $post_data["long"]              = $this->input->post("long");
                 
                    $post_data["url"]               = $this->input->post("url");
                    $post_data["description"]       = $this->input->post("description");

                    $post_data['created_by']        = $this->access_userid;
                   
                   // $post_data["active"]      = $this->input->post("venue_status");
                    $post_data["rank"]             = $this->input->post("rank");
                    $ch = curl_init(APIURL.'admin/admin/venues/add'."?".TOKEN);
					$payload = json_encode( $post_data );
	
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
					curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
					# Send request.
					$result_set = curl_exec($ch);
					curl_close($ch);
					$result =  json_decode($result_set);
					 
					if($result->success==true){						 
						sf('success_message', 'Venue created successfully');
						
						redirect('admin/venue/venues/#/venue-details='.$result->venue->_id);
						//redirect('admin/venue/venues/');
					}else{
						$this->gen_contents['error'] =$result->reason;						 
					}                
	            }  
	            
        	}
			$breadCrumbs = array( 'admin/venue/venues/0'=>'Venues');
            $this->gen_contents['breadcrumbs'] = $breadCrumbs;
            $this->template->write_view('content', 'admin/venue/create_venue', $this->gen_contents);
            $this->template->render();
        }catch (Exception $e) 
        {
            
        }
    }
    
    function _init_venue_validation_rules ()
    {
       $this->form_validation->set_rules('name', 'name', 'required');
	   $this->form_validation->set_rules('phone', 'phone', 'max_length[10]|integer');
	   $this->form_validation->set_rules('lat', 'Latitude', 'callback_latlong_check');
	   $this->form_validation->set_rules('lng', 'Longitude', 'callback_latlong_check');
	
    }
     
	function latlong_check($str){
		if (preg_match('/[-+]?(\d*[.])?\d+/', $str) || trim($str=='')) {
		  return TRUE;
		} else {
		  $this->form_validation->set_message('latlong_check', "The %s field is not valid");
		  return FALSE;
		} 
		
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

    public function addimage(){		 
			if (empty($_FILES['photo'])) {
			    echo json_encode(array('error'=>'No files found for upload.')); 		    
			    return;  
			}		
			$venue_id =  $this->input->post('event_id');
			 	 
			$success = null; 
			$paths= array();	  
			$RealTitleID = $_FILES['photo']['name']; 
			$url = APIURL.'admin/admin/venues/images/add/'.$venue_id."?".TOKEN; // e.g. http://localhost/myuploader/upload.php // request URL
		    
		    $filename = $_FILES['photo']['name'];
		    $filedata = $_FILES['photo']['tmp_name'];
		    $filesize = $_FILES['photo']['size'];
		    if ($filedata != '')
		    {	        	 
				$request = curl_init($url);			 
				curl_setopt($request, CURLOPT_POST, true);
				curl_setopt( $request,  CURLOPT_POSTFIELDS,
			    array(
			      'photo' =>
			          '@'            . $_FILES['photo']['tmp_name']
			          . ';filename=' . $_FILES['photo']['name']
			          . ';type='     . $_FILES['photo']['type'],
			      'venue_id'=>$venue_id
			    ));
				curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($request);			 
				curl_close($request);
				echo $result;
				exit;
			}
	}
	function removeimage($venue_id=''){
		
		 
		$url =APIURL."admin/admin/venues/images/remove/".$venue_id."?".TOKEN;	 
	
		
		$data_to_post = array();
		$data_to_post['url'] = $this->input->post('url');
		$data_to_post['venue_id'] = $venue_id;
		$curl = curl_init();
	
		// Set the options
		curl_setopt($curl,CURLOPT_URL, $url);
		
		// This sets the number of fields to post
		curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
		
		// This is the fields to post in the form of an array.
		curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
		
		//execute the post
		$result = curl_exec($curl);
		
		//close the connection
		curl_close($curl);
		echo 1;
		exit;
	}
    public function export() {
        try {
            if(!$this->master_model->checkAccess('export', VENUES_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            $start_date = $this->session->userdata('startDate')." 00:00:00";
            $end_date = $this->session->userdata('endDate'). " 23:59:59";
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
            $this->venue_model->export($start_date, $end_date,  @$arr_sort,  @$arr_search);
            
            exit;
        } catch (Exception $e) {
            
        }
    }
    
    public function duplicate_venue($venue_id) {
        try 
        {
            (!$this->authentication->check_logged_in("admin")) ? redirect('admin') :'' ;
            if(empty($venue_id)) 
            {
                 sf('error_message', "Venue details not found");  
                 redirect('admin/venue');
            }
            $this->mcontents = array();
            $this->gen_contents["cities"] = '';
            $breadCrumbs = array( 'admin/venue/venues/0'=>'Venues');
            $this->gen_contents['breadcrumbs'] = $breadCrumbs;

		    if(!empty($_POST)) {
                $this->_init_venue_validation_rules(); 
                if ($this->form_validation->run() == FALSE) {// form validation
                    sf('error_message', validation_errors());
				   
                } 
                else {
                	//$sel_city = $this->common_model->getDataExists('venue_city_name', "ref_venue_city", array("venue_city_id"=>$this->input->post("city")));  
                    $post_data = array();
                    $post_data["name"]              = $this->input->post("name");
                    $post_data["address"]           = $this->input->post("address");
					$post_data["address2"]           = $this->input->post("address2");
                    $post_data["neighborhood"]      = $this->input->post("neighborhood");                     
                    $post_data["city"]              = $this->input->post("city");					 
                    $post_data["state"]             = $this->input->post("state");
                    $post_data["zip"]               = $this->input->post("zip");                    
                    $post_data["phone"]             = ($this->input->post("phone"))?preg_replace('/[^0-9]/','', $this->input->post("phone")):'';
                    //$post_data["lat"]               = ($this->input->post("lat"))?preg_replace('/[^0-9\.]/','', $this->input->post("lat")):0;
                    //$post_data["long"]              = ($this->input->post("long"))?preg_replace('/[^0-9\.]/','', $this->input->post("long")):0;
                    $post_data["lat"]               = $this->input->post("lat");
                    $post_data["long"]              = $this->input->post("long");
                 
                    $post_data["url"]               = $this->input->post("url");
                    $post_data["description"]       = $this->input->post("description");
                    
                   // $post_data["active"]      = $this->input->post("venue_status");
                    $post_data["rank"]             = $this->input->post("rank");
                    $ch = curl_init(APIURL.'admin/admin/venues/add'."?".TOKEN);
					$payload = json_encode( $post_data );
	
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
					curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
					# Send request.
					$result_set = curl_exec($ch);
					curl_close($ch);
					$result =  json_decode($result_set);
					 
					if($result->success==true){						 
						sf('success_message', 'Venue created successfully');
						
						redirect('admin/venue/venues/#/venue-details='.$result->venue->_id);
						//redirect('admin/venue/venues/');
					}else{
						$this->gen_contents['error'] =$result->reason;						 
					}                
	            }  
	            
        	}
            $url =APIURL."admin/admin/venue/details/$venue_id"."?".TOKEN;
		 
	 		$json = @file_get_contents($url);
			$venues= json_decode($json); 
			
            $this->gen_contents["venue"] = $venues->data; 	
				 
            if(empty($this->gen_contents["venue"])) 
            {
                 sf('error_message', "Venue details not found");  
                 redirect('admin/venue');
            }
            $this->template->write_view('content', 'admin/venue/duplicate_venue', $this->gen_contents);
            $this->template->render();
        }
        catch (Exception $e) 
        {
            
        }
    }
}