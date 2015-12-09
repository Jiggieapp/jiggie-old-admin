<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends CI_Controller {
    
    var $gen_contents	=	array();    
    public function __construct()
    {
        parent::__construct();
        $this->merror['error']	= '';
        $this->msuccess['msg']	= '';
        $this->load->model(array('admin/admin_model', 'common_model', 'admin/chat_model','master_model','admin/permission_model'));
        $this->gen_contents['title']	=	'';
				$this->config->set_item('site_title', 'Party Host  Admin - Chats');
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
        presetpastdaterange();
				$this->gen_contents['current_controller'] = $this->router->fetch_class();
        $this->access_userid = $this->session->userdata("ADMIN_USERID");
        $this->access_usertypeid = $this->session->userdata("USER_TYPE_ID");
        $this->access_permissions = $this->permission_model->get_all_permission();
    }
    
    public function index()
    {
        if(!$this->master_model->checkAccess('view', CHATS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            return FALSE;
        } else {
        	
            $this->chat_list();
        }
    }

     public function chat_list($init=''){
				if(!$this->master_model->checkAccess('view', CHATS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
				 return FALSE;
				}

        $breadCrumbs = array( 'admin/chat/chat_list/0'=>'Chat');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        $this->gen_contents['p_title']= 'Chats';
        $this->gen_contents['ci_view']= 'admin/chat/list_all';
        $this->gen_contents['add_link']= "";
				$this->gen_contents['user_filter']= $this->uri->segment(5);
        $this->gen_contents['export_link']= base_url().'admin/chat/export';
		
        $this->gen_contents['current_controller'] = "chat";
        $this->template->write_view('content', 'admin/listing',$this->gen_contents);
        $this->template->render();
    }
	
	public function ajax_list($init=''){
		
		$is_ajax = $this->input->is_ajax_request();		
        
        $config['base_url'] = base_url().'admin/chat/ajax_list';        
        if ('' != $this->input->post('per_page')) {
            $config['per_page'] = $this->input->post('per_page');            
            $perPage = '';
        }        
        else {
            $config['per_page'] = 25;
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
		$this->gen_contents['offset'] = $offset;
        if('' != $this->input->post ('sort_field')){
               $arr_sort['name']	= $this->input->post ('sort_field');			    
        }
		else {
        	$arr_sort['name'] ='last_updated';
        } 
		
		if('' != $this->input->post ('sort_val')){
               $arr_sort['value']	= $this->input->post ('sort_val');			    
        }
		else {
        	$arr_sort['value'] ='DESC';            
        }
		
        //Search Factor
        $arr_search = array();
        if($this->input->post ('search_name') != "") {
        	$arr_search["where"] = mysql_real_escape_string($this->input->post('search_name')) ;   
			$search_string ="&search_fields=messages.message,topic_venue,guest,host&search_value=".urlencode($arr_search["where"])  ;     
        }else {
            $search_string ="";           
        } 
		// ?search_fields=convo_id&search_value=10152901432247953"	
		if($this->input->post ('search_filter') != "") {
			$search_filter =$this->input->post ('search_filter');
        	$arr_search["search_name"] = mysql_real_escape_string($this->input->post('search_name')) ;  
			$search_string  = "&search_fields=$search_filter&search_value=".urlencode($arr_search["search_name"]); // this "_" will wor only for searching convo_id  
			$date_string= "";              
        } 
        else {
            if($this->input->post ('search_name') != "") {
	        	$arr_search["search_name"] = mysql_real_escape_string($this->input->post('search_name')) ;  
				$search_string  = "&search_fields=guest,host,topic_venue,messages.message&search_value=".urlencode($arr_search["search_name"]);        
	        }else {
	            $search_string='';           
	        }
			$start_date = $this->input->post('startDate_iso');		 
        	$end_date =   $this->input->post('endDate_iso');    
			$date_string= "&start_date=$start_date&end_date=$end_date";          
        } 
		
         //T04:00:00.000Z
         //$start_date = $this->input->post('startDate_iso');		 
        // $end_date =   $this->input->post('endDate_iso');
        // file_put_contents('C:\Users\User\Desktop\1.txt', $start_date.'======'.$end_date);
        //
		      $url = APIURL."admin/admin/chat/list?".TOKEN."&per_page=".$config['per_page']."&offset=".$offset."&sort_field=".$arr_sort['name']."&sort_val=".$arr_sort['value'].$date_string.$search_string;
 		 
		
		 echo $json = file_get_contents($url);exit;
    }
	
    
    
    public function conversation($to='',$from='') {
        try 
        {
            (!$this->authentication->check_logged_in("admin")) ? redirect('admin') :'' ;            
             // $to = safeOffset(4); 
			 //$from = safeOffset(5);	
		     //	10205174476420708&to_id=10155115456340397"
		 	 $url =APIURL."admin/admin/chat/details?from_id=".$from.'&to_id='.$to."&".TOKEN;
             $result['chat_data']= file_get_contents($url);
		   /// $result['from'] =$from;
			echo $json = $result['chat_data'];exit;
        }
        catch (Exception $e) 
        {
            
        }
        exit;
    }
    
	public function export(){
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
        $this->chat_model->export($arr_where, @$arr_sort,  $arr_search);
        exit;
	}
	
    public function APIexport() {
        try {
        	$starttime = new DateTime($this->session->userdata('startDate')." 00:00:00");
	        $start_date = $starttime->format(DateTime::ISO8601) ;
			
			$endtime = new DateTime($this->session->userdata('endDate')." 23:59:59");
	        $end_date = $endtime->format(DateTime::ISO8601) ;
	        $serchval =urlencode($this->session->userdata('chat_search'));
			$arr_sort	= array();
			$arr_sort['name']	= $this->session->userdata('sort_field');
			$arr_sort['value']	= $this->session->userdata('sort_val');
			//var_dump($this->session->userdata('sort_field'));exit;
	        
			$url=APIURL."searchadminconversations?".TOKEN."&key=9dayZ9ydHhda98hd98n8hoO!NsdbffZjnLJ&per_page=&offset=&sort_field=".$arr_sort['name']."&sort_val=".$arr_sort['value']."&start_date=$start_date&end_date=$end_date&search_fields=messages.message,topic_venue,guest,host,&search_value=".$serchval;
			
            $json = file_get_contents($url);
			$array = json_decode($json, true);
			
        
			$filename = "Chat-".date("Y-m-d").".csv";
			$file = fopen($this->config->item("site_basepath") . "uploads/csv/".$filename,"w");
			
			$firstLineKeys = false;
			$skip = array('_id'=>'','convo_id'=>'','messages'=>'','guest_id'=>'','host_id'=>'');
			$p_date = array('last_updated'=>'','topic_date'=>'','created_at'=>'');
			$c_date = array('topic_venue'=>'venue','topic_date'=>'hosting date','created_at'=>'created date');
			foreach ($array['conversations'] as $line)
			{
				if (empty($firstLineKeys))
				{
					$values = array_diff_key ($line , $skip);
					foreach ($values as $key1 => $keyval) {
						if (array_key_exists($key1, $c_date)) {							
						   $n[] = $c_date[$key1];						
						}else{
							$n[] = $key1;	
						}						
					}
					
					$firstLineKeys = array_keys($values);
					
					
					fputcsv($file, $n);
					$firstLineKeys = array_flip($firstLineKeys);
					//var_dump($firstLineKeys);exit;
				}
				$values = array_diff_key ($line , $skip);
				foreach ($values as $key => &$value) {
					if (array_key_exists($key, $p_date)) {
					   $value = date("m/d/Y H:i:s",strtotime($value));						
					}
					if($key=='last_message')
						$value = $value['message'];
				}
				
				fputcsv($file, array_merge($firstLineKeys, $values));
			}
			if (file_exists($this->config->item("site_basepath") . "uploads/csv/".$filename)) {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Pragma: no-cache');
            readfile($this->config->item("site_basepath") . "uploads/csv/".$filename);
	        } else {
	            
	        }
        } catch (Exception $e) {
            
        }
    }
    
}    