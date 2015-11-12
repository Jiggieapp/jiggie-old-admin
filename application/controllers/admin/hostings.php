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
		$this->config->set_item('site_title', 'Party Host Admin - Hostings');
		presetfuturedaterange();
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
        $this->gen_contents['current_controller'] = $this->router->fetch_class();
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
	
	public function hosting($init=''){
		//var_dump($_SERVER['HTTP_REFERER']);exit;		   
			 
            $this->gen_contents['p_title']= 'Hosting listing';
            $this->gen_contents['ci_view']= 'admin/hostings/listing';
            //$this->gen_contents['add_link']= base_url().'admin/hostings/create';
            $this->gen_contents['add_link']= '';
            $this->gen_contents['export_link']= base_url().'admin/hostings/export';            
            $breadCrumbs = array( 'admin/hostings/hosting/0'=>'Hosting');
			$this->gen_contents['user_filter']= $this->uri->segment(5);
            $this->gen_contents['breadcrumbs'] = $breadCrumbs;
            $venues=  file_get_contents(APIURL.'admin/admin/venuelist'."?".TOKEN);			
			$this->gen_contents['venues'] = json_decode($venues);
            
            $this->template->write_view('content', 'admin/listing',$this->gen_contents);
            $this->template->render();
	}
	
    public function ajax_list($init=''){		
		 
		$page_var = array();
		 
		
		$config['base_url'] = base_url().'admin/hostings/ajax_list';
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
		
		 
        if('' != $this->input->post ('sort_field')){
               $arr_sort['name']	= $this->input->post ('sort_field');			    
        }
		else {
        	$arr_sort['name'] ='start_datetime';
        } 
		
		if('' != $this->input->post ('sort_val')){
               $arr_sort['value']	= $this->input->post ('sort_val');			    
        }
		else {
        	$arr_sort['value'] ='asc';            
        }        
        //Search Factor
        $arr_search = array();
        
		if($this->input->post ('search_filter') != "") {
        	$search_filter =$this->input->post ('search_filter');
        	$arr_search["search_name"] = mysql_real_escape_string($this->input->post('search_name')) ;  
			$search_string  = "&search_fields=$search_filter&search_value=".urlencode($arr_search["search_name"]);   
			$date_string= "";             
        } 
        else {
           if($this->input->post ('search_name') != "") {
	        	$arr_search["search_name"] = mysql_real_escape_string($this->input->post('search_name')) ;  
				$search_string  = "&search_fields=host.first_name,event.title,description&search_value=".urlencode($arr_search["search_name"]);        
	        }else {
	            $search_string='';           
	        }
			$start_date = $this->input->post('startDate_iso');		 
        	$end_date =   $this->input->post('endDate_iso');    
			$date_string= "&start_date=$start_date&end_date=$end_date";
		        
        } 
				 
        
        if($this->input->post ('search_venue') != "") {
            //$arr_search["search_venue"]    = $this->input->post ('search_venue');
			$arr_search["search_venue"] = mysql_real_escape_string($this->input->post('search_venue')) ;
			 
            $search_venue=  "&search_venue=".$arr_search["search_venue"];
        }else {
           
            $search_venue=  "";
        }
                
        if($this->input->post ('search_promoter') != "") {
            //$arr_search["search_promoter"]    = $this->input->post ('search_promoter');
			$arr_search["search_promoter"] = mysql_real_escape_string($this->input->post('search_promoter')) ;
			$page_var['hosting_promoter'] = $arr_search["search_promoter"];
            //$this->session->set_userdata("hosting_promoter", $arr_search["search_promoter"]);
			$search_promoter="&search_promoter=".$arr_search["search_promoter"];
        }
		else {
            $arr_search["search_promoter"]    = "";
            $search_promoter='';
        }                
        if($this->input->post ('search_recurring') != "") {
            //$arr_search["search_recurring"]    = $this->input->post ('search_recurring');
			$arr_search["search_recurring"] = mysql_real_escape_string($this->input->post('search_recurring')) ;
			$page_var['hosting_recurring']=$arr_search["search_recurring"];
            $search_recurring="&search_recurring=".$arr_search["search_recurring"];
        }else {
            $arr_search["search_recurring"]    = "";
			$search_recurring='';			 
        }
        $s_v = $this->session->userdata('hosting_verified');
        if($this->input->post ('search_verified') != "") {
             
            $arr_search["search_verified"] = mysql_real_escape_string($this->input->post('search_verified')) ;
            
            $search_verified ="&search_verified=".$arr_search["search_verified"];
        }else {
             
			 $search_verified='';
        }
 
		
		 //
		 $url =APIURL."admin/admin/hostings/list?".TOKEN."&per_page=".$config['per_page']."&offset=".
		 $offset.$search_venue.$search_promoter.$search_verified.$search_recurring."&sort_field=".$arr_sort['name']."&sort_val=".$arr_sort['value'].$date_string.$search_string;
		  // file_put_contents('C:\Users\User\Desktop\1.txt', $url);
		 echo $json = file_get_contents($url);exit;	
			
	}
    public function create($type,$event_id='')    {
    	if($type!='weekly' && $type!='special'){
    		redirect('admin/events');			
    	}
    	if(!$event_id)
			redirect('admin/events');	
        try 
        {
            if(!$this->master_model->checkAccess('create', HOSTINGS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            
            
            if(!empty($_POST)) {
                $post_data = array();    
       
                $this->_init_hosting_validation_rules(); 
                if ($this->form_validation->run() == FALSE) {// form validation                   
                   $this->gen_contents['error'] ='Please enter name, venue, date, time';
                } elseif(!$this->input->post("user_fb_id")) {
                	$this->gen_contents['error'] ='Please select valid user';                     
                }
                else {
                	if($this->input->post("event_type")=='special'){
                		// $post_data["user_fb_id"]         = $this->input->post("user_fb_id");
	                    $post_data["event_id"]          = $event_id;
	                    $post_data["description"]        = $this->input->post("description");					
	                    //$post_data["is_recurring"]       = $this->input->post("is_recurring");
	                    $post_data["is_verified_table"]  = (int)$this->input->post("verified_table");
	                    $post_data["rank"]               = (int)trim($this->input->post("rank"));
	                    $post_data["start_datetime_str"] = $this->input->post("start_date").' '.$this->input->post("starttime");
					    $post_data["end_datetime_str"]   = $this->input->post("end_time");	
	                    $url =  APIURL.'admin/admin/hosting/add/'.$this->input->post("user_fb_id")."?".TOKEN;					 
						
                	}else{
                		$post_data["event_id"]          = $event_id;
	                    $post_data["description"]        = $this->input->post("description");
	                    $url =  APIURL.'admin/admin/hosting/recurring/add/'.$this->input->post("user_fb_id")."?".TOKEN;	
                	}
                     
                    $ch = curl_init($url);
					$payload = json_encode( $post_data ); 					
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
					curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
					# Send request.
					$result_set = curl_exec($ch);
					
					curl_close($ch);
					$result =  json_decode($result_set);
					 
					
					if($result->success==true){						 
						sf('success_message', 'Hosting created successfully');
						//if(property_exists ( $result->hosting, 'isrecurring' )){
						if($this->input->post("event_type")=='special'){							
							redirect('admin/hostings/hosting/#/hosting-details='.$result->hosting->_id);
						}else{
							redirect('admin/hostings/hosting/#/recurring-hosting-details='.$result->hosting->_id);
						}
						
					}else{
						$this->gen_contents['error'] =$result->reason;						 
					}
                }
            }
			 
			if($type=='weekly')
			$url =APIURL."admin/admin/event/recurring/details/".$event_id."?".TOKEN;
			else {
				$url =APIURL."admin/admin/event/details/".$event_id."?".TOKEN;
			}	
			$breadCrumbs = array( 'admin/hostings/hosting/0'=>'Hosting');
        	$this->gen_contents['breadcrumbs'] = $breadCrumbs;
			$venues=  file_get_contents(APIURL.'admin/admin/venuelist'."?".TOKEN);			
			$this->gen_contents['venues'] = json_decode($venues);
			
 			$events=  file_get_contents($url);
			$this->gen_contents['type']	=$type;		
			$this->gen_contents['events'] = json_decode($events); 
            $this->template->write_view('content', 'admin/hostings/create', $this->gen_contents);
            $this->template->render();
        }
        catch (Exception $e) 
        { 
            
        }
    }
    
    function _init_hosting_validation_rules ()
    {
       $this->form_validation->set_rules('uemail', 'Email', 'required');       
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
	public function searchEmail($search)
    {
        $datas = $this->hosting_model->search($search);
        $array = array();
        
        echo json_encode ($datas); //Return the JSON Array
        exit;
    }
	public function searchuser($search)
    {
        $url =APIURL."admin/admin/user/details/search/".$search."?".TOKEN;	
		 
		echo $json = file_get_contents($url);        
        exit;
    }
    
    public function details($hosting_id = "")
    {
    	if(!$this->master_model->checkAccess('update', HOSTINGS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            return FALSE;
        }
        
		$url =APIURL."admin/admin/hosting/details/".$hosting_id."?".TOKEN;			
		 
		echo $json = file_get_contents($url);        
        exit;
    }
    public function recurringdetails($hosting_id = "")
    {
    	if(!$this->master_model->checkAccess('update', HOSTINGS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            return FALSE;
        }        
		$url =APIURL."admin/admin/hosting/recurring/details/".$hosting_id."?".TOKEN;		 
		echo $json = file_get_contents($url);        
        exit;
    }  
    public function save1($hosting="") {
        try {
            if(!$this->master_model->checkAccess('update', HOSTINGS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            $data = $this->input->post('value');
            $label = $this->input->post('name');
			$parent = $this->input->post('pk');
            $updated = $this->hosting_model->updateHosting($label, $data, $hosting,$parent);
            echo $updated;
        } catch (Exception $e) {
            
        }
        exit;
    }
	public function save($hosting_id){
		  
	    $url =APIURL."admin/admin/hosting/update/".$hosting_id."?".TOKEN;
		$post_data["object"]          = $this->input->post('name'); 
		//$post_data["value"]          = $this->input->post('value') ; 	
		if($post_data["object"] =='is_verified_table'){
		 	 $post_data["value"] =  $this->input->post('value') ==1? true:false;
		}else{
		 	$post_data["value"]          = $this->input->post('value') ;
		}	 
		$post_data["hosting_id"]          = $hosting_id;
		  
		$ch = curl_init($url);					 
		$payload = json_encode( $post_data );	
					  					
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		# Send request.
		$result_set = curl_exec($ch);
		curl_close($ch);
		$result =  json_decode($result_set);
		var_dump($result);
		exit;  
          
	}
	
	public function save_rec($hosting_id){
		  
	    $url =APIURL."admin/admin/hosting/recurring/details/update/".$hosting_id."?".TOKEN;
		$post_data[$this->input->post('name')]          = $this->input->post('value'); 
		//$post_data["value"]          = $this->input->post('value') ; 	
		 
		 
		  
		$ch = curl_init($url);					 
		$payload = json_encode( $post_data );	
		   					
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		# Send request.
		$result_set = curl_exec($ch);
		curl_close($ch);
		$result =  json_decode($result_set);
		var_dump($result);
		exit;  
          
	}
	public function delete_rec_hosting($hosting_id){
		  
	    $url =APIURL."admin/admin/hosting/recurring/details/update/".$hosting_id."?".TOKEN;
		$post_data['active']          = 0; 
		 
		 
		 
		  
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
            sf('success_message', 'Hosting deleted successfully');
            redirect("admin/hostings/hosting/");
        } else {
            sf('error_message', 'Please try again');
            redirect("admin/hostings/hosting");
        }
          
	}
	public function changeuser($hosting=""){
		 $user = $this->input->post('value');
           $parent = $this->input->post('pk');
		$user_details = $this->common_model->getDataExists(array("email","CONCAT_WS(' ', first_name, last_name) as name","profile_image_url"), "users", array("id =" => $user));
		$data["user_image_url"]    = $user_details->profile_image_url;
		$data["user_id"]		   =$user;
		if($this->hosting_model->updateHostinUser($data,$hosting,$parent)){
			 echo json_encode(array("status" => 'success','email'=>$user_details->email,'name'=>$user_details->name,'url'=>$user_details->profile_image_url));
		}else{
			echo json_encode(array("status" => 'success'));
		}
		exit;
	}
	
    public function delete_hosting($hosting_id,$parent=0) {
    	$url =APIURL."admin/admin/hosting/update/".$hosting_id."?".TOKEN;
		$post_data["object"]          = 'active'; 
		$post_data["value"]          = 0 ;
		$post_data["hosting_id"]          = $hosting_id;
		  
		$ch = curl_init($url);					 
		$payload = json_encode( $post_data );	
					  					
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		# Send request.
		$result_set = curl_exec($ch);
		curl_close($ch);
		$result =  json_decode($result_set);
		//var_dump($result);exit;
        if($result->success=='true') {
            sf('success_message', 'Hosting deleted successfully');
            redirect("admin/hostings/hosting/");
        } else {
            sf('error_message', 'Please try again');
            redirect("admin/hostings/hosting");
        }
    }
    
    public function duplicate_hosting($hosting_id="") {
        try 
        {
            (!$this->authentication->check_logged_in("admin")) ? redirect('admin') :'' ;
            $this->gen_contents["venues"] = $this->common_model->getDataExistsArray(array("id", "name"), "venues", "", "", "name ASC");
            if(!empty($_POST)) {
                $post_data = array();
                $post_data["user_id"]           = $this->hosting_model->search_user($this->input->post("uemail"));
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
                    redirect("admin/hostings/hosting/hosting");
                }
            }
			$this->gen_contents['hosting_id'] = $hosting_id;
            $hosting = $this->hosting_model->getHostings($hosting_id);
            if(empty($hosting)) 
            { 
                sf('error_message', "Hosting details not found");  
                redirect('admin/hostings/hosting');
            }
			$breadCrumbs = array( 'admin/hostings/hosting/0'=>'Hosting');
        	$this->gen_contents['breadcrumbs'] = $breadCrumbs;
            $this->gen_contents["hosting"] = $hosting;
            $this->gen_contents["hosting_chat"] = $this->hosting_model->getChatsByHosting( $hosting_id);
            $this->gen_contents["venue"] = $this->common_model->getDataExists(array("name","id"),"venues", array("id" => $hosting->venue_id));
            $this->gen_contents["user"] = $this->common_model->getDataExists(array("id","CONCAT_WS(' ', first_name, last_name) as name,email"),"users", array("id" => $hosting->user_id,'user_status !='=>10));
            $this->gen_contents["venue_all"] = $this->common_model->getDataExistsArray(array("id","name"),"venues");
            $this->gen_contents["users_all"] = $this->common_model->getDataExistsArray(array("id","CONCAT_WS(' ', first_name, last_name) as name"),"users",array("first_name !="=>"' '"), "", "", "", "", "", "", "",1);
           
            
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
			 $url= $_SERVER['QUERY_STRING'];
			 $pars = explode('/',$url);
			 $data = array();
			 if($url){
				foreach ($pars as $values){
				 	$val = explode('=' ,$values);
					$data[$val[0]] =$val[1];
				 }
			 }
			  
            $start_date = $this->session->userdata('startDate') . " 00:00:00";
            $end_date = $this->session->userdata('endDate') . " 23:59:59";
			$arr_where = array("ct.created_at >="=>"$start_date","ct.created_at  <="=>"$end_date");
			if(isset($data['sort_field']))
				$arr_sort['name'] = @$data['sort_field'] ; 
			if(isset($data['sort_val']))
				$arr_sort['value'] = @$data['sort_val'] ;  
			$arr_search["where"] = mysql_real_escape_string(@$data['search_name']) ; 
			$arr_search["hosting_venue"] = @$data['search_venue'] ; 
			$arr_search["hosting_promoter"] = @$data['search_promoter'] ; 
			$arr_search["hosting_recurring"] = @$data['search_recurring'] ; 
			$arr_search["hosting_verified"] = @$data['search_verified'] ; 
	        $this->hosting_model->export($start_date, $end_date, @$arr_sort,  @$arr_search);
            //$this->hosting_model->export($start_date, $end_date, $this->session->userdata("hosting_search"), 
            //$this->session->userdata("hosting_venue"), $this->session->userdata("hosting_promoter"), $this->session->userdata("hosting_recurring"));
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
        $email_exists = $this->common_model->getDataExists(array("email"), "users", array("email" => $email,'user_status !='=>10));
        if (empty($email_exists)) {
            echo json_encode(array("status" => 1));
        } else {
            echo json_encode(array("status" => 0));
        }

        exit;
    }
	function check_email_validate() {
		echo "true"; exit;
        $email = $this->input->post('uemail');       
        $email_exists = $this->common_model->getDataExists(array("email"), "users", array("email" => $email,'user_status !='=>10));
        if (empty($email_exists)) {
            echo "true";
        } else {
            echo "true";
        }

        exit;
    }
}