<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blocklists extends CI_Controller {
    
    var $gen_contents	=	array();
    
    public function __construct()
    {
        parent::__construct();
        $this->merror['error']	= '';
        $this->msuccess['msg']	= '';
        $this->gen_contents['current_controller'] = $this->router->fetch_class();
        $this->load->model(array('admin/admin_model','common_model','admin/block_model', 'admin/chat_model','master_model','admin/permission_model'));
        $this->gen_contents['title']	=	'';
		    presetpastdaterange();
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
        
        $this->access_userid = $this->session->userdata("ADMIN_USERID");
        $this->access_usertypeid = $this->session->userdata("USER_TYPE_ID");
        $this->access_permissions = $this->permission_model->get_all_permission();
    }
    
    public function index()
    {
        if(!$this->master_model->checkAccess('view', BLOCKS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            return FALSE;
        } else {
            $this->blocks();
        }
    }

    public function blocks($init=''){
        if(!$this->master_model->checkAccess('view', BLOCKS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
            return FALSE;
        }

        //$this->ajax_list($init);
        $this->gen_contents['p_title']= 'User Blocks';
        $this->gen_contents['ci_view']= 'admin/block/listing';
        $this->gen_contents['add_link']= '';
        $this->gen_contents['export_link']= base_url() . 'admin/blocklists/export';
        //$this->gen_contents['current_controller'] = "block";
        $breadCrumbs = array( 'admin/blocklists/blocks/0'=>'Blocks');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        $this->template->write_view('content', 'admin/listing',$this->gen_contents);
        $this->template->render();
    }
	public function ajax_list($init=''){
		

		//$headers = apache_request_headers();
		//$is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');		
        $is_ajax = $this->input->is_ajax_request();
		if($is_ajax || $init=='' ){				
			$array_items = array('blocklist_search' => '', 'cur_page'=>'','per_page'=>'');
			$this->session->unset_userdata($array_items);		
		}
		$this->load->library('pagination');	
		
		$config['base_url'] = base_url().'admin/blocklists/ajax_list';
		if('' != $this->input->post ('per_page')){
			$config['per_page']	= $this->input->post ('per_page');
		}else{
			$config['per_page'] = 10 ;
		}
	 
	 
	 	$offset	= safeOffset(4);   
	 	
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
		$this->mcontents['offset'] = $offset;
		$config['uri_segment']					= 4;
		$config['callbackfunction'] 			= 'loadListing';
		$arr_where								= array();
		
		$arr_sort								= array();
		if('' != $this->input->post ('sort_field')){
			$arr_sort['name']	= $this->input->post ('sort_field');
		}else{
			$arr_sort['name'] ='created';
		}
		if('' != $this->input->post ('sort_val')){
			$arr_sort['value']	= $this->input->post ('sort_val');
		}else{
			$arr_sort['value'] ='DESC';
		}
                
                //Search Factor
                $arr_search = array();
                if($this->input->post ('search_name') != "") {
                    //$arr_search["where"]    = $this->input->post ('search_name');
					 $arr_search["where"] = mysql_real_escape_string($this->input->post('search_name')) ;
                    $this->session->set_userdata("blocklist_search", $arr_search["where"]);
                } elseif($this->session->userdata('blocklist_search')){
		            	$arr_search["search_name"] = mysql_real_escape_string($this->session->userdata('blocklist_search')) ;                	
		        }else {
                    $arr_search["where"]    = "";
                    $this->session->set_userdata("blocklist_search", "");
                }
                
		$start_date = $this->session->userdata('startDate')." 00:00:00";
        $end_date = $this->session->userdata('endDate'). " 23:59:59";
        $arr_where = array("b.created_at  >="=>"$start_date","b.created_at  <="=>"$end_date");
		
		$config['total_rows'] 					= $this->block_model->getBlocked ($arr_where, $arr_sort, 'count', $config['per_page'], $offset, $arr_search);
		$this->gen_contents['total_count']			= $config['total_rows'];
	 
		$this->gen_contents['paginate']		= $config;
		$this->gen_contents['data_url']		= $config['base_url'];
		$this->gen_contents['blocks']		= $this->block_model->getBlocked ($arr_where, $arr_sort,'list', $config['per_page'], $offset, $arr_search);
		//if($is_ajax){
		//	$this->load->view('admin/block/listing',$this->gen_contents); 
		//}
		echo json_encode($this->gen_contents);exit;
	}
        
        public function unblock($block_id="", $requestor = "", $blockee = "") {
            try {
                if(!$this->master_model->checkAccess('view', BLOCKS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                    return FALSE;
                }
                if (empty($block_id)) {
                    throw new Exception("Block Id should not be empty");
                }
                $blocked = $this->common_model->getDataExists(array("id"), "block_lists", array("id" => $block_id));
                if (empty($blocked)) {
                    throw new Exception("Blocked details not found");
                }
                //$this->block_model->unblock($block_id);
                $token = md5($requestor+$blockee);
                $url = $this->config->item("unblock_url")."?requestor_id=$requestor&blockee_id=$blockee&token=$token";
                // Open connection
                $ch = curl_init();

                // Set the url, number of POST vars, POST data
                curl_setopt($ch, CURLOPT_URL, $url);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // Disabling SSL Certificate support temporarly
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                
                // Execute post

                $result = curl_exec($ch);
                if ($result === FALSE) 
                {
                    throw new Exception("Please try again");
                } 
                else
                {
                    // Close connection
                    curl_close($ch);
                    $result_decoded = json_decode($result);
                } 
                if($result_decoded->status == "SUCCESS") {
                    sf('success_message', 'Unblock successfull');
                    redirect("admin/blocklists");
                } else {
                    throw new Exception("Please try again");
                }
            } catch (Exception $e) {
                sf('error_message', $e->getMessage());
                redirect("admin/blocklists");
            }
        }
        
      public function conversation() {
        try 
        {
            (!$this->authentication->check_logged_in("admin")) ? redirect('admin') :'' ;
            $channel = $this->input->post('channel');
            $u1 = $this->input->post('u1');
            $u2 = $this->input->post('u2');
            $flag = $this->input->post('flag');
            $message = '<div class="container-fluid-md">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Chat Messages</h4>
                                </div>';
            
            $starter = $u1;
            $joinee = $u2;
           
            $conversations = $this->common_model->getDataExistsArray(array("channel_id","to_id","from_id","message","viewable_from","time"),"chat_messages",array("channel_id"=>$channel));
            
            $user1 = $this->common_model->getDataExists(array("CONCAT_WS(' ', first_name, last_name) as name,id"),"users",array("id"=>$starter));
            $user2 = $this->common_model->getDataExists(array("CONCAT_WS(' ', first_name, last_name) as name,id"),"users",array("id"=>$joinee));
            if(!empty($conversations)) {
                foreach($conversations as $conversation) {
                    
                    if($conversation["viewable_from"] == $starter) {
                        $cur_user =  $user1->name;
                        $color = "#EFF3F4";
                    }
                    else {
                        $cur_user =  $user2->name;
                        $color = "#FFFFFF";
                    } 
                   $message .= '<div class="panel-body" style="background-color:'.$color.'">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">'.$cur_user.'</label>
                                        <div class="col-sm-5">
                                            '.$conversation["message"].'
                                        </div>
                                        <div class="col-sm-3">
                                            '.date("m/d/Y H:i:s",strtotime($conversation["time"])).'
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
            if(!$this->master_model->checkAccess('export', BLOCKS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            $start_date = $this->session->userdata('startDate') . " 00:00:00";
            $end_date = $this->session->userdata('endDate') . " 23:59:59";
            $this->block_model->export($start_date, $end_date, $this->session->userdata("user_search"));
            exit;
        } catch (Exception $e) {
            
        }
    }
}