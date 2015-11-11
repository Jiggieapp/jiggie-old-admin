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
        (!$this->authentication->check_logged_in("admin")) ? redirect('admin') : '';
        
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
    
     public function chat_list(){
    	$this->ajax_list();
        $breadCrumbs = array( 'admin/chat'=>'Chat');
        $this->gen_contents['breadcrumbs'] = $breadCrumbs;
        $this->gen_contents['p_title']= 'Chats';
        $this->gen_contents['ci_view']= 'admin/chat/listing';
        $this->gen_contents['add_link']= "";
        $this->gen_contents['export_link']= base_url().'admin/chat/export';
        $this->gen_contents['current_controller'] = "chat";
        $this->template->write_view('content', 'admin/listing',$this->gen_contents);
        $this->template->render();
    }
    public function ajax_list(){
	$headers = apache_request_headers();
        $is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');		
        $this->load->library('pagination');	

        $config['base_url'] = base_url().'admin/chat/ajax_list';
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
                $arr_sort['name'] ='a.channel_id';
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
            $this->session->set_userdata("user_search", $arr_search["where"]);
        } else {
            $arr_search["where"]    = "";
            $this->session->set_userdata("user_search", "");
        }
                
        $start_date = $this->session->userdata('startDate')." 00:00:00";
        $end_date = $this->session->userdata('endDate'). " 23:59:59";
        $arr_where = array("a.created_at >="=>"$start_date","a.created_at  <="=>"$end_date");

        $config['total_rows'] 					= $this->chat_model->getAllChats ($arr_where, $arr_sort, 'count', $config['per_page'], $offset, $arr_search);
        $this->gen_contents['total_count']			= $config['total_rows'];
        $this->pagination->initialize($config);
        $this->gen_contents['paginate']		= $this->pagination->create_links_ajax();
        $this->gen_contents['data_url']		= $config['base_url'];
        $this->gen_contents['chats']		= $this->chat_model->getAllChats ($arr_where, $arr_sort,'list', $config['per_page'], $offset, $arr_search);
        //echo $this->db->last_query();exit;
        if($is_ajax){
                $this->load->view('admin/chat/listing',$this->gen_contents); 
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
            
            if($flag == "u1") {
                $starter = $u1;
                $joinee = $u2;
            } else {
                $starter = $u2;
                $joinee = $u1;
            }
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
                                    <div class="form-group ">
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
            if(!$this->master_model->checkAccess('export', CHATS_MODULE, $this->access_userid, $this->access_usertypeid, $this->access_permissions)) {
                return FALSE;
            }
            $start_date = $this->session->userdata('startDate') . " 00:00:00";
            $end_date = $this->session->userdata('endDate') . " 23:59:59";
            $this->chat_model->export($start_date, $end_date, $this->session->userdata("user_search"));
            exit;
        } catch (Exception $e) {
            
        }
    }
    
}    