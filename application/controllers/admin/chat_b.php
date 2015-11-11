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
        presetdaterange();
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
    
	
	public function listall($offset=1){
		
		$offset= $offset==0 ?1:$offset;
		$url = 'https://partyhostapp.herokuapp.com/getallmessages?key=9dayZ9ydHhda98hd98n8hoO!NsdbffZjnLJ&offset='.$offset;		
		$json = file_get_contents($url);		
		if(!isset($_SERVER['HTTP_REFERER'])) {							
		    $this->session->set_userdata("cur_page", 0);			 
		}
    	//$this->ajax_list($init);
        $breadCrumbs = array( 'admin/chat/listall/0'=>'Chat');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        $this->gen_contents['p_title']= 'Chats';
        $this->gen_contents['ci_view']= 'admin/chat/list_all';
        $this->gen_contents['add_link']= "";
        $this->gen_contents['export_link']= base_url().'admin/chat/export';
        $this->gen_contents['current_controller'] = "chat";
		$this->gen_contents['data_url']		= '';
		$this->gen_contents['chat_lists'] = json_decode($json);;
        $this->template->write_view('content', 'admin/listing',$this->gen_contents);
        $this->template->render();
	}
	
	
	
	
     public function chat_list($init=''){
     	if(!isset($_SERVER['HTTP_REFERER'])) {							
		    $this->session->set_userdata("cur_page", 0);			 
		}
    	$this->ajax_list($init);
        $breadCrumbs = array( 'admin/chat/chat_list/0'=>'Chat');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        $this->gen_contents['p_title']= 'Chats';
        $this->gen_contents['ci_view']= 'admin/chat/list_all';
        $this->gen_contents['add_link']= "";
        $this->gen_contents['export_link']= base_url().'admin/chat/export';
        $this->gen_contents['current_controller'] = "chat";
        $this->template->write_view('content', 'admin/listing',$this->gen_contents);
        $this->template->render();
    }
    public function ajax_list($init=''){
		$is_ajax = $this->input->is_ajax_request();		
        $this->load->library('pagination');	
		if($is_ajax || $init=='' ){				
			$array_items = array('chat_search' => '', 'cur_page'=>'','per_page'=>'','sort_field'=>'','sort_val'=>'');
			$this->session->unset_userdata($array_items);		
		}
        $config['base_url'] = base_url().'admin/chat/ajax_list';
        $perPage = $this->session->userdata('per_page');
        if ('' != $this->input->post('per_page')) {
            $config['per_page'] = $this->input->post('per_page');            
            $perPage = '';
        }        
        else {
            $config['per_page'] = 10;
        }
        
        if($perPage==''):
            $this->session->set_userdata(array('per_page'=>$config['per_page']));
        else:
            $config['per_page'] = $this->session->userdata('per_page');
        endif;


        $offset	= safeOffset(4)/$config['per_page'];   
	 	
	 	if($offset){			
			$this->session->set_userdata("cur_page", $offset);
		}else{
			if($this->uri->segment(4)!==0){				
				$offset	= $this->session->userdata('cur_page');				
			}				
			else{				
				$offset=0;
			}		
		}
        $this->gen_contents['offset'] = $offset;
        $config['uri_segment']					= 4;
        $config['callbackfunction'] 			= 'loadListing';
        $arr_where								= array();

        $arr_sort								= array();
        if('' != $this->input->post ('sort_field')){
               $arr_sort['name']	= $this->input->post ('sort_field');
			   $this->session->set_userdata("sort_field", $arr_sort['name']);
        }
		elseif($this->session->userdata('sort_field')){
            	$arr_sort['name'] = $this->session->userdata('sort_field') ;                	
        }else {
        	$arr_sort['name'] ='created_at';           
            $this->session->set_userdata("sort_field", $arr_sort['name']);
        } 
		
		if('' != $this->input->post ('sort_val')){
               $arr_sort['value']	= $this->input->post ('sort_val');
			   $this->session->set_userdata("sort_val", $arr_sort['value']);
        }
		elseif($this->session->userdata('sort_val')){
            	$arr_sort['value'] = $this->session->userdata('sort_val') ;                	
        }else {
        	$arr_sort['value'] ='DESC';           
            $this->session->set_userdata("sort_val", "DESC");
        }
		
        
        
        //Search Factor
        $arr_search = array();
        if($this->input->post ('search_name') != "") {
        	$arr_search["where"] = mysql_real_escape_string($this->input->post('search_name')) ;
            //["where"]    = $this->input->post ('search_name');
            $this->session->set_userdata("chat_search", $arr_search["where"]);
        } elseif($this->session->userdata('chat_search')){
            	$arr_search["search_name"] = mysql_real_escape_string($this->session->userdata('chat_search')) ;                	
        }else {
            $arr_search["where"]    = "";
            $this->session->set_userdata("chat_search", "");
        }          
		
		$starttime = new DateTime($this->session->userdata('startDate')." 00:00:00");
        $start_date = $starttime->format(DateTime::ISO8601) ;
		
		$endtime = new DateTime($this->session->userdata('endDate')." 23:59:59");
        $end_date = $endtime->format(DateTime::ISO8601) ;
		
         //= date("c", strtotime($this->session->userdata('endDate'). " 23:59:59"));
        //$arr_where = array("a.created_at >="=>"$start_date","a.created_at  <="=>"$end_date");
        $serchval = urlencode($arr_search["where"]);
		$url="http://partyhostapp.herokuapp.com/searchadminconversations?key=9dayZ9ydHhda98hd98n8hoO!NsdbffZjnLJ&per_page=".$config['per_page']."&offset=".$offset."&sort_field=".$arr_sort['name']."&sort_val=".$arr_sort['value']."&start_date=$start_date&end_date=$end_date&search_fields=messages.message,topic_venue,guest,host,&search_value=".$serchval;
		//$this->gen_contents['exp_url']=
		//echo $url;
		$json = @file_get_contents($url);	
		$chat_data = json_decode($json);
		//$config['total_rows'] 					= $this->chat_model->getAllChats ($arr_where, $arr_sort, 'count', $config['per_page'], $offset, $arr_search);
        $config['total_rows']						= @$chat_data->total_count;
        $this->gen_contents['total_count']			= $config['total_rows'];		
        $this->pagination->initialize($config);
        $this->gen_contents['paginate']		= $this->pagination->create_links_ajax();
        $this->gen_contents['data_url']		= $config['base_url'];
       // $this->gen_contents['chats']		= $this->chat_model->getAllChats ($arr_where, $arr_sort,'list', $config['per_page'], $offset, $arr_search);
        $this->gen_contents['chats']		=@$chat_data->conversations;
        //echo $this->db->last_query();exit;
        if($is_ajax){
                $this->load->view('admin/chat/list_all',$this->gen_contents); 
        }

    }
    
    public function conversation() {
        try 
        {
            (!$this->authentication->check_logged_in("admin")) ? redirect('admin') :'' ;
            
            $u1 = $this->input->post('u1');
            $u2 = $this->input->post('u2');
            
			$from_name = $this->input->post('u1name');
			$to_name = $this->input->post('u2name');
			
			$url='https://partyhostapp.herokuapp.com/getadminconversation?key=9dayZ9ydHhda98hd98n8hoO!NsdbffZjnLJ&from_id='.$u1.'&to_id='.$u2;
			//echo $url;
			$json = file_get_contents($url);	
		 	$chat_data = json_decode($json);
            $message = '<div class="container-fluid-md">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Chat Messages</h4>
                                </div>';
            
           
            $conversations = @$chat_data->messages;
           
            if(!empty($conversations)) {
            	$user_id='';
                foreach($conversations as $conversation) {
                    if($conversation->from_id == $u1) {
                        $cur_user =  $from_name;
                        $color = "#EFF3F4";
						$user_id = $u1;
                    }
                    else {
                        $cur_user =  $to_name;
                        $color = "#FFFFFF";
						$user_id = $u2;
                    }
                    
                   $message .= '<div class="panel-body" style="background-color:'.$color.'">
                                    <div class="form-group ">
                                        <label for="inputEmail3" class="col-sm-4 control-label"><a href="'.base_url().'admin/user/user_details/'.$user_id.'" target="_blank">'.$cur_user.'</a></label>
                                        <div class="col-sm-5">
                                            '.$conversation->message.'
                                        </div>
                                        <div class="col-sm-3">
                                            '.date("m/d/Y H:i:s",strtotime($conversation->created_at)).'
                                        </div>
                                    </div>
                                    
                                </div>';
                }
            } else {
                $message .= '<div class="panel-body">
                                <div class="form-group">No chats available</div></div>';
            }
            $message .= "</div></div>";
            echo $message;
        }
        catch (Exception $e) 
        {
            
        }
        exit;
    }
    
    public function export() {
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
	        
			$url="http://partyhostapp.herokuapp.com/searchadminconversations?key=9dayZ9ydHhda98hd98n8hoO!NsdbffZjnLJ&per_page=&offset=&sort_field=".$arr_sort['name']."&sort_val=".$arr_sort['value']."&start_date=$start_date&end_date=$end_date&search_fields=messages.message,topic_venue,guest,host,&search_value=".$serchval;
			
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