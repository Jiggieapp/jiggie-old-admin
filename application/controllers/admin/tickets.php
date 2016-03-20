<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tickets extends CI_Controller {
    
    var $gen_contents	=	array();    
    public function __construct()
    {
      parent::__construct();
      $this->merror['error']	= '';
      $this->msuccess['msg']	= '';
      $this->load->model(array('master_model','admin/permission_model'));
      $this->gen_contents['title']	=	'';
			$this->config->set_item('site_title', 'Jiggie  Admin - Events Tickets');
      (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
      presetfuturedaterange();
			$this->gen_contents['current_controller'] = $this->router->fetch_class();
      $this->access_userid = $this->session->userdata("ADMIN_USERID");
      $this->access_usertypeid = $this->session->userdata("USER_TYPE_ID");
      $this->access_permissions = $this->permission_model->get_all_permission();		
    }
      
    public function index()
    {
      if(!$this->master_model->checkAccess('view', TICKETS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions) || 
      	!$this->master_model->checkAccess('update', TICKETS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
        return FALSE;
      } else {
        $this->ticket_list();
      }
    }
	
    public function ticket_list($init=''){     	     
      $breadCrumbs = array( 'admin/tickets/'=>'Tickets');
      $this->gen_contents['breadcrumbs'] = $breadCrumbs;
      $this->gen_contents['p_title']= 'Ticket List';
      $this->gen_contents['current_controller'] = "tickets";
			$this->gen_contents['export_link']= base_url().'admin/tickets/export';
			$this->gen_contents['add_link']= 'admin/tickets/add';
        //$this->template->write_view('content', 'admin/events',$this->gen_contents);
			$this->template->write_view('content', 'admin/listing',$this->gen_contents);
      $this->template->render();		 
    }
	
	public function ajax_list(){
		
		
         
		 //T04:00:00.000Z
         $start_date = $this->input->post('startDate_iso');		 
         $end_date =   $this->input->post('endDate_iso');
		 $event_id = $this->input->post('event-id');
		 $resuuring = (int)$this->input->post('is_recurring');
		  
		 if($resuuring==1){
		 	$urlpart= "admin/admin/ticket-type/recurring/list/".$event_id;
			$add_url= base_url()."admin/tickets/create/$event_id/1";
		 }else{
		 	$add_url= base_url()."admin/tickets/create/$event_id/0";
		 	$urlpart= "admin/admin/ticket-type/list/".$event_id;
		 }
		 	
        // file_put_contents('C:\Users\User\Desktop\1.txt', $start_date.'======'.$end_date);
		 $url =APIURL.$urlpart."?".TOKEN;
		 //."?per_page=".$config['per_page']."&offset=".
		 //$offset."&sort_field=".$arr_sort['name']."&sort_val=".$arr_sort['value']."&sort_status=".$sort_status."&start_date=$start_date&end_date=$end_date".$search_string;
	 
     	 //echo $json = file_get_contents($url);exit;
         $data= array();
  
		// $data["paginate"] = array("per_page"=> 10,"total_rows"=> 10,"total_page"=> 1,"offset"=> 1,"total_count"=> 10,"data_search"=> "") ;
    	 $data["current_controller"] ='tickets';
		 $data['add_url'] = $add_url;
    	 $data['tickets'] = json_decode(file_get_contents($url)) ;
    
    
         echo json_encode($data);
        // echo json_encode($data);
         exit;
    }

	public function create($event_id,$type){	
   		try 
       {
            if(!$this->master_model->checkAccess('create', TICKETS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                    return FALSE;
            }
            
            $this->mcontents = array();
            
			
            if(!empty($_POST)) {                 
		          $post_data["name"]              = $this->input->post("name");
							$post_data["event_id"]          = $this->input->post("event_id");
							$post_data["is_recurring"]      = $this->input->post("is_recurring_event");
							$post_data["ticket_type"]       = $this->input->post("ticket_type");
							$post_data["quantity"]          = $this->input->post("quantity")?$this->input->post("quantity"):'0';   
						  $post_data["guest"]             = $this->input->post("guest")?$this->input->post("guest"):'0'; 
						  $post_data["currency"]					= $this->input->post("currency") ? $this->input->post("currency") : 'Rp';
							$post_data["price"]             = $this->input->post("price")?$this->input->post("price"):'0';
							$post_data["deposit"]           = $this->input->post("deposit")?$this->input->post("deposit"):'0'; 
							$post_data["add_guest"]         = $this->input->post("add_guest")?$this->input->post("add_guest"):'0'; 
							$post_data["chk_adminfee"]      = $this->input->post("chk_adminfee")?'1':'0';   
							$post_data["admin_fee"]         = $this->input->post("admin_fee")?$this->input->post("admin_fee"):'0';   
							$post_data["chk_tax"]           = $this->input->post("chk_tax")?'1':'0'; 
							$post_data["tax"]               = $this->input->post("tax")?$this->input->post("tax"):'0';  
							$post_data["tip"]               = $this->input->post("tip")?$this->input->post("tip"):'0'; 
							$post_data["chk_tip"]           = $this->input->post("chk_tip")?'1':'0'; 				
							$post_data["total"]             = $this->input->post("total")?$this->input->post("total"):'0'; 
							$post_data["description"]       = $this->input->post("description"); 
							$post_data["status"]     				= $this->input->post("ticket_status");			  
							$post_data["payment_timelimit"] = $this->input->post("payment_timelimit");
						    //$post_data["chk_fullamt"]       = $this->input->post("chk_fullamt"); 
							//$post_data["full_amt_box"]      = $this->input->post("full_amt_box"); 
							//$post_data["chk_matching"]      = $this->input->post("chk_matching"); 
							//$post_data["matching_box"]      = $this->input->post("matching_box");
							//$post_data["chk_returns"]       = $this->input->post("chk_returns"); 
							//$post_data["returns_box"]       = $this->input->post("returns_box");  
			 				//$post_data["confirmation"]      = $this->input->post("confirmation");  
							$purchase_confirmations= array();
							$i=0;
							if($this->input->post("chk_fullamt")){
								$purchase_confirmations[$i]['label'] = 'Full Amount';
								$purchase_confirmations[$i]['body']  =$this->input->post("full_amt_box");
								$i++;
							}
							if($this->input->post("chk_matching")){
								$purchase_confirmations[$i]['label'] = 'Matching CC &ID';
								$purchase_confirmations[$i]['body']  =$this->input->post("matching_box");
								$i++;
							}
							if($this->input->post("chk_returns")){
								$purchase_confirmations[$i]['label'] = 'No returns';
								$purchase_confirmations[$i]['body']  =$this->input->post("returns_box");
								$i++;
							}
							$cnfm_ids = explode(',', rtrim($this->input->post("cnfm_ids"),","));
							
							 
							foreach($cnfm_ids as $id){
								 
								if($this->input->post("new_label".$id)){
									$purchase_confirmations[$i]['label'] =$this->input->post("new_label".$id);
									$purchase_confirmations[$i]['body']  =$this->input->post("confirmation".$id);
									$i++;
								}
								
							}
							
							
							$post_data['purchase_confirmations'] =  json_encode($purchase_confirmations);
		 
							$ch = curl_init(APIURL.'admin/admin/ticket-type/add/'.$this->input->post("event_id")."?".TOKEN );					 
							$payload = json_encode( $post_data );

							curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
							curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
							curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
							# Send request.
							$result_set = curl_exec($ch);
							curl_close($ch);
							$result =  json_decode($result_set);
							
							if($result->success==true){						 
								sf('success_message', 'Ticket created successfully');	
								if($result->tickettype->is_recurring)					 
									redirect('admin/tickets/#/ticket-recurring='.$result->tickettype->_id);
								else
									redirect('admin/tickets/#/ticket-details='.$result->tickettype->_id);
							}else{
								$this->gen_contents['error'] =$result->reason;
							}
                }
             
            $breadCrumbs = array( 'admin/tickets/'=>'Tickets');
            $this->gen_contents['breadcrumbs'] = $breadCrumbs;
			$this->gen_contents['event_id']=$event_id;
			///admin/admin/event/recurring/details/:id
			if($type)
				$url =APIURL."admin/admin/event/recurring/details/".$event_id."?".TOKEN;
			else
				$url =APIURL."admin/admin/event/details/".$event_id."?".TOKEN;
 			$events=  file_get_contents($url);	
			//sf('error_message', 'Unable to load Event details');	
		 
			 	
			$events = json_decode($events);
			if(property_exists($events, "success")){
				if($events->success==false){
					sf('error_message', 'Unable to load event details');
					redirect('admin/events/');	
				}
			}
		    $this->gen_contents['events'] = $events;
			 
						
			$this->gen_contents['type']=$type;
            $this->template->write_view('content', 'admin/ticket/create_ticket', $this->gen_contents);
            $this->template->render();
        }
        catch (Exception $e) 
        {
            
        }
   }
	function duplicate($ticket_id,$type=''){
		if(!$ticket_id)
		return FALSE;
   		try 
        {
            if(!$this->master_model->checkAccess('create', TICKETS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                    return FALSE;
            }
            
            $this->mcontents = array();
            
			
            if(!empty($_POST)) {                 
                	 
				   
          $post_data["name"]              = $this->input->post("name");
					$post_data["event_id"]          = $this->input->post("event_id");
					$post_data["is_recurring"]      = $this->input->post("is_recurring_event");
					$post_data["ticket_type"]       = $this->input->post("ticket_type");
					$post_data["quantity"]          = $this->input->post("quantity")?$this->input->post("quantity"):'0';   
				  $post_data["guest"]             = $this->input->post("guest")?$this->input->post("guest"):'0'; 
				  $post_data["currency"]					= $this->input->post("currency") ? $this->input->post("currency") : 0;
					$post_data["price"]             = $this->input->post("price")?$this->input->post("price"):'0';
					$post_data["deposit"]           = $this->input->post("deposit")?$this->input->post("deposit"):'0'; 
					$post_data["add_guest"]         = $this->input->post("add_guest")?$this->input->post("add_guest"):'0'; 
					$post_data["chk_adminfee"]      = $this->input->post("chk_adminfee")?'1':'0';   
					$post_data["admin_fee"]         = $this->input->post("admin_fee")?$this->input->post("admin_fee"):'0';   
					$post_data["chk_tax"]           = $this->input->post("chk_tax")?'1':'0'; 
					$post_data["tax"]               = $this->input->post("tax")?$this->input->post("tax"):'0';  
					$post_data["tip"]               = $this->input->post("tip")?$this->input->post("tip"):'0'; 
					$post_data["chk_tip"]           = $this->input->post("chk_tip")?'1':'0'; 				
					$post_data["total"]             = $this->input->post("total")?$this->input->post("total"):'0'; 
					$post_data["description"]       = $this->input->post("description"); 
					$post_data["status"]     		= $this->input->post("ticket_status");	
				 				  
				    //$post_data["chk_fullamt"]       = $this->input->post("chk_fullamt"); 
					//$post_data["full_amt_box"]      = $this->input->post("full_amt_box"); 
					//$post_data["chk_matching"]      = $this->input->post("chk_matching"); 
					//$post_data["matching_box"]      = $this->input->post("matching_box");
					//$post_data["chk_returns"]       = $this->input->post("chk_returns"); 
					//$post_data["returns_box"]       = $this->input->post("returns_box");  
	 				//$post_data["confirmation"]      = $this->input->post("confirmation");  
					$purchase_confirmations= array();
					$confirmations_count = $this->input->post("confirmationscount");
					$j=0;$i=0;
					if($confirmations_count){
						for($i=0;$i<=$confirmations_count;$i++){
							 
							if($this->input->post("chk_".$i)){
								
								$purchase_confirmations[$i]['label'] =$this->input->post("txt_".$i);
						    	$purchase_confirmations[$i]['body']  =$this->input->post($i."_box");
								$j++;
							}
						}
					}
					 
					$cnfm_ids = explode(',', rtrim($this->input->post("cnfm_ids"),","));
					
					 
					foreach($cnfm_ids as $id){
						 
						if($this->input->post("new_label".$id)){
							$purchase_confirmations[$i]['label'] =$this->input->post("new_label".$id);
							$purchase_confirmations[$i]['body']  =$this->input->post("confirmation".$id);
							$i++;
						}
						
					}
					
					$post_data['purchase_confirmations'] =  json_encode($purchase_confirmations);

					$ch = curl_init(APIURL.'admin/admin/ticket-type/add/'.$this->input->post("event_id")."?".TOKEN );					 
					$payload = json_encode( $post_data );
					 					
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
					curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
					# Send request.
					$result_set = curl_exec($ch);
					curl_close($ch);
					$result =  json_decode($result_set);
					// var_dump($result);exit;
					if($result->success==true){						 
						sf('success_message', 'Ticket created successfully');	
						if($result->tickettype->is_recurring)					 
							redirect('admin/tickets/#/ticket-recurring='.$result->tickettype->_id);
						else
							redirect('admin/tickets/#/ticket-details='.$result->tickettype->_id);
					}else{
						$this->gen_contents['error'] =$result->reason;
					}
                }
             
            $breadCrumbs = array( 'admin/tickets/'=>'Tickets');
            $this->gen_contents['breadcrumbs'] = $breadCrumbs;
			//$this->gen_contents['event_id']=$event_id;
			///admin/admin/event/recurring/details/:id
			 
			if($type=='true'){
				 
				$url =APIURL."admin/admin/ticket-type/recurring/details/$ticket_id"."?".TOKEN;
			}else {				 
				 $url =APIURL."admin/admin/ticket-type/details/$ticket_id"."?".TOKEN;
			}
		    //$url =APIURL."admin/admin/ticket-type/recurring/details/$ticket_id"."?".TOKEN;
			//$url =APIURL."admin/admin/ticket-type/details/$ticket_id"."?".TOKEN;
			 
 			$ticket=  file_get_contents($url);	
			$ticket=  json_decode($ticket);			 
			if(property_exists($ticket, "success")){
				if($events->success==false){
					sf('error_message', 'Unable to load ticket details');
					redirect('admin/events/');	
				}
			}
			if($ticket->is_recurring==true)
				$url_e =APIURL."admin/admin/event/recurring/details/".$ticket->event_id."?".TOKEN;
			else {
				$url_e =APIURL."admin/admin/event/details/".$ticket->event_id."?".TOKEN;
			}
			$event  = file_get_contents($url_e);
			$this->gen_contents['ticket'] = $ticket;	
			$this->gen_contents['event']  = json_decode($event);			
			//$this->gen_contents['type']=$type;
            $this->template->write_view('content', 'admin/ticket/duplicate', $this->gen_contents);
            $this->template->render();
        }
        catch (Exception $e) 
        {
            
        }
	}
	
	function ticket_details($ticket_id = ""){
		$breadCrumbs = array( );
    $this->gen_contents['breadcrumbs'] = $breadCrumbs; 
		$this->gen_contents["error"] = false; 
        try 
        {
            if(!$this->master_model->checkAccess('update', TICKETS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            
            if(empty($ticket_id)) 
            {
                
                $this->gen_contents["error"] = true;
                $this->gen_contents["error_msg"] = "Ticket details not found"; 
			    echo json_encode($this->gen_contents);exit;
            }
            $url =APIURL."admin/admin/ticket-type/details/$ticket_id"."?".TOKEN;
		 
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
	function ticket_recurring($ticket_id = ""){
		$breadCrumbs = array( );
        $this->gen_contents['breadcrumbs'] = $breadCrumbs; 
		$this->gen_contents["error"] = false; 
        try 
        {
            if(!$this->master_model->checkAccess('update', TICKETS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            
            if(empty($ticket_id)) 
            {
                
                $this->gen_contents["error"] = true;
                $this->gen_contents["error_msg"] = "Ticket details not found"; 
			    echo json_encode($this->gen_contents);exit;
            }
            $url =APIURL."admin/admin/ticket-type/recurring/details/$ticket_id"."?".TOKEN;
		 
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

	public function save( $ticket="",$is_recurring='') {
    	 
        try {
        	
        	if($is_recurring=='true')
				$url =APIURL."admin/admin/ticket-type/update/recurring/".$ticket."?".TOKEN;
			else
           		$url =APIURL."admin/admin/ticket-type/update/".$ticket."?".TOKEN;		  
             
			$post_data["object"]          = $this->input->post('name'); 
			if($post_data["object"]=='visible')
				$post_data["value"]           = $this->input->post('value');
			else
				$post_data["value"]           = $this->input->post('value')?$this->input->post('value'):''; 
			
			$post_data["ticket_id"]        = $ticket;	
			 
			$ch = curl_init($url);					 
			$payload = json_encode( $post_data );	
						  					
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			 # Send request.
			$result_set = curl_exec($ch);
			curl_close($ch);
			$result =  json_decode($result_set);
			echo $result_set;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        exit;
    }
    public function delete( $ticket="",$event='',$is_recurring='') {
			if(!$this->master_model->checkAccess('delete', TICKETS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
				return FALSE;
			}
			
        try {
        	
        	if($is_recurring=='true'){
        		$is_recurring=1;
				$ticket_hash ='ticket-recurring';				
				$url =APIURL."admin/admin/ticket-type/delete/recurring/".$ticket."?".TOKEN;
				$redirecturl ='admin/events/#/event-weekly='.$event;
        	}        	    
			else{
				$ticket_hash ='ticket-details';
				$is_recurring=0;
				$url =APIURL."admin/admin/ticket-type/delete/".$ticket."?".TOKEN;
				$redirecturl ='admin/events/#/event-special='.$event;
			}
           		 		  
             
			$post_data["object"]          = 'active'; 
			$post_data["value"]           = 0; 
			
			$post_data["ticket_type_id"]        = $ticket;		
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
	            sf('success_message', 'Ticket deleted successfully');
	            //redirect("admin/tickets/#event-id=".$event."/is_recurring=".$is_recurring);
	            redirect($redirecturl);
	        } else {
	            sf('error_message', 'Please try again');
	            redirect("admin/tickets/#/".$ticket_hash."=".$ticket);
	        }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        exit;
    }

	function updateticket(){
		if(!empty($_POST)) {                 
                	 
				    
          $post_data["name"]              = $this->input->post("name");
					//$post_data["event_id"]          = $this->input->post("event_id");
					$post_data["is_recurring"]      = $this->input->post("is_recurring");
					$post_data["ticket_type"]       = $this->input->post("ticket_type");
					$post_data["event_id"]	        = $this->input->post("event_id");
					$post_data["ticket_type_id"]    = $this->input->post("ticket_type_id");
					$post_data["quantity"]          = $this->input->post("quantity")?$this->input->post("quantity"):'0';   
				  $post_data["guest"]             = $this->input->post("guest")?$this->input->post("guest"):'0'; 
				 	$post_data["currency"]					= $this->input->post("currency") ? $this->input->post("currency") : 'Rp';
					$post_data["price"]             = $this->input->post("price")?$this->input->post("price"):'0';
					$post_data["deposit"]           = $this->input->post("deposit")?$this->input->post("deposit"):'0'; 
					$post_data["add_guest"]         = $this->input->post("add_guest")?$this->input->post("add_guest"):'0'; 
					$post_data["chk_adminfee"]      = $this->input->post("chk_adminfee")?'1':'0';   
					$post_data["admin_fee"]         = $this->input->post("admin_fee")?$this->input->post("admin_fee"):'0';   
					$post_data["chk_tax"]           = $this->input->post("chk_tax")?'1':'0'; 
					$post_data["tax"]               = $this->input->post("tax")?$this->input->post("tax"):'0';  
					$post_data["tip"]               = $this->input->post("tip")?$this->input->post("tip"):'0'; 
					$post_data["chk_tip"]           = $this->input->post("chk_tip")?'1':'0'; 				
					$post_data["total"]             = $this->input->post("total")?$this->input->post("total"):'0'; 
					$post_data["description"]       = $this->input->post("description"); 
					$post_data["status"]     				= $this->input->post("ticket_status");	
	 				//var_dump($post_data);exit;
					$purchase_confirmations= array();
					$confirmations_count = $this->input->post("confirmationscount");
					$j=0;$i=0;
					if($confirmations_count){
						for($i=0;$i<=$confirmations_count;$i++){
							 
							if($this->input->post("chk_".$i)){
								
								$purchase_confirmations[$i]['label'] =$this->input->post("txt_".$i);
						    	$purchase_confirmations[$i]['body']  =$this->input->post($i."_box");
								$j++;
								
							}
						}
					}
					 
					$cnfm_ids = explode(',', rtrim($this->input->post("cnfm_ids"),","));
					
					 
					foreach($cnfm_ids as $id){
						 
						if($this->input->post("new_label".$id)){
							$purchase_confirmations[$j]['label'] =$this->input->post("new_label".$id);
							$purchase_confirmations[$j]['body']  =$this->input->post("confirmation".$id);
							$i++;
						}
						
					}
					
					$post_data['purchase_confirmations'] =  json_encode($purchase_confirmations);					 		
					if($post_data["is_recurring"] =='true'){
						$post_data["forceedit"] =$this->input->post("forceedit")?$this->input->post("forceedit"):'0'; 
						$url =APIURL."admin/admin/ticket-type/update/recurring/".$post_data["ticket_type_id"]."?".TOKEN;
					}			   
					else{
						$url =APIURL."admin/admin/ticket-type/update/".$post_data["ticket_type_id"]."?".TOKEN;
					}
		           			
					 

					$payload = json_encode( $post_data );
					$ch = curl_init($url);	 					
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
					curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));					 
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
					# Send request.
					$result_set = curl_exec($ch);
					curl_close($ch);					 
					$result =  json_decode($result_set);
					echo $result_set;
					exit;
                }
	}
    
}