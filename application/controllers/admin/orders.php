<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller {
    
    var $gen_contents	=	array();    
    public function __construct()
    {
        parent::__construct();
        $this->merror['error']	= '';
        $this->msuccess['msg']	= '';
        $this->load->model(array('master_model','admin/permission_model'));
        $this->gen_contents['title']	=	'';
		$this->config->set_item('site_title', 'Party Host  Admin - Orders');
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
        presetfuturedaterange();
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
        	
            $this->ticket_list();
        }
    }
	
	
    public function ticket_list($init=''){     	     
        $breadCrumbs = array( 'admin/orders/'=>'Orders');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        $this->gen_contents['p_title']= 'Orders List';
        $this->gen_contents['current_controller'] = "orders";
		$this->gen_contents['export_link']= base_url().'admin/orders/export';
		$this->gen_contents['add_link']= '';
        //$this->template->write_view('content', 'admin/events',$this->gen_contents);
		$this->template->write_view('content', 'admin/listing',$this->gen_contents);
        $this->template->render();		 
    }
	
	public function ajax_list(){
		
		
        $config['base_url'] = base_url().'admin/orders/ajax_list';
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
            $arr_sort['name'] = 'event.start_datetime';
        }
        if ('' != $this->input->post('sort_val')) {
            $arr_sort['value'] = $this->input->post('sort_val');
        } else {
            $arr_sort['value'] = 'asc';
        }
        
		if ('' != $this->input->post('sort_status')) {
            $sort_status = $this->input->post('sort_status');
        } else {
            $sort_status = '';
        }
		     
        $arr_search = array();
        /*if ($this->input->post('search_name') != "") {
        	$arr_search["where"] = mysql_real_escape_string($this->input->post('search_name')) ; 
			 $search_string ="&search_fields=_id,customer.first_name,event.venue_name,ticket.ticket_type.ticket_type,ticket.ticket_type.name&search_value=".urlencode($arr_search["where"]);           		
        }else {
           	 $search_string  = "";
            
        }*/
        if($this->input->post ('search_filter') != "") {
			$search_filter =$this->input->post ('search_filter');
        	$arr_search["search_name"] = mysql_real_escape_string($this->input->post('search_name')) ;  
			$search_string  = "&search_fields=$search_filter&search_value=".urlencode($arr_search["search_name"]); // this "_" will wor only for searching convo_id  
			$date_string= "";              
        } 
        else {
            if($this->input->post ('search_name') != "") {
	        	$arr_search["search_name"] = mysql_real_escape_string($this->input->post('search_name')) ;  
				$search_string  = "&search_fields=_id,customer.first_name,event.venue_name,tickettype.ticket_type,order_id,tickettype.description,tickettype.name,event.title,status,transaction_id&search_value=".urlencode($arr_search["search_name"]);        
	        }else {
	            $search_string='';           
	        }
			$start_date = $this->input->post('startDate_iso');		 
        	$end_date =   $this->input->post('endDate_iso');    
			$date_string= "&start_date=$start_date&end_date=$end_date";          
        } 
        /*
        

         "_id" : ObjectId("55d795622a77110300641d2d"),
    "ticket_id" : "55d795622a77110300641d2c",
    "event_id" : "55d77ae0187a0d0300c375f7",
    "ticket_type_id" : "55d77b59187a0d0300c375f8",
    "created_at" : ISODate("2015-08-21T21:17:22.697Z"),
    "fb_id" : "10152619299527920",
    "tickettype" : {
        "_id" : ObjectId("55d77b59187a0d0300c375f8"),
        "name" : "VIP Table Reservation",
        "event_id" : "55d77ae0187a0d0300c375f7",
        "ticket_type" : "reservation",
        "guest" : "6",
        "price" : "1000",
        "deposit" : "0",
        "add_guest" : "200",
        "quantity" : 10,
        "admin_fee" : "100",
        "chk_adminfee" : true,
        "chk_tax" : true,
        "tax" : "4",
        "tip" : "25",
        "chk_tip" : true,
        "status" : "active",
        "tax_amount" : "40.00",
        "tip_amount" : "250.00",
        "total_num" : 1390,
        "total" : "1390.00",
        "description" : "$1000 table Reservation Cr",
        "purchase_confirmations" : [],
        "is_recurring" : false,
        "active" : "active",
        "created_at" : ISODate("2015-08-21T19:26:17.872Z"),
        "price_num" : 1000,
        "deposit_num" : 0,
        "add_guest_num" : 200,
        "tax_amount_num" : 40,
        "tip_amount_num" : 250,
        "guest_num" : 6,
        "cname" : "vip table reservation"
    },
    "status" : "active",
    "transaction_id" : "0",
    "order_id" : "5gWBNP"


        if($this->input->get ('cust_fb_id') != "")
        {
            $search_string  = "&search_fields=customer.fb_id&search_value=".mysql_real_escape_string($this->input->get('cust_fb_id'))

        }
        */
        if($this->input->post ('cust_fb_id') != "")
        {
            $search_string  = "&search_fields=fb_id&search_value=".$this->input->post ('cust_fb_id');
        }

        if($this->input->post ('event_id') != "")
        {
            $search_string  = "&search_fields=event._id&search_value=".$this->input->post ('event_id');
        }

		 //T04:00:00.000Z
         //$start_date = $this->input->post('startDate_iso');		 
         //$end_date =   $this->input->post('endDate_iso');
        // file_put_contents('C:\Users\User\Desktop\1.txt', $start_date.'======'.$end_date);
        //
         $url = APIURL."admin/admin/orders/list?".TOKEN."&per_page=".$config['per_page']."&offset=".$offset."&sort_field=".$arr_sort['name']."&sort_val=".$arr_sort['value'].$date_string.$search_string;
		
		 echo $json = file_get_contents($url);exit;	
    }
	function order_details($id){
		$url =APIURL."admin/admin/order/details/".$id."?".TOKEN;
		echo $json = file_get_contents($url);        
        exit;
	}

    function update($order_id)
    {
        $url =APIURL."admin/admin/order/update/".$order_id."?".TOKEN;
        

         
        
        $name = $this->input->post('name');
        $value = $this->input->post('value');

        $post_data["value"]         = $value;
        $post_data["object"]        = $name; 
         
        $ch = curl_init($url);                   
        $payload = json_encode( $post_data );   
                                        
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));                   
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result_set = curl_exec($ch);
        curl_close($ch);
        
        return $result_set;
        //echo $json = file_get_contents($url);        
        exit;
    }
}    