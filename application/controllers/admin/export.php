<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Export extends CI_Controller {

    var $gen_contents = array();

    public function __construct() {

        parent::__construct();
		$this->gen_contents['current_controller'] = $this->router->fetch_class();
        $this->merror['error'] = '';
        $this->msuccess['msg'] = '';
        $this->load->model(array('admin/admin_model', 'common_model', 'admin/user_model','admin/permission_model','master_model'));
        $this->gen_contents['title'] = '';
        presetpastdaterange();
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
        //$this->load->config('cimongo'); 
		//$this->load->library("cimongo/cimongo");	
		  
		
	     
    }
	
	public function user(){


		$querystring = $_SERVER['QUERY_STRING'] ;
		$var = explode('/', $querystring);		 
		$params= array();
		foreach ($var as $key => $value) {
			if($value){
				$param=  explode('=', $value);
				$params[$param[0]]=$param[1];
			}
			
		}
		
	    if(!array_key_exists ('sort_field', $params)) {        
               $params['sort_field'] ='created_at';			    
        }
		if(!array_key_exists ('sort_val', $params)) { 
		  $params['sort_val'] ='DESC';			    
        }		 
		
        
        if(array_key_exists ('search_filter', $params) && array_key_exists ('search_name', $params)) {		 
			$search_fields =$params['search_filter'];
			$search_value =$params['search_name'];
			$date_string= false;
			$search_string =true;        	             
        } 
        else {
            if(array_key_exists ('search_name', $params)) {
	        	 
				$search_fields  = "first_name,last_name,email,location,gender";  
				$search_value =$params['search_name'];  
				$search_string=true;      
	        }else {
	            $search_string=false;           
	        }
			$date_string= true;  
			$search_value='';    
        } 
		 
		
        
		$start_date = $params['start_date'];
		$end_date = $params['end_date'];
		




		//$start_date = $this->session->userdata('startDate') . " 00:00:00";
            //$end_date = $this->session->userdata('endDate') . " 23:59:59";
        $urlToLoad = APIURL."admin/admin/csv/customers/".$start_date."/".$end_date."?".TOKEN;
        //header('Location: ' + $urlToLoad); 
		//echo json_encode(array("status" => $urlToLoad));
		redirect($urlToLoad, 'location');
		//return;
           /*

		if($date_string){
			//$date_clause = array("created_at"=>array('$gte'=>$start,'$lte'=>$end));
			// $this->cimongo->where($date_clause, TRUE);
		}
		  
	     $this->cimongo->select(array('first_name','last_name','email','birth_date','gender','location','created_at',
		 						'last_login','chat_count','hosting_count','user_ref_name','ref_count','is_verified_host','active'));
 
         $where_clause = array('$or'=>array( array("first_name"=>new MongoRegex("/".$search_value."/i")),
           					 				  array("last_name"=>new MongoRegex("/".$search_value."/i")),
           					 				  array("email"=>new MongoRegex("/".$search_value."/i")),
           					 				  array("location"=>new MongoRegex("/".$search_value."/i")),
           					 				  array("gender"=>new MongoRegex("/".$search_value."/i")),
           					 		 ),
           					 		'created_at'=>array('$gte' => $start,'$lte' => $end),
           					 	    'fb_id' => array('$ne' => '') );
        $this->cimongo->order_by(array($params['sort_field']=>$params['sort_val']));
		 
		$this->cimongo->where($where_clause, TRUE);
		$query = $this->cimongo->get('customers');
		
		$rows = $query->result_array();
		//echo count($rows);
		//var_dump($rows);
		 
		$filename = "Chat-".date("Y-m-d").".csv";
		$file = fopen($this->config->item("site_basepath") . "uploads/csv/".$filename,"w");
		
		$firstLineKeys = false;
		$skip = array('_id'=>'');
		$p_date = array('last_login'=>'','created_at'=>'');
		$c_date = array('first_name'=>'First name','last_name'=>'Last Name','email'=>'Email','birth_date'=>'Age','gender'=>'Sex','location'=>'Location','created_at'=>'Joined','last_login'=>'Last','chat_count'=>'Chat','hosting_count'=> 'Hostings','user_ref_name'=>'Invited By','ref_count'=>'Invites','is_verified_host'=>'Verified','active'=>'Status');
		 
		foreach ($rows as $key => $line)
		{
			if (empty($firstLineKeys))
			{
				
				foreach ($c_date as $key => $value) {
					 $n[] = $value;
				}
				fputcsv($file, $n);
				$firstLineKeys=true;
				 
			}
			$values = $line; 
			foreach ($c_date as $key => $value) {
				if (array_key_exists($key, $values)) {
					if (array_key_exists($key, $p_date)) {	
						 
						$test[$key] = date('m/d/y h:i a', $values[$key]->sec);									
					}elseif($key=='birth_date' ){
						if($values[$key]->sec!=2085996496){ // need to change this logic 
							$from = new DateTime(date('m/d/y', $values[$key]->sec));
							$to   = new DateTime('today');
							$test[$key]= $from->diff($to)->y;
						}else{
							$test[$key]=115;
						}
					}elseif($key=='is_verified_host'){
						$test[$key]= $values[$key]?'yes':'No';
					}elseif($key=='active'){
						$test[$key]= $values[$key]?'Active':'Inactive';
					}else{
						$test[$key] = $values[$key];
					}
					
				}else{
					if($key=='active'){
						$test[$key]='Avtive';
					}elseif($key=='is_verified_host'){
						$test[$key]='No';
					}else{
						$test[$key]='n/a';
					}
					
					
				}
			}
 
			fputcsv($file, $test);
		}
		if (file_exists($this->config->item("site_basepath") . "uploads/csv/".$filename)) {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        readfile($this->config->item("site_basepath") . "uploads/csv/".$filename);
        } else {
            
        }
        */
	}
	public function venues(){
		$querystring = $_SERVER['QUERY_STRING'] ;
		$var = explode('/', $querystring);		 
		$params= array();
		foreach ($var as $key => $value) {
			if($value){
				$param=  explode('=', $value);
				$params[$param[0]]=$param[1];
			}
			
		}
		
	    if(!array_key_exists ('sort_field', $params)) {        
               $params['sort_field'] ='created_at';			    
        }
		if(!array_key_exists ('sort_val', $params)) { 
		  $params['sort_val'] ='DESC';			    
        }		 
		
        
        if(array_key_exists ('search_filter', $params) && array_key_exists ('search_name', $params)) {		 
			$search_fields =$params['search_filter'];
			$search_value =$params['search_name'];
			$date_string= false;
			$search_string =true;        	             
        } 
        else {
            if(array_key_exists ('search_name', $params)) {
	        	 
				$search_fields  = "first_name,last_name,email,location,gender";  
				$search_value =$params['search_name'];  
				$search_string=true;      
	        }else {
	            $search_string=false;           
	        }
			$date_string= true;  
			$search_value='';    
        } 
		 
		
        
		$start = new MongoDate(strtotime($params['start_date']));
		$end = new MongoDate(strtotime($params['end_date']));
		
		 
		
		if($date_string){
			//$date_clause = array("created_at"=>array('$gte'=>$start,'$lte'=>$end));
			// $this->cimongo->where($date_clause, TRUE);
		}
		  
	     $this->cimongo->select(array('name','address','neighborhood','city','description','host_cnt'));
 
         $where_clause = array('$or'=>array( array("name"=>new MongoRegex("/".$search_value."/i")),
           					 				  array("address"=>new MongoRegex("/".$search_value."/i")),
           					 				  array("neighborhood"=>new MongoRegex("/".$search_value."/i")),
           					 				  array("city"=>new MongoRegex("/".$search_value."/i")),
           					 				  array("description"=>new MongoRegex("/".$search_value."/i")),
           					 		 ),
           					 		'created_at'=>array('$gte' => $start,'$lte' => $end),
           					 	    );
        $this->cimongo->order_by(array($params['sort_field']=>$params['sort_val']));
		 
		$this->cimongo->where($where_clause, TRUE);
		$query = $this->cimongo->get('venues');
		
		$rows = $query->result_array();
		//echo count($rows);
		//var_dump($rows);
		 
		$filename = "Venues-".date("Y-m-d").".csv";
		$file = fopen($this->config->item("site_basepath") . "uploads/csv/".$filename,"w");
		
		$firstLineKeys = false;
		$skip = array('_id'=>'');
		$p_date = array();
		$c_date = array('name'=>'Name','address'=>'Address','neighborhood'=>'Neighborhood','city'=>'City','description'=>'Description','host_cnt'=>'Hostings');
		 
		foreach ($rows as $key => $line)
		{
			if (empty($firstLineKeys))
			{
				
				foreach ($c_date as $key => $value) {
					 $n[] = $value;
				}
				fputcsv($file, $n);
				$firstLineKeys=true;
				 
			}
			$values = $line; 
			foreach ($c_date as $key => $value) {
				if (array_key_exists($key, $values)) {
					if (array_key_exists($key, $p_date)) {	
						 
						$test[$key] = date('m/d/y h:i a', $values[$key]->sec);									
					}else{
						$test[$key] = $values[$key];
					}
					
				}else{
					$test[$key]='0';
					
					
				}
			}

 
			fputcsv($file, $test);
		}
		if (file_exists($this->config->item("site_basepath") . "uploads/csv/".$filename)) {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        readfile($this->config->item("site_basepath") . "uploads/csv/".$filename);
        } else {
            
        }
	}
public function orders(){
		$querystring = $_SERVER['QUERY_STRING'] ;
		$var = explode('/', $querystring);		 
		$params= array();
		foreach ($var as $key => $value) {
			if($value){
				$param=  explode('=', $value);
				$params[$param[0]]=$param[1];
			}
			
		}
		
	    if(!array_key_exists ('sort_field', $params)) {        
               $params['sort_field'] ='event.start_datetime';			    
        }
		if(!array_key_exists ('sort_val', $params)) { 
		  $params['sort_val'] ='asc';			    
        }		 
		
        
        if(array_key_exists ('search_filter', $params) && array_key_exists ('search_name', $params)) {		 
			$search_fields =$params['search_filter'];
			$search_value =$params['search_name'];
			$date_string= false;
			$search_string =true;        	             
        } 
        else {
            if(array_key_exists ('search_name', $params)) {
	        	 
				$search_fields  = "search_fields=_id,customer.first_name,event.venue_name,ticket.ticket_type.ticket_type,ticket.ticket_type.name";  
				$search_value =$params['search_name'];  
				$search_string=true;      
	        }else {
	            $search_string=false;           
	        }
			$date_string= true;  
			$search_value='';    
        } 
		 
		
        
		$start = new MongoDate(strtotime($params['start_date']));
		$end = new MongoDate(strtotime($params['end_date']));
		
		 
		
		if($date_string){
			//$date_clause = array("created_at"=>array('$gte'=>$start,'$lte'=>$end));
			// $this->cimongo->where($date_clause, TRUE);
		}
		  
	     //$this->cimongo->select(array('customer.first_name','event.venue_name','event.start_datetime','created_at','ticket.ticket_type.ticket_type','ticket.ticket_type.name','ticket.ticket_type.price','ticket.ticket_type.total','confirmation_count','ticket.ticket_type.guest','status'));
          
         $where_clause = array('$or'=>array( array("_id"=>new MongoRegex("/".$search_value."/i")),
           					 				  //array("customer.first_name"=>new MongoRegex("/".$search_value."/i")),
           					 				  //array("event.venue_name"=>new MongoRegex("/".$search_value."/i")),
           					 				  //array("ticket.ticket_type.ticket_type"=>new MongoRegex("/".$search_value."/i")),
           					 				  //array("ticket.ticket_type.name"=>new MongoRegex("/".$search_value."/i")),
           					 		 ),
           					 		'created_at'=>array('$gte' => $start,'$lte' => $end),
           					 	     );
        $this->cimongo->order_by(array($params['sort_field']=>$params['sort_val']));
		 
		$this->cimongo->where($where_clause, TRUE);
		$query = $this->cimongo->get('orders');
		
		$rows = $query->result_array();
		//echo count($rows);
		
		
		var_dump($rows);
		 exit;
		$filename = "Orders-".date("Y-m-d").".csv";
		$file = fopen($this->config->item("site_basepath") . "uploads/csv/".$filename,"w");
		
		$firstLineKeys = false;
		$skip = array('_id'=>'');
		$p_date = array('event.start_datetime'=>'','created_at'=>'');
		$c_date = array('customer.first_name'=>'Name','event.venue_name'=>'Venue',
		'event.start_datetime'=>'Date.time','created_at'=>'Order Date/Time','ticket.ticket_type.ticket_type'=>'Type','ticket.ticket_type.name'=>'Ticket','ticket.ticket_type.price'=>'Price','ticket.ticket_type.total'=>'Paid',
		'ticket.ticket_type.guest'=> 'Guest','status'=>'Status');
		 
		foreach ($rows as $key => $line)
		{
			if (empty($firstLineKeys))
			{
				
				foreach ($c_date as $key => $value) {
					 $n[] = $value;
				}
				fputcsv($file, $n);
				$firstLineKeys=true;
				 
			}
			$values = $line; 
			foreach ($c_date as $key => $value) {
				if (array_key_exists($key, $values)) {
					if (array_key_exists($key, $p_date)) {	
						 
						$test[$key] = date('m/d/y h:i a', $values[$key]->sec);									
					}else{
						$test[$key] = $values[$key];
					}
					
				}else{
					if($key=='active'){
						$test[$key]='Avtive';
					}elseif($key=='is_verified_host'){
						$test[$key]='No';
					}else{
						$test[$key]='n/a';
					}
					
					
				}
			}
 
			fputcsv($file, $test);
		}
		if (file_exists($this->config->item("site_basepath") . "uploads/csv/".$filename)) {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        readfile($this->config->item("site_basepath") . "uploads/csv/".$filename);
        } else {
            
        }
	}
}
?>